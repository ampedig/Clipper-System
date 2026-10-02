<?php

use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\ClipCampaignController;
use App\Http\Controllers\Admin\ClipperController;
use App\Http\Controllers\Admin\ClipSubmissionController as AdminClipSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RejectionTemplateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\WithdrawChannelController;
use App\Http\Controllers\App\CampaignController;
use App\Http\Controllers\App\ClipSubmissionController as AppClipSubmissionController;
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\PasswordController as AppPasswordController;
use App\Http\Controllers\App\ProfileController as AppProfileController;
use App\Http\Controllers\App\WalletController as AppWalletController;
use App\Http\Controllers\App\WithdrawalController as AppWithdrawalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('app.home');
Route::view('/offline', 'offline')->name('offline');
Route::get('/campaign', [CampaignController::class, 'index'])->name('app.campaigns');
Route::get('/campaign/{campaign:slug}', [CampaignController::class, 'show'])->name('app.campaigns.show');
Route::post('/campaign/{campaign:slug}/submissions', [AppClipSubmissionController::class, 'store'])->middleware('auth')->name('app.campaigns.submissions.store');
Route::get('/klip', [AppClipSubmissionController::class, 'index'])->middleware('auth')->name('app.submissions.index');
Route::get('/klip/{clipSubmission}', [AppClipSubmissionController::class, 'show'])->middleware('auth')->name('app.submissions.show');
Route::get('/tarik-saldo', [AppWithdrawalController::class, 'create'])->middleware('auth')->name('app.withdrawals.create');
Route::post('/tarik-saldo', [AppWithdrawalController::class, 'store'])->middleware('auth')->name('app.withdrawals.store');
Route::get('/tarik-saldo/riwayat', [AppWithdrawalController::class, 'index'])->middleware('auth')->name('app.withdrawals.index');
Route::get('/saldo', [AppWalletController::class, 'index'])->middleware('auth')->name('app.wallet.index');
Route::get('/bantuan', [AppProfileController::class, 'help'])->name('app.help');
Route::get('/kebijakan-layanan', [AppProfileController::class, 'policy'])->name('app.policy');
Route::get('/akun', [AppProfileController::class, 'index'])->middleware('auth')->name('app.profile');
Route::get('/akun/edit', [AppProfileController::class, 'edit'])->middleware('auth')->name('app.profile.edit');
Route::put('/akun/edit', [AppProfileController::class, 'update'])->middleware('auth')->name('app.profile.update');
Route::get('/akun/kata-sandi', [AppPasswordController::class, 'edit'])->middleware('auth')->name('app.password.edit');
Route::put('/akun/kata-sandi', [AppPasswordController::class, 'update'])->middleware('auth')->name('app.password.update');
Route::get('/akun/rekening', [AppProfileController::class, 'rekening'])->middleware('auth')->name('app.rekening');
Route::put('/akun/rekening', [AppProfileController::class, 'updateRekening'])->middleware('auth')->name('app.rekening.update');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clip-campaigns/{clip_campaign}/submissions', [ClipCampaignController::class, 'submissions'])->name('clip-campaigns.submissions');
    Route::resource('clip-campaigns', ClipCampaignController::class);

    Route::patch('/clip-submissions/{clip_submission}/status', [AdminClipSubmissionController::class, 'updateStatus'])->name('clip-submissions.update-status');
    Route::patch('/clip-submissions/{clip_submission}/toggle-reference', [AdminClipSubmissionController::class, 'toggleReference'])->name('clip-submissions.toggle-reference');
    Route::post('/clip-submissions/{clip_submission}/check-views', [AdminClipSubmissionController::class, 'checkViews'])->name('clip-submissions.check-views');
    Route::resource('clip-submissions', AdminClipSubmissionController::class)->only(['index', 'destroy']);

    Route::patch('/clippers/{clipper}/status', [ClipperController::class, 'toggleStatus'])->name('clippers.status');
    Route::resource('clippers', ClipperController::class)->parameters(['clippers' => 'clipper']);

    Route::patch('/administrators/{administrator}/status', [AdministratorController::class, 'toggleStatus'])->name('administrators.status');
    Route::resource('administrators', AdministratorController::class);

    Route::patch('/withdraw-channels/{withdraw_channel}/status', [WithdrawChannelController::class, 'toggleStatus'])->name('withdraw-channels.status');
    Route::resource('withdraw-channels', WithdrawChannelController::class);

    Route::resource('rejection-templates', RejectionTemplateController::class)->except(['show']);

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/riwayat-saldo', [WalletTransactionController::class, 'index'])->name('riwayat-saldo.index');

    Route::patch('/withdrawals/{withdrawal}/status', [AdminWithdrawalController::class, 'updateStatus'])->name('withdrawals.update-status');
    Route::resource('withdrawals', AdminWithdrawalController::class)->only(['index', 'show']);
});
// Shared / Clipper Routes
Route::middleware('auth')->group(function () {
    // We will add Clipper dashboard/clips later. Profile is kept here.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
