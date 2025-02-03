<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'service_type',
        'receipt_number',
        'date_of_filing',
        'date_of_decision',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }
}