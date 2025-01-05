<?php

namespace App\Mail;

use App\Models\CaseQuestionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $caseQuestionnaire;

    /**
     * Create a new message instance.
     */
    public function __construct(CaseQuestionnaire $caseQuestionnaire)
    {
        $this->caseQuestionnaire = $caseQuestionnaire;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Review Request for Case Questionnaire')
                    ->view('emails.review-request')
                    ->with([
                        'questionnaire' => $this->caseQuestionnaire
                    ]);
    }
}
