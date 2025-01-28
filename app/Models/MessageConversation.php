<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageConversation extends Model
{
    protected $fillable = [
        'message_id',
        'content',
        'sender_id',
        'sender_type',
        'is_read'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
