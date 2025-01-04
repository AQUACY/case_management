<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_questionnaire_id',
        'family_name',
        'given_name',
        'full_middle_name',
        'relationship',
        'dob',
        'birth_country',
        'adjustment_status',
        'immigrant_visa',
    ];

    public function caseQuestionnaire()
    {
        return $this->belongsTo(CaseQuestionnaire::class);
    }
}
