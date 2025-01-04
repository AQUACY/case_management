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
        <p>Dear {{ $messageData->caseManager->name }},</p>
        <p>You have received a new message from <strong>{{ $messageData->user->name }}</strong> in the category "<strong>{{ $messageData->category->name }}</strong>".</p>
        <p><strong>Subject:</strong> {{ $messageData->subject }}</p>
        <p><strong>Message:</strong></p>
        <blockquote>{{ $messageData->message }}</blockquote>
        <p>Please log in to the platform to respond promptly.</p>
        <a href="{{ $platformUrl }}/messages/{{ $messageData->id }}" class="button">View Message</a>
    </div>
    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
    </div>
</body>
</html>
