<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class AdministratorController extends Controller
{
    public function index()
    {
        $administrators = User::where('role', 'admin')->paginate(10);

        return view('dashboard.administrators.index', compact('administrators'));
    }

    public function create()
    {
        return view('dashboard.administrators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['role'] = 'admin';
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('admin.administrators.index')->with('success', 'Akun administrator berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $administrator = User::where('role', 'admin')->findOrFail($id);

        return view('dashboard.administrators.edit', compact('administrator'));
    }

    public function update(Request $request, string $id)
    {
        $administrator = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $administrator->name = $validated['name'];
        $administrator->whatsapp = $validated['whatsapp'];
        $administrator->email = $validated['email'];

        if (! empty($validated['password'])) {
            $administrator->password = $validated['password'];
        }

        $administrator->is_active = $request->has('is_active');
        $administrator->save();

        return redirect()->route('admin.administrators.index')->with('success', 'Akun administrator berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $administrator = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() == $id) {
            return redirect()->route('admin.administrators.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $administrator->delete();

        return redirect()->route('admin.administrators.index')->with('success', 'Akun administrator berhasil dihapus.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        $administrator = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() == $id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.',
            ], 403);
        }

        $administrator->is_active = $request->boolean('is_active');
        $administrator->save();

        return response()->json([
            'success' => true,
            'message' => 'Status administrator berhasil '.($administrator->is_active ? 'diaktifkan.' : 'dinonaktifkan.'),
        ]);
    }
}
