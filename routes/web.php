<?php

use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\ClipCampaignController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WithdrawChannelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clip-campaigns', ClipCampaignController::class);

    Route::patch('/administrators/{administrator}/status', [AdministratorController::class, 'toggleStatus'])->name('administrators.status');
    Route::resource('administrators', AdministratorController::class);

    Route::patch('/withdraw-channels/{withdraw_channel}/status', [WithdrawChannelController::class, 'toggleStatus'])->name('withdraw-channels.status');
    Route::resource('withdraw-channels', WithdrawChannelController::class);
});

// Shared / Clipper Routes
Route::middleware('auth')->group(function () {
    // We will add Clipper dashboard/clips later. Profile is kept here.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
