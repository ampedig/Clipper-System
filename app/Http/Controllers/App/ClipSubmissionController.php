<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipCampaign;
use App\Models\ClipSubmission;
use App\Models\Setting;
use App\Services\TikTokScraperService;
use App\Services\TikTokUrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClipSubmissionController extends Controller
{
    /**
     * Display a listing of the authenticated user's clip submissions.
     */
    public function index(Request $request): View|JsonResponse
    {
        $status = $request->input('status', 'all');

        $query = $request->user()
            ->clipSubmissions()
            ->with('clipCampaign')
            ->latest();

        if ($status === 'diproses') {
            $query->where('status', 'pending');
        } elseif ($status === 'disetujui') {
            $query->whereIn('status', ['active', 'approved', 'completed']);
        } elseif ($status === 'ditolak') {
            $query->where('status', 'rejected');
        }

        $submissions = $query->paginate(10);

        if ($request->ajax()) {
            $html = '';
            foreach ($submissions as $sub) {
                $html .= view('app.submissions.partials.item', compact('sub'))->render();
            }

            return response()->json([
                'html' => $html,
                'has_more' => $submissions->hasMorePages(),
                'next_page' => $submissions->hasMorePages() ? $submissions->currentPage() + 1 : null,
                'total' => $submissions->total(),
            ]);
        }

        $homeAnnouncement = Setting::where('key', 'home_announcement')->value('value');

        return view('app.submissions.index', compact('submissions', 'status', 'homeAnnouncement'));
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
    public function store(
        Request $request,
        ClipCampaign $campaign,
        TikTokUrlService $tiktokService,
        TikTokScraperService $scraperService
    ) {
        $request->validate([
            'submitted_url' => ['required', 'url', 'regex:/tiktok\.com/i'],
        ], [
            'submitted_url.regex' => 'Link yang dimasukkan harus berupa link TikTok.',
        ]);

        $user = $request->user();

        // 1. Guard: Pastikan user memiliki setidaknya satu akun TikTok yang sudah berstatus terverifikasi
        $verifiedAccounts = $user->tiktokAccounts()
            ->where('is_verified', true)
            ->pluck('username')
            ->map(fn ($u) => strtolower(ltrim($u, '@')))
            ->all();

        if (empty($verifiedAccounts)) {
            return back()->with('error', 'Anda belum memiliki akun TikTok yang terverifikasi. Silakan daftarkan dan verifikasi akun TikTok Anda terlebih dahulu di menu Profil.');
        }

        // 2. Ekstrak canonical URL, Video ID, dan username author dari link TikTok
        $parsed = $tiktokService->parseVideo($request->submitted_url);
        $videoId = $parsed['video_id'];
        $authorUsername = $parsed['username'];

        if (! $videoId) {
            return back()->with('error', 'Gagal memproses link TikTok. Pastikan link video atau foto valid.');
        }

        // Fallback: Jika username pengunggah tidak terdeteksi dari struktur URL, coba ambil lewat scraper
        if (! $authorUsername) {
            $stats = $scraperService->getVideoStats($parsed['url'] ?: $request->submitted_url);
            if (! empty($stats['uploader'])) {
                $authorUsername = strtolower(ltrim($stats['uploader'], '@'));
            }
        }

        if (! $authorUsername) {
            return back()->with('error', 'Gagal mendeteksi pemilik konten TikTok ini. Pastikan konten bersifat publik dan link dapat diakses.');
        }

        // 3. Guard: Cek apakah konten diunggah oleh salah satu akun TikTok terverifikasi milik user
        if (! in_array(strtolower($authorUsername), $verifiedAccounts, true)) {
            $verifiedList = implode(', ', array_map(fn ($u) => '@'.$u, $verifiedAccounts));

            return back()->with('error', "Konten ini diunggah oleh akun @{$authorUsername}, bukan dari akun TikTok terverifikasi milik Anda ({$verifiedList}). Pastikan mengunggah konten dari akun yang telah diverifikasi.");
        }

        // 4. Guard: Cek apakah konten sudah pernah didaftarkan di sistem
        $exists = ClipSubmission::where('video_id', $videoId)->exists();

        if ($exists) {
            return back()->with('error', 'Konten ini sudah pernah didaftarkan di sistem kami.');
        }

        // 5. Simpan ke database
        $submission = $campaign->clipSubmissions()->create([
            'user_id' => $user->id,
            'submitted_url' => $parsed['url'] ?: $request->submitted_url,
            'video_id' => $videoId,
            'status' => 'pending',
            'current_views' => 0,
            'credited_views' => 0,
            'total_earned' => 0,
        ]);

        $pesan = "📝 <b>NEW CLIP SUBMISSION</b> 📝\n"
               ."━━━━━━━━━━━━━━━━━━━━\n"
               ."👤 <b>User:</b> {$user->name}\n"
               ."📱 <b>Akun TikTok:</b> @{$authorUsername}\n"
               ."🏷 <b>Campaign:</b> {$campaign->title}\n"
               ."🔗 <b>Link TikTok:</b> <a href=\"{$request->submitted_url}\">Lihat Konten</a>\n"
               .'⏳ <b>Status:</b> Pending Check';

        SendTelegramMessageJob::dispatch($pesan, config('telegram.topics.clip_submit'));

        return redirect()->route('app.submissions.index')->with('success', 'Link konten berhasil didaftarkan! Tim kami akan segera meninjau pengajuanmu.');
    }
}
