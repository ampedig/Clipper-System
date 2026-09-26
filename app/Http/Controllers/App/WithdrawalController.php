<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Menampilkan form penarikan saldo (withdrawal) untuk clipper.
     */
    public function create(Request $request): View
    {
        $user = $request->user()->load('withdrawChannel');

        // Ambil batas minimal penarikan dari setting sistem (default 50.000)
        $minimalWd = (int) (Setting::where('key', 'minimal_wd')->value('value') ?? 50000);

        // Biaya admin dari channel rekening yang dipilih user
        $adminFee = (int) ($user->withdrawChannel?->fee ?? 0);

        $hasRekening = (bool) ($user->withdraw_channel_id && $user->account_number && $user->account_name);

        return view('app.withdrawals.create', compact('user', 'minimalWd', 'adminFee', 'hasRekening'));
    }

    /**
     * Memproses permohonan penarikan saldo clipper.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user()->load('withdrawChannel');

        // Guard clause: Pastikan user sudah mengatur rekening pencairan
        if (! $user->withdraw_channel_id || ! $user->account_number || ! $user->account_name) {
            return redirect()->route('app.rekening')
                ->with('error', 'Silakan atur rekening pencairan dana Anda terlebih dahulu sebelum melakukan penarikan.');
        }

        $minimalWd = (int) (Setting::where('key', 'minimal_wd')->value('value') ?? 50000);

        $validated = $request->validate([
            'amount' => ['required', 'integer', "min:{$minimalWd}", "max:{$user->balance}"],
        ], [
            'amount.required' => 'Nominal penarikan wajib diisi.',
            'amount.integer' => 'Nominal penarikan harus berupa angka bulat.',
            'amount.min' => 'Minimal penarikan adalah Rp'.number_format($minimalWd, 0, ',', '.').'.',
            'amount.max' => 'Nominal penarikan melebihi saldo tersedia Anda.',
        ]);

        $amount = (int) $validated['amount'];
        $fee = (int) ($user->withdrawChannel?->fee ?? 0);
        $netAmount = max(0, $amount - $fee);

        DB::transaction(function () use ($user, $amount, $fee, $netAmount) {
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();

            if ($lockedUser->balance < $amount) {
                throw ValidationException::withMessages([
                    'amount' => ['Saldo Anda tidak mencukupi untuk melakukan penarikan ini.'],
                ]);
            }

            $balanceBefore = $lockedUser->balance;
            $balanceAfter = $balanceBefore - $amount;

            // 1. Kurangi saldo user secara presisi
            $lockedUser->update([
                'balance' => $balanceAfter,
            ]);

            // 2. Catat mutasi dompet (debit)
            $lockedUser->walletTransactions()->create([
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'notes' => 'Penarikan saldo ke '.$lockedUser->withdrawChannel->name.' ('.$lockedUser->account_number.')',
            ]);

            // 3. Catat permohonan withdrawal status pending
            $lockedUser->withdrawals()->create([
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'bank_name' => $lockedUser->withdrawChannel->name,
                'account_number' => $lockedUser->account_number,
                'account_name' => $lockedUser->account_name,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('app.withdrawals.create')
            ->with('success', 'Permintaan penarikan saldo sebesar Rp'.number_format($amount, 0, ',', '.').' berhasil diajukan dan sedang diproses.');
    }
}
