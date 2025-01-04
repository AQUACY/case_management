<!DOCTYPE html>
<html>
<head>
    <title>New Message Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
        .content { margin: 20px; }
        .footer { margin: 20px; text-align: center; font-size: 0.9em; color: #888; }
        .button { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Message Notification</h1>
    </div>
    <div class="content">
        <p>Dear {{ $messageData->user->name }},</p>
        @if($messageData->sender_type === 'case_manager')
            <p>You have received a new message from your case manager.</p>
        @else
            <p>You have received a new message from the user.</p>
        @endif
        <p><strong>Subject:</strong> {{ $messageData->subject }}</p>
        <p><strong>Message:</strong></p>
        <blockquote>{{ $messageData->message }}</blockquote>
        <a href="{{ $platformUrl }}/messages/{{ $messageData->id }}" class="button">View Message</a>

    </div>
    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
    </div>
</body>
</html>
