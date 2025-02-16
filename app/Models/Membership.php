<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'case_id',
        'membership_level',
        'membership_requirements',
        'fee_and_subscription_details'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
} 
