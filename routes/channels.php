<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Message;

Broadcast::channel('message.{messageId}', function ($user, $messageId) {
    $message = Message::find($messageId);

    if (!$message) {
        return false;
    }

    return $user->id === $message->user_id || $user->id === $message->case_manager_id;
});
