<?php

namespace App\Mail;

use App\Models\TrialCandidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TrialCandidate $candidate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Keputusan Seleksi Trial - Ruler Of Kings',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-rejected',
            with: [
                'candidate' => $this->candidate,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
