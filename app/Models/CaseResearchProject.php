<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseResearchProject extends Model
{
    protected $fillable = [
        'case_id',
        'project_description',
        'order'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function researchSummary()
    {
        return $this->belongsTo(CaseResearchSummary::class, 'case_id', 'case_id');
    }
}
