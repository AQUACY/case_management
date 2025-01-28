<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackgroundReviewComment extends Model
{
    protected $fillable = [
        'background_information_id',
        'comment',
        'status',
        'commented_by'
    ];

    public function backgroundInformation()
    {
        return $this->belongsTo(BackgroundInformation::class);
    }

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
