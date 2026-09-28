<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WithdrawalApprovedMail;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the withdrawals.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = Withdrawal::with('user')->latest('id');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                // 1. Nama & Email User
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                // 2. Bank & Rekening
                    ->orWhere('bank_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%")
                // 3. Tanggal (created_at)
                    ->orWhere('created_at', 'like', "%{$search}%");

                // Format tanggal misal DD-MM-YYYY atau DD/MM/YYYY
                if (preg_match('/^\d{1,2}[-\/]\d{1,2}[-\/]\d{2,4}$/', $search)) {
                    try {
                        $parsedDate = Carbon::parse($search)->format('Y-m-d');
                        $q->orWhereDate('created_at', $parsedDate);
                    } catch (\Throwable $e) {
                        // Abaikan jika gagal parse
                    }
                }

                // 4. Nominal (Angka utuh / format seperti 50.000)
                $cleanAmount = preg_replace('/[^0-9]/', '', $search);
                if ($cleanAmount !== '') {
                    $q->orWhere('amount', 'like', "%{$cleanAmount}%")
                        ->orWhere('net_amount', 'like', "%{$cleanAmount}%");
                }
            });
        }

        $withdrawals = $query->paginate($perPage)->withQueryString();

        return view('dashboard.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Display the specified withdrawal detail.
     */
    public function show(Withdrawal $withdrawal): View
    {
        $withdrawal->load('user');

        return view('dashboard.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Update the status of a specific withdrawal.
     */
    public function updateStatus(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:processing,completed,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $validated['status'];
        $notes = $validated['notes'] ?? null;

        // Early return jika status sudah sesuai
        if ($withdrawal->status === $newStatus) {
            return back()->with('info', 'Status penarikan tidak berubah.');
        }

        // Early return jika penarikan sudah selesai/ditolak sebelumnya
        if (in_array($withdrawal->status, ['completed', 'rejected'])) {
            return back()->with('error', 'Penarikan yang sudah selesai atau ditolak tidak dapat diubah lagi statusnya.');
        }

        DB::transaction(function () use ($withdrawal, $newStatus, $notes) {
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $withdrawal->user_id)->lockForUpdate()->first();

            // Logic khusus jika penarikan DITOLAK
            if ($newStatus === 'rejected') {
                $balanceBefore = $lockedUser->balance;
                $balanceAfter = $balanceBefore + $withdrawal->amount;

                // 1. Kembalikan saldo utuh ke user (amount, bukan net_amount)
                $lockedUser->update([
                    'balance' => $balanceAfter,
                ]);

                // 2. Catat mutasi pengembalian saldo (credit)
                $lockedUser->walletTransactions()->create([
                    'type' => 'credit',
                    'amount' => $withdrawal->amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'notes' => 'Pengembalian saldo: Penarikan dana ditolak (Refund)',
                ]);
            }

            // Update status withdrawal
            $withdrawal->update([
                'status' => $newStatus,
                'notes' => $notes,
                'processed_at' => in_array($newStatus, ['completed', 'rejected']) ? now() : null,
            ]);
        });

        // Kirim Notifikasi Telegram
        $amountFormatted = 'Rp '.number_format($withdrawal->amount, 0, ',', '.');
        $netFormatted = 'Rp '.number_format($withdrawal->net_amount, 0, ',', '.');

        if ($newStatus === 'completed') {
            $msg = "✅ <b>Penarikan Dana Selesai</b>\n";
            $msg .= "ID: #{$withdrawal->id}\n";
            $msg .= "Clipper: {$withdrawal->user->name}\n";
            $msg .= "Tujuan: {$withdrawal->bank_name} ({$withdrawal->account_name})\n";
            $msg .= "Nominal: {$amountFormatted}\n";
            $msg .= "Diterima: {$netFormatted}\n\n";
            $msg .= 'Dana telah berhasil ditransfer ke rekening tujuan.';
            TelegramService::sendMessage($msg);

            // Kirim notifikasi email konfirmasi ke user clipper
            if (! empty($withdrawal->user?->email)) {
                try {
                    Mail::to($withdrawal->user->email)->send(new WithdrawalApprovedMail($withdrawal));
                } catch (\Throwable $e) {
                    Log::error('Gagal mengirim email konfirmasi penarikan disetujui: '.$e->getMessage(), [
                        'withdrawal_id' => $withdrawal->id,
                        'user_id' => $withdrawal->user_id,
                    ]);
                }
            }
        } elseif ($newStatus === 'rejected') {
            $msg = "❌ <b>Penarikan Dana Ditolak</b>\n";
            $msg .= "ID: #{$withdrawal->id}\n";
            $msg .= "Clipper: {$withdrawal->user->name}\n";
            $msg .= "Nominal: {$amountFormatted}\n";
            $msg .= 'Alasan: '.($notes ?: 'Tidak ada keterangan')."\n\n";
            $msg .= 'Saldo telah dikembalikan (refund) ke dompet Clipper.';
            TelegramService::sendMessage($msg);
        }

        return back()->with('success', 'Status penarikan dana berhasil diperbarui menjadi '.ucfirst($newStatus).'.');
    }
}
