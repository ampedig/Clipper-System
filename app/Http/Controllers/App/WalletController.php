<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Display a listing of the authenticated user's wallet transactions.
     * Supports 25-item pagination and infinite scroll via AJAX.
     */
    public function index(Request $request): View|JsonResponse
    {
        $type = $request->input('type', 'all');

        $query = $request->user()
            ->walletTransactions()
            ->latest('created_at');

        if ($type === 'income') {
            $query->where('type', 'credit');
        } elseif ($type === 'outcome') {
            $query->where('type', 'debit');
        }

        $transactions = $query->paginate(25);

        // Group the current page's transactions dynamically based on date
        $groupedTransactions = $transactions->getCollection()->groupBy(function (WalletTransaction $tx): string {
            $createdAt = $tx->created_at->locale('id');

            if ($createdAt->isToday()) {
                return 'Hari Ini';
            }

            if ($createdAt->isYesterday()) {
                return 'Kemarin';
            }

            return $createdAt->isoFormat('D MMM Y');
        });

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('app.wallet.partials.groups', compact('groupedTransactions'))->render();

            return response()->json([
                'html' => $html,
                'has_more' => $transactions->hasMorePages(),
                'next_page' => $transactions->hasMorePages() ? $transactions->currentPage() + 1 : null,
                'total' => $transactions->total(),
            ]);
        }

        return view('app.wallet.index', [
            'groupedTransactions' => $groupedTransactions,
            'transactions' => $transactions,
            'currentType' => $type,
        ]);
    }
}
