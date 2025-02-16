<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseWorkExperience extends Model
{
    protected $fillable = [
        'case_id',
        'employer_name',
        'address_1',
        'address_2',
        'city',
        'state',
        'country',
        'postal_code',
        'business_type',
        'job_title',
        'start_date',
        'end_date',
        'hours_worked',
        'job_details'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
