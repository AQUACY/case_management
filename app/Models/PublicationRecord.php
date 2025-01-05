<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'peer_reviewed_journal_articles',
        'notes_peer_reviewed_journal',
        'peer_reviewed_conference_articles',
        'notes_peer_reviewed_conference',
        'conference_abstracts',
        'notes_conference_abstracts',
        'pre_prints',
        'notes_pre_prints',
        'book_chapters',
        'notes_book_chapters',
        'books',
        'notes_books',
        'technical_reports',
        'notes_technical_reports',
        'granted_patents',
        'notes_granted_patents',
        'others',
        'in_preparation_manuscripts',
        'research_topic',
        'significance',
        'funding_sources',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}

