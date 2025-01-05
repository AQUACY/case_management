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
        'petition_type',
        'petitioner',
        'family_name',
        'given_name',
        'full_middle_name',
        'native_alphabet',
        'street_number_name',
        'type',
        'type_detail',
        'city_town',
        'state',
        'zip_code',
        'province',
        'country',
        'dob',
        'birth_city_town_village',
        'birth_state_province',
        'birth_country',
        'citizenship_country',
        'dual_citizenship',
        'secondary_country',
        'ssn',
        'alien_registration_number',
        'arrival_date',
        'admission_record_number',
        'passport_number',
        'passport_country',
        'passport_expiration_date',
        'admission_class',
        'admit_until_date',
        'occupation',
        'annual_income',
        'job_title',
        'soc_code',
        'nontechnical_job_description',
        'full_time_position',
        'hours_per_week',
        'permanent_position',
        'new_position',
        'wages',
        'wages_per',
        'worksite_type',
        'worksite_street_number_name',
        'work_building_type',
        'work_type_detail',
        'work_city_town',
        'work_state',
        'work_county_township',
        'work_zip_code',
        'apply_visa_abroad',
        'processing_country',
        'processing_city',
        'file_adjustment_status',
        'current_residence_country',
        'foreign_address_street_number_name',
        'foreign_address_type',
        'foreign_type_detail',
        'foreign_city_town',
        'foreign_state_province',
        'foreign_postal_code',
        'foreign_country',
        'simultaneous_petitions',
        'simultaneous_petitions_details',
        'prior_petition',
        'prior_petition_details',
        'removal_proceedings',
        'removal_proceedings_details',
        'daytime_telephone',
        'mobile_telephone',
        'email_address',
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
