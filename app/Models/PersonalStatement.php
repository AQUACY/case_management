<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalStatement extends Model
{
    protected $fillable = [
        'case_id',
        'personal_name',
        'proposed_endeavor',
        'background_information',
        'future_intentions',
        'future_research'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
