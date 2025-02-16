<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseResearchSummary extends Model
{
    protected $fillable = [
        'case_id',
        'field_description',
        'expertise_description',
        'work_impact'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function projects()
    {
        return $this->hasMany(CaseResearchProject::class, 'case_id', 'case_id')
            ->orderBy('order', 'asc');
    }
}
