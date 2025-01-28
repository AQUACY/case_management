<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievementReviewComment extends Model
{
    protected $fillable = [
        'achievement_id',
        'comment',
        'status',
        'commented_by'
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
