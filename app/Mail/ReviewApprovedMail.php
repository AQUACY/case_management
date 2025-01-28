<?php

namespace App\Mail;

use App\Models\ProposedEmploymentEndavorRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->subject('Your Review Request Has Been Approved')
                    ->view('emails.reviewApproved')
                    ->with('emailData', $this->emailData);
    }
}
