<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewSoc extends Mailable
{
    use Queueable, SerializesModels;

    public $backgroundInformation;

    public function __construct($backgroundInformation)
    {
        $this->backgroundInformation = $backgroundInformation;
    }

    public function build()
    {
        return $this->subject('SOC Review Request')
                    ->view('emails.review_soc_blade')
                    ->with('record', $this->backgroundInformation);
    }
}
