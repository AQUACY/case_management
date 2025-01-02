<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'bill',
        'case_manager_id',
        'description',
    ];

    // Relationship with User (Case Manager)
    public function caseManager()
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }
}
