<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ClipCampaign;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Menampilkan daftar kampanye clip aktif untuk clipper.
     * Hanya mengambil kolom-kolom yang diperlukan untuk card index agar performa optimal.
     */
    public function index(Request $request): View
    {
        $campaigns = ClipCampaign::active()
            ->select([
                'id',
                'title',
                'description',
                'thumbnail',
                'commission_amount',
                'view_threshold',
                'clipper_limit',
                'end_at',
                'status',
            ])
            ->latest('id')
            ->get();

        return view('app.campaign.index', compact('campaigns'));
    }
}
