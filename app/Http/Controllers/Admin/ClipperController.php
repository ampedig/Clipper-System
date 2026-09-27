<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ClipperController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan role clipper.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = User::with('withdrawChannel')
            ->withCount('clipSubmissions')
            ->where('role', 'clipper');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $clippers = $query->latest()->paginate($perPage)->withQueryString();

        return view('dashboard.clippers.index', compact('clippers'));
    }

    /**
     * Menampilkan formulir penambahan data clipper baru.
     */
    public function create(): View
    {
        return view('dashboard.clippers.create');
    }

    /**
     * Menyimpan data clipper baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $validated['role'] = 'clipper';
        $validated['is_active'] = $request->has('is_active');
        $validated['balance'] = 0;

        User::create($validated);

        return redirect()->route('admin.clippers.index')->with('success', 'Akun clipper berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail informasi akun, saldo, dan riwayat clipper.
     */
    public function show(User $clipper): View|RedirectResponse
    {
        $clipper->load([
            'withdrawChannel',
            'walletTransactions' => fn ($q) => $q->latest()->take(5),
            'clipSubmissions' => fn ($q) => $q->with('clipCampaign')->latest()->take(5),
        ]);

        $approvedSubmissionsCount = $clipper->clipSubmissions()
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->count();

        $totalSubmissionsCount = $clipper->clipSubmissions()->count();

        $approvalRate = $totalSubmissionsCount > 0
            ? (int) round(($approvedSubmissionsCount / $totalSubmissionsCount) * 100)
            : 0;

        return view('dashboard.clippers.show', compact(
            'clipper',
            'approvedSubmissionsCount',
            'totalSubmissionsCount',
            'approvalRate'
        ));
    }

    /**
     * Menampilkan formulir edit data clipper.
     */
    public function edit(User $clipper): View|RedirectResponse
    {
        if ($clipper->role !== 'clipper') {
            return redirect()->route('admin.clippers.index')->with('error', 'Data pengguna bukan merupakan clipper.');
        }

        return view('dashboard.clippers.edit', compact('clipper'));
    }

    /**
     * Memperbarui data akun clipper.
     */
    public function update(Request $request, User $clipper): RedirectResponse
    {
        if ($clipper->role !== 'clipper') {
            return redirect()->route('admin.clippers.index')->with('error', 'Data pengguna bukan merupakan clipper.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$clipper->id],
            'role' => ['required', 'string', 'in:clipper,admin'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $clipper->name = $validated['name'];
        $clipper->whatsapp = $validated['whatsapp'];
        $clipper->email = $validated['email'];
        $clipper->role = $validated['role'];

        if (! empty($validated['password'])) {
            $clipper->password = $validated['password'];
        }

        $clipper->is_active = $request->has('is_active');
        $clipper->save();

        return redirect()->route('admin.clippers.index')->with('success', 'Data clipper berhasil diperbarui.');
    }

    /**
     * Menghapus akun clipper dari sistem.
     */
    public function destroy(User $clipper): RedirectResponse
    {
        if ($clipper->role !== 'clipper') {
            return redirect()->route('admin.clippers.index')->with('error', 'Data pengguna bukan merupakan clipper.');
        }

        $clipper->delete();

        return redirect()->route('admin.clippers.index')->with('success', 'Data clipper berhasil dihapus.');
    }

    /**
     * Mengubah status aktif akun clipper secara asinkron.
     */
    public function toggleStatus(Request $request, User $clipper): JsonResponse
    {
        if ($clipper->role !== 'clipper') {
            return response()->json([
                'success' => false,
                'message' => 'Data pengguna bukan merupakan clipper.',
            ], 403);
        }

        $clipper->is_active = $request->boolean('is_active');
        $clipper->save();

        return response()->json([
            'success' => true,
            'message' => 'Status clipper berhasil '.($clipper->is_active ? 'diaktifkan.' : 'dinonaktifkan.'),
        ]);
    }
}
