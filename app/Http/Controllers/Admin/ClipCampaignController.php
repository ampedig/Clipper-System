<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Http\Controllers\Controller;
use App\Models\ClipCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class ClipCampaignController extends Controller
{
    /**
     * Menampilkan daftar kampanye clip dengan paginasi dan pencarian dinamis.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = ClipCampaign::with('creator')
            ->withCount([
                'clipSubmissions as approved_submissions_count' => function ($query) {
                    $query->whereIn('status', ['approved', 'active', 'completed']);
                },
                'clipSubmissions as total_submissions_count',
            ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $campaigns = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('dashboard.clip_campaigns.index', compact('campaigns', 'perPage'));
    }

    /**
     * Menampilkan form untuk membuat kampanye clip baru.
     */
    public function create(): View
    {
        return view('dashboard.clip_campaigns.create');
    }

    /**
     * Menyimpan kampanye clip baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalisasi format mata uang Rupiah ke integer murni
        if ($request->has('commission_amount')) {
            $request->merge([
                'commission_amount' => (int) preg_replace('/\D/', '', (string) $request->commission_amount),
            ]);
        }

        $validated = $request->validate([
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'title' => ['required', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'brief' => ['required', 'string'],
            'commission_amount' => ['required', 'integer', 'min:0'],
            'view_threshold' => ['required', 'integer', 'min:1'],
            'view_max' => ['required', 'integer', 'min:1'],
            'clipper_limit' => ['required', 'integer', 'min:1'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after_or_equal:start_at'],
            'status' => ['required', new Enum(CampaignStatus::class)],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails/campaigns', 'public');
        }

        $validated['created_by'] = auth()->id();

        ClipCampaign::create($validated);

        return redirect()->route('admin.clip-campaigns.index')->with('success', 'Campaign clipper berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari kampanye clip tertentu.
     */
    public function show(ClipCampaign $clip_campaign): View
    {
        $clip_campaign->load('creator');

        $recent_submissions = $clip_campaign->clipSubmissions()
            ->with('user')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('dashboard.clip_campaigns.show', compact('clip_campaign', 'recent_submissions'));
    }

    /**
     * Menampilkan form untuk mengedit kampanye clip.
     */
    public function edit(ClipCampaign $clip_campaign): View
    {
        return view('dashboard.clip_campaigns.edit', compact('clip_campaign'));
    }

    /**
     * Memperbarui data kampanye clip di database.
     */
    public function update(Request $request, ClipCampaign $clip_campaign): RedirectResponse
    {
        // Normalisasi format mata uang Rupiah ke integer murni
        if ($request->has('commission_amount')) {
            $request->merge([
                'commission_amount' => (int) preg_replace('/\D/', '', (string) $request->commission_amount),
            ]);
        }

        $validated = $request->validate([
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'title' => ['required', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'brief' => ['required', 'string'],
            'commission_amount' => ['required', 'integer', 'min:0'],
            'view_threshold' => ['required', 'integer', 'min:1'],
            'view_max' => ['required', 'integer', 'min:1'],
            'clipper_limit' => ['required', 'integer', 'min:1'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after_or_equal:start_at'],
            'status' => ['required', new Enum(CampaignStatus::class)],
        ]);

        if ($request->boolean('remove_thumbnail')) {
            // Bersihkan file thumbnail lama dari disk jika diminta
            if ($clip_campaign->thumbnail && Storage::disk('public')->exists($clip_campaign->thumbnail)) {
                Storage::disk('public')->delete($clip_campaign->thumbnail);
            }
            $validated['thumbnail'] = null;
        } elseif ($request->hasFile('thumbnail')) {
            // Hapus file thumbnail lama sebelum menyimpan file yang baru
            if ($clip_campaign->thumbnail && Storage::disk('public')->exists($clip_campaign->thumbnail)) {
                Storage::disk('public')->delete($clip_campaign->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails/campaigns', 'public');
        }

        $clip_campaign->update($validated);

        return redirect()->route('admin.clip-campaigns.index')->with('success', 'Campaign clipper berhasil diperbarui.');
    }

    /**
     * Menghapus kampanye clip dari database (soft delete) beserta thumbnail fisiknya.
     */
    public function destroy(ClipCampaign $clip_campaign): RedirectResponse
    {
        if ($clip_campaign->thumbnail && Storage::disk('public')->exists($clip_campaign->thumbnail)) {
            Storage::disk('public')->delete($clip_campaign->thumbnail);
        }

        $clip_campaign->delete();

        return redirect()->route('admin.clip-campaigns.index')->with('success', 'Kampanye clip berhasil dihapus.');
    }

    /**
     * Menampilkan daftar submisi khusus untuk suatu campaign.
     */
    public function submissions(Request $request, ClipCampaign $clip_campaign): View
    {
        $perPage = (int) $request->input('per_page', 10);
        $status = $request->input('status', 'all');

        $query = $clip_campaign->clipSubmissions()->with('user')->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $submissions = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => $clip_campaign->clipSubmissions()->count(),
            'pending' => $clip_campaign->clipSubmissions()->where('status', 'pending')->count(),
            'approved' => $clip_campaign->clipSubmissions()->whereIn('status', ['active', 'completed', 'approved'])->count(),
            'total_commission' => (int) $clip_campaign->clipSubmissions()->sum('total_earned'),
        ];

        return view('dashboard.clip_campaigns.submissions', compact('clip_campaign', 'submissions', 'stats', 'perPage', 'status'));
    }
}
