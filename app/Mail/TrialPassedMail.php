<?php

namespace App\Mail;

use App\Models\TrialCandidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialPassedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TrialCandidate $candidate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Anda Dinyatakan Lolos Seleksi Trial - Ruler Of Kings',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-passed',
            with: [
                'candidate' => $this->candidate,
                'nextStageUrl' => $this->candidate->next_stage_url,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
