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

        return view('app.campaign.show', compact('campaign'));
    }
}
