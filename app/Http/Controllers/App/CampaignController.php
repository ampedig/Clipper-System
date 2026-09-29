<?php

namespace App\Http\Controllers\App;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\ClipCampaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Menampilkan daftar kampanye clip aktif untuk clipper.
     * Mendukung pencarian database dan pagination infinite scroll (20 item per halaman).
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->input('q');

        $query = ClipCampaign::active()
            ->where(function ($q) {
                $q->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('clipper_limit')
                    ->orWhere('clipper_limit', 0)
                    ->orWhereRaw('(SELECT COUNT(*) FROM clip_submissions WHERE clip_submissions.clip_campaign_id = clip_campaigns.id AND clip_submissions.status IN ("active", "approved", "completed")) < clip_campaigns.clipper_limit');
            })
            ->select([
                'id',
                'title',
                'slug',
                'description',
                'thumbnail',
                'commission_amount',
                'view_threshold',
                'clipper_limit',
                'end_at',
                'status',
            ])
            ->when($request->filled('q'), function ($q) use ($search) {
                $keyword = '%'.trim($search).'%';
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('title', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->withCount(['clipSubmissions as approved_submissions_count' => function ($query) {
                $query->whereIn('status', ['approved', 'active', 'completed']);
            }])
            ->latest('id');

        $campaigns = $query->paginate(20);

        if ($request->ajax()) {
            $html = '';
            foreach ($campaigns as $campaign) {
                $html .= view('app.campaign.partials.item', compact('campaign'))->render();
            }

            return response()->json([
                'html' => $html,
                'has_more' => $campaigns->hasMorePages(),
                'next_page' => $campaigns->hasMorePages() ? $campaigns->currentPage() + 1 : null,
                'total' => $campaigns->total(),
            ]);
        }

        return view('app.campaign.index', compact('campaigns', 'search'));
    }

    /**
     * Menampilkan halaman detail kampanye clip untuk clipper.
     * Hanya kampanye berstatus aktif yang dapat diakses oleh publik/clipper.
     */
    public function show(ClipCampaign $campaign): View
    {
        abort_unless($campaign->status === CampaignStatus::Active, 404);

        $campaign->loadCount(['clipSubmissions as approved_submissions_count' => function ($query) {
            $query->whereIn('status', ['approved', 'active', 'completed']);
        }]);

        $isFull = $campaign->clipper_limit > 0 && $campaign->approved_submissions_count >= $campaign->clipper_limit;

        $referenceSubmissions = $campaign->clipSubmissions()
            ->asReference()
            ->with('user:id,name,email')
            ->orderByDesc('current_views')
            ->take(10)
            ->get();

        return view('app.campaign.show', compact('campaign', 'isFull', 'referenceSubmissions'));
    }
}
