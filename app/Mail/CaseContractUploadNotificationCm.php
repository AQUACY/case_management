<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CaseContractUploadNotificationCm extends Mailable
{
    use Queueable, SerializesModels;

    public $case;
    public $isUser;

    /**
     * Create a new message instance.
     */
    public function __construct($case, $isUser)
    {
        $this->case = $case;
        $this->isUser = $isUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Case Contract Uploaded - ' . $this->case->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cases.contract-uploaded-cm',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->case->contract_file) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath(
                    storage_path('app/public/' . $this->case->contract_file)
                )->withMime('application/pdf')
            ];
        }
        return [];
    }

    public function build()
    {
        $mail = $this->markdown('emails.cases.contract-uploaded-cm')
                    ->subject('Case Contract Uploaded - ' . $this->case->order_number);

        if ($this->case->contract_file) {
            $mail->attach(
                storage_path('app/public/' . $this->case->contract_file),
                ['mime' => 'application/pdf']
            );
        }

        return $mail;
    }
}
