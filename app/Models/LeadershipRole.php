<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipRole extends Model
{
    protected $fillable = [
        'case_id',
        'role_position',
        'organization_name',
        'service_start_date',
        'service_end_date',
        'organization_prestige',
        'role_summary'
    ];

    protected $casts = [
        'service_start_date' => 'date',
        'service_end_date' => 'date'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
