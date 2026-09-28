<?php

namespace App\Mail;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inisialisasi instance mailable dengan data withdrawal.
     */
    public function __construct(public Withdrawal $withdrawal) {}

    /**
     * Mendefinisikan envelope email (subjek dan pengirim).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penarikan Dana Berhasil Diproses - AZCLIP',
        );
    }

    /**
     * Mendefinisikan template view untuk isi email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawals.approved',
        );
    }

    /**
     * Mendefinisikan lampiran email jika diperlukan.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
