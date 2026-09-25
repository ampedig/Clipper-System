<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipSubmission;
use Illuminate\Http\Request;

class ClipSubmissionController extends Controller
{
    /**
     * Display a listing of the clip submissions with summary statistics and filters.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $status = $request->input('status', 'all');

        $query = ClipSubmission::with(['user', 'clipCampaign'])->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
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

        $clipSubmission->status = $validated['status'];

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
        $header = "⏳ <b>[ADMIN] STATUS KLIP DIUBAH</b> ⏳";

        if (in_array($clipSubmission->status, ['approved', 'active', 'completed'])) {
            $header = "✅ <b>[ADMIN] KLIP DISETUJUI</b> ✅";
            $statusLabel = 'APPROVED';
        } elseif ($clipSubmission->status === 'rejected') {
            $header = "❌ <b>[ADMIN] KLIP DITOLAK</b> ❌";
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
     * Remove the specified submission from storage.
     */
    public function destroy(ClipSubmission $clipSubmission)
    {
        $clipSubmission->delete();

        return back()->with('success', 'Submission berhasil dihapus.');
    }
}
