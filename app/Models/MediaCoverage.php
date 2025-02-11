<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaCoverage extends Model
{
    protected $fillable = [
        'case_id',
        'media_name',
        'date_published',
        'author',
        'outlet_name',
        'circulation_count',
        'article_summary',
        'work_relevance'
    ];

    protected $casts = [
        'date_published' => 'date'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
