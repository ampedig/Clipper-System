<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama admin beserta metrik ringkasan, chart statistik, dan data terbaru.
     */
    public function index(): View
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Campaign Aktif (status active secara realtime)
        $activeCampaignsCount = ClipCampaign::where('status', CampaignStatus::Active)->count();

        // 2. Total Komisi Bulan Ini (kredit komisi masuk ke user, mengecualikan refund penarikan)
        $monthlyCommissionTotal = (int) WalletTransaction::where('type', 'credit')
            ->where('notes', 'not like', '%Refund%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 3. Klip Di Submit Bulan Ini
        $monthlySubmissionsCount = ClipSubmission::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 4. Jumlah Withdraw (akumulasi nominal penarikan sukses/completed secara lifetime)
        $completedWithdrawalsTotal = (int) Withdrawal::where('status', 'completed')
            ->sum('amount');

        // 5. 5 Pengajuan Klip Terbaru
        $recentSubmissions = ClipSubmission::with(['user', 'clipCampaign'])
            ->latest('id')
            ->take(5)
            ->get();

        // 6. Data Tren Pengajuan Klip (7 Hari & 30 Hari)
        $thirtyDaysAgo = now()->subDays(29)->startOfDay();
        $submissionsLast30Days = ClipSubmission::where('created_at', '>=', $thirtyDaysAgo)
            ->get(['id', 'status', 'created_at']);

        $buildTrendData = function (int $days) use ($submissionsLast30Days): array {
            $categories = [];
            $submitted = [];
            $approved = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $targetDate = now()->subDays($i);
                $dateKey = $targetDate->format('Y-m-d');
                $categories[] = $days <= 7
                    ? $targetDate->translatedFormat('D, d M')
                    : $targetDate->translatedFormat('d M');

                $dayItems = $submissionsLast30Days->filter(function ($item) use ($dateKey) {
                    return $item->created_at->format('Y-m-d') === $dateKey;
                });

                $submitted[] = $dayItems->count();
                $approved[] = $dayItems->whereIn('status', ['active', 'approved', 'completed'])->count();
            }

            return [
                'categories' => $categories,
                'submitted' => $submitted,
                'approved' => $approved,
            ];
        };

        $trendData7Days = $buildTrendData(7);
        $trendData30Days = $buildTrendData(30);

        // 7. Data Distribusi Status Klip (Donut Chart)
        $statusCounts = ClipSubmission::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $pendingCount = (int) ($statusCounts['pending'] ?? 0);
        $activeCount = (int) (($statusCounts['active'] ?? 0) + ($statusCounts['approved'] ?? 0));
        $completedCount = (int) ($statusCounts['completed'] ?? 0);
        $rejectedCount = (int) ($statusCounts['rejected'] ?? 0);

        $statusChartData = [
            ['name' => 'Menunggu Review', 'value' => $pendingCount, 'itemStyle' => ['color' => '#f59e0b']],
            ['name' => 'Aktif / Disetujui', 'value' => $activeCount, 'itemStyle' => ['color' => '#10b981']],
            ['name' => 'Selesai', 'value' => $completedCount, 'itemStyle' => ['color' => '#8b5cf6']],
            ['name' => 'Ditolak', 'value' => $rejectedCount, 'itemStyle' => ['color' => '#f43f5e']],
        ];

        return view('dashboard.index', compact(
            'activeCampaignsCount',
            'monthlyCommissionTotal',
            'monthlySubmissionsCount',
            'completedWithdrawalsTotal',
            'recentSubmissions',
            'trendData7Days',
            'trendData30Days',
            'statusChartData'
        ));
    }
}
