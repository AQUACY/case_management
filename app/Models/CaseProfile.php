<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'academic_degree',
        'citation_database_link',
        'current_us_position',
        'proposed_employment_us',
        'same_or_similar_field',
        'alternative_field_1',
        'alternative_field_2',
        'conduct_research',
        'ongoing_project_1',
        'ongoing_project_2',
        'number_of_papers_reviewed',
        'editor_role',
    ];

    // Relationship with Cases
    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
