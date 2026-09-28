<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramMessageJob;
use App\Models\ClipSubmission;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-recap')]
#[Description('Kirim rekap harian AZCLIP ke Telegram')]
class SendDailyRecapCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // 1. Member Baru
        $newMembers = User::where('role', 'clipper')
            ->whereDate('created_at', $today)
            ->count();

        // 2. Klip Baru Disubmit
        $newClips = ClipSubmission::whereDate('created_at', $today)->count();

        // 3. Klip Menunggu Review
        $pendingClips = ClipSubmission::where('status', 'pending')->count();

        // 4. Request Penarikan Baru
        $newWithdrawals = Withdrawal::whereDate('created_at', $today)->count();

        // 5. Total Nominal Penarikan Di-ACC
        $approvedWithdrawalsAmount = Withdrawal::whereDate('updated_at', $today)
            ->where('status', 'completed')
            ->sum('amount');

        // 6. Total Komisi Diberikan
        $commissionGiven = WalletTransaction::whereDate('created_at', $today)
            ->where('type', 'credit')
            ->sum('amount');

        $dateStr = $today->translatedFormat('l, d F Y');

        $text = "📊 <b>DAILY RECAP AZCLIP</b>\n";
        $text .= "🗓 <i>{$dateStr}</i>\n\n";

        $text .= "<b>👥 Aktivitas Member</b>\n";
        $text .= "▪️ Member Baru: <b>{$newMembers}</b> orang\n\n";

        $text .= "<b>🔗 Aktivitas Klip</b>\n";
        $text .= "▪️ Klip Disubmit Hari Ini: <b>{$newClips}</b> klip\n";
        $text .= "▪️ Klip Menunggu Review: <b>{$pendingClips}</b> klip\n\n";

        $text .= "<b>💰 Aktivitas Keuangan</b>\n";
        $text .= "▪️ Request Penarikan Baru: <b>{$newWithdrawals}</b> tiket\n";
        $text .= '▪️ Penarikan Di-ACC: <b>Rp '.number_format($approvedWithdrawalsAmount, 0, ',', '.')."</b>\n";
        $text .= '▪️ Komisi Diberikan: <b>Rp '.number_format($commissionGiven, 0, ',', '.')."</b>\n";

        $topicId = config('telegram.topics.activity');

        SendTelegramMessageJob::dispatch($text, $topicId);

        $this->info('Daily recap dispatched to Telegram successfully.');
    }
}
