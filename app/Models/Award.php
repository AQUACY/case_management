<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'case_id',
        'award_name',
        'award_recipient',
        'awarding_institution',
        'award_criteria',
        'award_significance',
        'number_of_recipients',
        'competitor_limitations'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
