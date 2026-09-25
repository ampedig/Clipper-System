<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Services\TikTokUrlService;
use Illuminate\Http\Request;

class ClipSubmissionController extends Controller
{
    /**
     * Display a listing of the authenticated user's clip submissions.
     */
    public function index(Request $request)
    {
        $submissions = $request->user()
            ->clipSubmissions()
            ->with('clipCampaign')
            ->latest()
            ->get();

        return view('app.submissions.index', compact('submissions'));
    }

    /**
     * Display the specified clip submission for the authenticated user.
     */
    public function show(Request $request, ClipSubmission $clipSubmission)
    {
        // Guard against unauthorized access to other users' submissions
        abort_if($clipSubmission->user_id !== $request->user()->id, 403, 'Akses ditolak.');

        $clipSubmission->load('clipCampaign');

        return view('app.submissions.show', [
            'submission' => $clipSubmission,
        ]);
    }

    /**
     * Store a newly created clip submission.
     */
    public function store(Request $request, ClipCampaign $campaign, TikTokUrlService $tiktokService)
    {
        $request->validate([
            'submitted_url' => ['required', 'url', 'regex:/tiktok\.com/i'],
        ], [
            'submitted_url.regex' => 'Link yang dimasukkan harus berupa link TikTok.',
        ]);

        // 1. Ekstrak Video ID
        $videoId = $tiktokService->extractVideoId($request->submitted_url);

        if (! $videoId) {
            return back()->with('error', 'Gagal memproses link TikTok. Pastikan link video valid.');
        }

        // 2. Cek apakah video sudah pernah didaftarkan
        // Bisa di campaign yang sama atau campaign lain (tergantung kebutuhan, kita cek global agar tidak ada kecurangan lintas campaign)
        $exists = ClipSubmission::where('video_id', $videoId)->exists();

        if ($exists) {
            return back()->with('error', 'Video ini sudah pernah didaftarkan di sistem kami.');
        }

        // 3. Simpan ke database
        $submission = $campaign->clipSubmissions()->create([
            'user_id' => $request->user()->id,
            'submitted_url' => $request->submitted_url,
            'video_id' => $videoId,
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $pesan = "📝 <b>NEW CLIP SUBMISSION</b> 📝\n"
               ."━━━━━━━━━━━━━━━━━━━━\n"
               ."👤 <b>User:</b> {$request->user()->name}\n"
               ."🏷 <b>Campaign:</b> {$campaign->title}\n"
               ."🔗 <b>Link TikTok:</b> <a href=\"{$request->submitted_url}\">Tonton Video</a>\n"
               .'⏳ <b>Status:</b> Pending Check';

        SendTelegramMessageJob::dispatch($pesan, config('telegram.topics.clip_submit'));

        return back()->with('success', 'Link video berhasil didaftarkan! Sistem akan mengecek views setiap jam 12 malam.');
    }
}
