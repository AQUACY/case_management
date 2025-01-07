<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposedEmploymentEndavorRecord extends Model
{
    protected $fillable = [
        'case_id',
        'type',
        'selection',
        'proposed_endavor_field_1',
        'proposed_endavor_field_2',
        'proposed_endavor_field_3',
        'past_experience',
        'publication_plans',
        'status',
        'currently_student_us_niw',
        'currently_employed_academic_niw',
        'currently_employed_postdoctoral_niw',
        'received_promotion_notice_niw',
        'not_promoted_notice_niw',
        'currently_medical_resident_niw',
        'currently_visiting_scholar_niw',
        'currently_employed_us_business_niw',
        'currently_employed_outside_us_niw',
        'currently_unemployed_niw',
        'currently_student_outside_us_niw',
        'currently_intern_part_time_niw',
        'currently_employed_visa_expiring_niw',
        'currently_intern_student_niw',
        'currently_unemployed_with_offer_niw',
        'other_niw',
        'currently_student_us_eb1a',
        'currently_employed_academic_eb1a',
        'currently_employed_postdoctoral_eb1a',
        'received_promotion_notice_eb1a',
        'not_promoted_notice_eb1a',
        'currently_medical_resident_eb1a',
        'currently_visiting_scholar_eb1a',
        'currently_employed_us_business_eb1a',
        'currently_employed_outside_us_eb1a',
        'currently_unemployed_eb1a',
        'currently_student_outside_us_eb1a',
        'currently_intern_part_time_eb1a',
        'currently_employed_visa_expiring_eb1a',
        'currently_intern_student_eb1a',
        'currently_unemployed_with_offer_eb1a',
        'other_eb1a',
        'currently_student_us_eb1b',
        'currently_employed_academic_eb1b',
        'currently_employed_postdoctoral_eb1b',
        'received_promotion_notice_eb1b',
        'not_promoted_notice_eb1b',
        'currently_medical_resident_eb1b',
        'currently_visiting_scholar_eb1b',
        'currently_employed_us_business_eb1b',
        'currently_employed_outside_us_eb1b',
        'currently_unemployed_eb1b',
        'currently_student_outside_us_eb1b',
        'currently_intern_part_time_eb1b',
        'currently_employed_visa_expiring_eb1b',
        'currently_intern_student_eb1b',
        'currently_unemployed_with_offer_eb1b',
        'other_eb1b',
        'currently_student_us_o1',
        'currently_employed_academic_o1',
        'currently_employed_postdoctoral_o1',
        'received_promotion_notice_o1',
        'not_promoted_notice_o1',
        'currently_medical_resident_o1',
        'currently_visiting_scholar_o1',
        'currently_employed_us_business_o1',
        'currently_employed_outside_us_o1',
        'currently_unemployed_o1',
        'currently_student_outside_us_o1',
        'currently_intern_part_time_o1',
        'currently_employed_visa_expiring_o1',
        'currently_intern_student_o1',
        'currently_unemployed_with_offer_o1',
        'other_o1',
    ];

    protected $casts = [
        'type' => 'array', // Cast 'type' as an array
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function caseManager()
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }
}
