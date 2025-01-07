<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    protected $fillable = [
        'client_record_id', 'last_name', 'first_name', 'middle_name',
        'relation', 'country_of_birth', 'date_of_birth', 'passport_expiration',
        'no_passport_applied', 'gender', 'address', 'visa_processing_option',
        'processing_country'
    ];

    public function clientRecord()
    {
        return $this->belongsTo(ClientRecord::class);
    }
}
