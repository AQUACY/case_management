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
        'user_id',
        'description',
        'contract_file',
        'status',
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
    public function publicationRecord()
    {
        return $this->hasOne(PublicationRecord::class, 'case_id');
    }
}

