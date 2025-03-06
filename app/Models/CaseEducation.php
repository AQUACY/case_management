<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseEducation extends Model
{
    protected $table = 'case_education';

    protected $fillable = [
        'case_id',
        'university_name',
        'completion_year',
        'location',
        'degree_type',
        'degree_majors',
        'degree_minors',
        'start_year'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
