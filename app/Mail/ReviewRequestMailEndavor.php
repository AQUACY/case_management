<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewRequestMailEndavor extends Mailable
{
    use Queueable, SerializesModels;

    public $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function build()
    {
        return $this->subject('Review Request for Case (Proposed Employment/Proposed Endeavor Records)')
                    ->view('emails.review_request')
                    ->with('record', $this->record);
    }
}

