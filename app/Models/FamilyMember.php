<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_questionnaire_id',
        'family_name_last_name',
        'given_name_first_name',
        'middle_name',
        'dob',
        'birth_country',
        'relationship',
        'adjustment_status',
        'visa_abroad',
    ];

    public function caseQuestionnaire()
    {
        return $this->belongsTo(CaseQuestionnaire::class);
    }
}
