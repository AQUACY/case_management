<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRecord extends Model
{
    protected $fillable = [
        'case_id', 'petition_types', 'petition_category', 'filing_plan_eb1',
        'filing_plan_eb2', 'previous_visa_filing', 'i485_filing_plan',
        'last_name', 'first_name', 'middle_name', 'gender', 'title',
        'date_of_birth', 'country_of_birth', 'country_of_citizenship',
        'in_us', 'visa_status', 'ds2019_expiration', 'visa_expiration',
        'passport_expiration', 'no_passport_applied', 'admit_until_date',
        'applying_new_visa', 'visa_type', 'latest_entry_date',
        'latest_visa_status', 'j_visa_status', 'communist_party_member',
        'employer_name', 'job_title', 'proposed_employment_field',
        'company_name', 'job_description', 'full_time', 'permanent_position',
        'worksite_city', 'worksite_state', 'paper_publication_year',
        'asylum_applied', 'street_address', 'address_line_2', 'city',
        'state', 'zip_code', 'country', 'email', 'phone_number',
        'referral_source', 'social_media_source', 'has_dependents',
        'marital_status'
    ];

    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }
}
