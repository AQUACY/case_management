<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundInformation extends Model
{
    use HasFactory;

    protected $table = 'background_information';

    protected $fillable = [
        'case_id',
        'main_academic_field',
        'specialization',
        'unique_skillset',
        'filing_niw',
        'critical_discussion_1',
        'critical_discussion_2',
        'critical_discussion_3',
        'key_issue_1',
        'key_issue_2',
        'key_issue_2_discussion_field_1',
        'key_issue_2_discussion_field_2',
        'key_issue_3',
        'key_issue_3_discussion_field_1',
        'key_issue_3_discussion_field_2',
        'benefit_us_issue_1',
        'benefit_us_issue_1_discussion_field_1',
        'benefit_us_issue_1_discussion_field_2',
        'benefit_us_issue_2',
        'benefit_us_issue_2_discussion_field_1',
        'benefit_us_issue_2_discussion_field_2',
        'benefit_us_issue_3',
        'benefit_us_issue_3_discussion_field_1',
        'benefit_us_issue_3_discussion_field_2',
        'status'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }
}
