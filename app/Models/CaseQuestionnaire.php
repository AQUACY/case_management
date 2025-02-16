<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseQuestionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'case_id',
        // basic information
        'petition_type',
        'petitioner',

        // personal information
        'family_name',
        'given_name',
        'full_middle_name',
        'native_alphabet',
        'dob',
        'city_town_village_of_birth',
        'state_of_birth',
        'country_of_birth',
        'nationality',
        'alien_registration_number',
        'ssn',

        // mail address
        'street_number_name',
        'type',
        'type_detail',
        'city_town',
        'state',
        'zip_code',
        'province',
        'country',
        'mobile_telephone',
        'email_address',

        // information about last arrival
        'last_arrival_date',
        'i_94_arrival_record_number',
        'expiration_date',
        'status_on_form_i_94',
        'passport_number',
        'travel_document_number',
        'country_of_issuance',
        'expiration_date_for_passport',

        // visa processing
        'alien_will_apply_for_visa_abroad',
        'visa_processing_city_town',
        'visa_processing_country',
        'alien_in_us',
        'if_now_in_the_us',
        'foreign_street_number_name',
        'foreign_address_type',
        'foreign_city_town',
        'foreign_state_province',
        'foreign_postal_code',
        'foreign_country',

        // employment information
        'current_employer_name',
        'job_title',
        'full_time_position',
        'permanent_position',
        'occupation',
        'annual_income',
        'soc_code',
        'nontechnical_job_description',
        'hours_per_week',
        'new_position',
        'wages',
        'wages_per',
        'worksite_type',
        'worksite_street_number_name',
        'work_building_type',
        'work_site_additional_details',
        'work_city_town',
        'work_state',
        'work_county_township',
        'work_zip_code'
    ];

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }
        public function case()
    {
        return $this->belongsTo(Cases::class);
    }

    // Case model
    public function caseManager()
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }
    }
