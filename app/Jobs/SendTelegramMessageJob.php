<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika gagal.
     *
     * @var int
     */
    public $tries = 3;

    protected $text;

    protected $topicId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $text, ?int $topicId = null)
    {
        $this->text = $text;
        $this->topicId = $topicId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $success = TelegramService::sendMessage($this->text, $this->topicId);

        if (! $success) {
            throw new \Exception('Gagal mengirim pesan Telegram.');
        }
    }
}
