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
        'user_id',  // Add this field to the fillable array
        'description',
    ];

    // Relationship with Case Manager (User)
    public function caseManager()
    {
        return $this->belongsTo(User::class, 'case_manager_id');
    }

    // Relationship with the User (Client) the case is created for
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

