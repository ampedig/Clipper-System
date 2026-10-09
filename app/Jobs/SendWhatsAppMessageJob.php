<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika pengiriman gagal.
     */
    public int $tries = 3;

    /**
     * Jeda waktu (dalam detik) sebelum mencoba kembali antrean yang gagal.
     */
    public int $backoff = 3;

    /**
     * Create a new job instance.
     *
     * @param  string  $to  Nomor WhatsApp tujuan
     * @param  string  $message  Konten teks pesan WhatsApp
     */
    public function __construct(
        public string $to,
        public string $message
    ) {}

    /**
     * Eksekusi background job pengiriman pesan WhatsApp.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $result = WhatsAppService::send($this->to, $this->message);

        if (! ($result['success'] ?? false)) {
            throw new Exception('Gagal mengirim pesan WhatsApp via queue: '.($result['message'] ?? 'Unknown gateway error'));
        }
    }
}
