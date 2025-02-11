<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraordinaryAbility extends Model
{
    protected $fillable = [
        'case_id',
        'has_awards',
        'has_memberships',
        'has_media_coverage',
        'has_speaking_engagements',
        'has_leadership_roles'
    ];

    protected $casts = [
        'has_awards' => 'boolean',
        'has_memberships' => 'boolean',
        'has_media_coverage' => 'boolean',
        'has_speaking_engagements' => 'boolean',
        'has_leadership_roles' => 'boolean'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'case_id', 'case_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'case_id', 'case_id');
    }

    public function mediaCoverages()
    {
        return $this->hasMany(MediaCoverage::class, 'case_id', 'case_id');
    }

    public function speakingEngagements()
    {
        return $this->hasMany(SpeakingEngagement::class, 'case_id', 'case_id');
    }

    public function leadershipRoles()
    {
        return $this->hasMany(LeadershipRole::class, 'case_id', 'case_id');
    }
}
