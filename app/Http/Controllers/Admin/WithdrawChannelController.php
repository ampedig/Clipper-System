<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawChannel;
use Illuminate\Http\Request;

class WithdrawChannelController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $withdrawChannels = WithdrawChannel::paginate($perPage);

        return view('dashboard.withdraw_channels.index', compact('withdrawChannels'));
    }

    public function create()
    {
        return view('dashboard.withdraw_channels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:withdraw_channels,code'],
            'fee' => ['required', 'integer', 'min:0'],
        ]);

        WithdrawChannel::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'fee' => $request->fee,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.withdraw-channels.index')->with('success', 'Metode penarikan berhasil ditambahkan.');
    }

    public function edit(WithdrawChannel $withdraw_channel)
    {
        return view('dashboard.withdraw_channels.edit', compact('withdraw_channel'));
    }

    public function update(Request $request, WithdrawChannel $withdraw_channel)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:withdraw_channels,code,'.$withdraw_channel->id],
            'fee' => ['required', 'integer', 'min:0'],
        ]);

        $withdraw_channel->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'fee' => $request->fee,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.withdraw-channels.index')->with('success', 'Metode penarikan berhasil diperbarui.');
    }

    public function destroy(WithdrawChannel $withdraw_channel)
    {
        $withdraw_channel->delete();

        return redirect()->route('admin.withdraw-channels.index')->with('success', 'Metode penarikan berhasil dihapus.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        $channel = WithdrawChannel::findOrFail($id);

        $channel->is_active = $request->boolean('is_active');
        $channel->save();

        return response()->json([
            'success' => true,
            'message' => 'Metode penarikan berhasil '.($channel->is_active ? 'diaktifkan.' : 'dinonaktifkan.'),
        ]);
    }
}
