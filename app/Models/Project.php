<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'title_of_project',
        'date_of_initiation_from',
        'date_of_initiation_to',
        'resulting_publications_1',
        'resulting_publications_2',
        'resulting_publications_3',
        'funding_sources_1',
        'funding_sources_2',
        'funding_sources_3',
        'summary_of_work',
        'niw_project_description',
        'alignment_with_section_i',
        'citation_1',
        'citation_2',
        'citation_3',
        'citation_4',
        'status',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }
}
