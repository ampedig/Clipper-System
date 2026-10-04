<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ClipCampaign;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda clipper dengan ringkasan saldo dan performa views.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $totalViews = 0;
        $approvedClipsCount = 0;

        if ($user) {
            // Optimasi single aggregate query untuk menghitung total penayangan & klip disetujui sekaligus
            $stats = $user->clipSubmissions()
                ->selectRaw("
                    COALESCE(SUM(current_views), 0) as total_views,
                    COUNT(CASE WHEN status IN ('approved', 'active', 'completed') THEN 1 END) as approved_count
                ")
                ->first();

            $totalViews = (int) ($stats->total_views ?? 0);
            $approvedClipsCount = (int) ($stats->approved_count ?? 0);
        }

        // Ambil kampanye terbaru berstatus aktif beserta hitungan klip yang disetujui (eager load count)
        $latestCampaigns = ClipCampaign::active()
            ->withCount(['clipSubmissions as approved_submissions_count' => function ($query) {
                $query->whereIn('status', ['approved', 'active', 'completed']);
            }])
            ->latest('id')
            ->take(6)
            ->get();

        return view('app.index', compact('user', 'totalViews', 'approvedClipsCount', 'latestCampaigns'));
    }
}
