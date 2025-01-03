<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommender extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'name',
        'dependent_or_independent',
        'title',
        'institution',
        'country',
        'faculty_biography_link',
        'google_scholar_link',
        'relationship',
        'projects_discussed',
        'cited_project',
        'cited_project_details',
        'status',
    ];

    // Relationship with Case
    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}

