<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'case_manager_id',
        'category_id',
        'subject',
        'message',
        'response',
        'status',
        'rating',
    ];

    public function category()
    {
        return $this->belongsTo(MessageCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caseManager()
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }
}
