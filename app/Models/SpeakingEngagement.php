<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakingEngagement extends Model
{
    protected $fillable = [
        'case_id',
        'conference_name',
        'engagement_date',
        'details'
    ];

    protected $casts = [
        'engagement_date' => 'date'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
} 
