<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseEducation extends Model
{
    protected $table = 'case_education';

    protected $fillable = [
        'case_id',
        'university_name',
        'completion_year'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
