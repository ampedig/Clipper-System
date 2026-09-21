<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawChannelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::patch('/administrators/{administrator}/status', [AdministratorController::class, 'toggleStatus'])->name('administrators.status');
    Route::resource('administrators', AdministratorController::class);
    
    Route::patch('/withdraw-channels/{withdraw_channel}/status', [WithdrawChannelController::class, 'toggleStatus'])->name('withdraw-channels.status');
    Route::resource('withdraw-channels', WithdrawChannelController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
