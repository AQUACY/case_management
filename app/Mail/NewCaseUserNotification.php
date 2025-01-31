<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCaseUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $case;
    public $caseManager;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($case, $caseManager, $loginUrl)
    {
        $this->case = $case;
        $this->caseManager = $caseManager;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Case User Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
        return $this->markdown('emails.cases.new-case-user')
                    ->subject('New Case Created - ' . $this->case->order_number);
    }
}
