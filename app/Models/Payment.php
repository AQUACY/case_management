<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'case_id',
        'amount',
        'status',
        'payment_date',
        'note',
        'transaction_id'
    ];
}
