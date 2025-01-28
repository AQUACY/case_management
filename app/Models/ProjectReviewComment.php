<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectReviewComment extends Model
{
    protected $fillable = [
        'project_id',
        'comment',
        'status',
        'commented_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
