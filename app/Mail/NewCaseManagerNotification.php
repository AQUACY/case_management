<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCaseManagerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $case;
    public $user;
    public $caseManager;

    /**
     * Create a new message instance.
     */
    public function __construct($case)
    {
        $this->case = $case;
        $this->user = $case->user;
        $this->caseManager = $case->caseManager;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Case Assigned - ' . $this->case->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cases.new-case-manager',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->markdown('emails.cases.new-case-manager')
                    ->subject('New Case Assigned - ' . $this->case->order_number);
    }
}
