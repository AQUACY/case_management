<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageNotificationMail extends Mailable
{
    use SerializesModels;

    public $messageData;
    public $platformUrl;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     * @param string $platformUrl
     */
    public function __construct(Message $message, $platformUrl)
    {
        $this->messageData = $message;
        $this->platformUrl = $platformUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = "{$this->messageData->category->name} - New Message Received from {$this->messageData->user->name}";

        return $this->subject($subject)
                    ->view('emails.message_notification');
    }
}