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
    public static function getPaymentsByCaseId($caseId)
{
    return self::where('case_id', $caseId)->orderBy('created_at', 'desc')->get();
}
}
