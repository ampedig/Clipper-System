<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipSubmission;
use App\Services\ClipViewsSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClipSubmissionController extends Controller
{
    /**
     * Display a listing of the clip submissions with summary statistics and filters.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $status = $request->input('status', 'all');

        $query = ClipSubmission::with(['user', 'clipCampaign'])->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                // 1. Nama & Email User / Clipper
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                // 2. Title Clip Campaign
                    ->orWhereHas('clipCampaign', function ($campaignQuery) use ($search) {
                        $campaignQuery->where('title', 'like', "%{$search}%");
                    })
                // 3. Submitted URL / Video ID
                    ->orWhere('submitted_url', 'like', "%{$search}%")
                    ->orWhere('video_id', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => ClipSubmission::count(),
            'pending' => ClipSubmission::where('status', 'pending')->count(),
            'active' => ClipSubmission::whereIn('status', ['active', 'approved'])->count(),
            'total_commission' => (int) ClipSubmission::sum('total_earned'),
        ];

        return view('dashboard.clip_submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Update the status of the specified submission.
     */
    public function updateStatus(Request $request, ClipSubmission $clipSubmission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,active,completed',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $clipSubmission->load('clipCampaign');
        $newStatus = $validated['status'];
        $oldStatus = $clipSubmission->status;

        // Validasi clipper_limit jika status berubah menjadi active/approved
        if (in_array($newStatus, ['approved', 'active'], true) && ! in_array($oldStatus, ['approved', 'active', 'completed'], true)) {
            $campaign = $clipSubmission->clipCampaign;

            if ($campaign && $campaign->clipper_limit > 0) {
                $activeCount = ClipSubmission::where('clip_campaign_id', $campaign->id)
                    ->whereIn('status', ['active', 'approved', 'completed'])
                    ->count();

                if ($activeCount >= $campaign->clipper_limit) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Batas maksimal clipper (clipper_limit) untuk campaign ini sudah penuh.',
                        ], 422);
                    }

                    return back()->with('error', 'Batas maksimal clipper untuk campaign ini sudah penuh.');
                }
            }
        }

        $clipSubmission->status = $newStatus;

        if (in_array($validated['status'], ['approved', 'active'], true)) {
            $clipSubmission->approved_at = now();
            $clipSubmission->rejected_at = null;
            $clipSubmission->rejection_reason = null;
        } elseif ($validated['status'] === 'rejected') {
            $clipSubmission->rejected_at = now();
            $clipSubmission->rejection_reason = $validated['rejection_reason'] ?? null;
        }

        $clipSubmission->save();
        $clipSubmission->load(['user', 'clipCampaign']);

        // --- Kirim Notifikasi Telegram ke Topic Activity ---
        $statusLabel = strtoupper($clipSubmission->status);
        $header = '⏳ <b>[ADMIN] STATUS KLIP DIUBAH</b> ⏳';

        if (in_array($clipSubmission->status, ['approved', 'active', 'completed'])) {
            $header = '✅ <b>[ADMIN] KLIP DISETUJUI</b> ✅';
            $statusLabel = 'APPROVED';
        } elseif ($clipSubmission->status === 'rejected') {
            $header = '❌ <b>[ADMIN] KLIP DITOLAK</b> ❌';
            $statusLabel = 'REJECTED';
        }

        $pesan = "{$header}\n"
               ."━━━━━━━━━━━━━━━━━━━━\n"
               ."👤 <b>User:</b> {$clipSubmission->user->name}\n"
               ."🏷 <b>Campaign:</b> {$clipSubmission->clipCampaign->title}\n"
               ."🔗 <b>Link TikTok:</b> <a href=\"{$clipSubmission->submitted_url}\">Tonton Video</a>\n"
               ."🎯 <b>Status Baru:</b> {$statusLabel}";

        if ($clipSubmission->status === 'rejected' && $clipSubmission->rejection_reason) {
            $pesan .= "\n📝 <b>Alasan:</b> {$clipSubmission->rejection_reason}";
        }

        SendTelegramMessageJob::dispatch($pesan, config('telegram.topics.activity'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status submission berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Status submission berhasil diperbarui.');
    }

    /**
     * Mengecek views terbaru dari TikTok dan menghitung komisi untuk submission aktif.
     */
    public function checkViews(ClipSubmission $clipSubmission, ClipViewsSyncService $syncService): JsonResponse
    {
        $result = $syncService->sync($clipSubmission);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'current_views' => $result['current_views'],
                'current_views_formatted' => number_format($result['current_views'], 0, ',', '.'),
                'credited_views' => $result['credited_views'],
                'credited_views_formatted' => number_format($result['credited_views'], 0, ',', '.'),
                'delta_views' => $result['delta_views'],
                'delta_views_formatted' => number_format($result['delta_views'], 0, ',', '.'),
                'earned_now' => $result['earned_now'],
                'earned_now_formatted' => 'Rp '.number_format($result['earned_now'], 0, ',', '.'),
                'total_earned' => $result['total_earned'],
                'total_earned_formatted' => 'Rp '.number_format($result['total_earned'], 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Toggle status is_reference for inspiration/educational video.
     */
    public function toggleReference(Request $request, ClipSubmission $clipSubmission): JsonResponse
    {
        $clipSubmission->is_reference = ! $clipSubmission->is_reference;
        $clipSubmission->save();

        $message = $clipSubmission->is_reference
            ? 'Video berhasil dijadikan sebagai referensi inspirasi.'
            : 'Video dihapus dari daftar referensi inspirasi.';

        return response()->json([
            'success' => true,
            'is_reference' => (bool) $clipSubmission->is_reference,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified submission from storage.
     */
    public function destroy(ClipSubmission $clipSubmission)
    {
        $clipSubmission->delete();

        return back()->with('success', 'Submission berhasil dihapus.');
    }
}
