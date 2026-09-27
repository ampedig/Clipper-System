<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletTransactionController extends Controller
{
    /**
     * Menampilkan riwayat transaksi saldo (mutasi komisi & penarikan) para clipper.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = WalletTransaction::with('user:id,name,email')
            ->latest('id');

        $type = $request->input('type');
        if ($type === 'credit' || $type === 'tambah') {
            $query->where('type', 'credit');
        } elseif ($type === 'debit' || $type === 'kurang') {
            $query->where('type', 'debit');
        }

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $transactions = $query->paginate($perPage)->withQueryString();

        return view('dashboard.wallet_transactions.index', compact('transactions'));
    }
}
