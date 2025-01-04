<!DOCTYPE html>
<html>
<head>
    <title>Message Response</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { background-color: #007BFF; color: white; padding: 10px; text-align: center; }
        .content { margin: 20px; }
        .footer { margin: 20px; text-align: center; font-size: 0.9em; color: #888; }
        .button { background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Message Response</h1>
    </div>
    <div class="content">
        <p>Dear {{ $messageData->user->name }},</p>
        <p><strong>{{ $messageData->caseManager->name }}</strong> has responded to your message in the category "<strong>{{ $messageData->category->name }}</strong>".</p>
        <p><strong>Subject:</strong> {{ $messageData->subject }}</p>
        <p><strong>Response:</strong></p>
        <blockquote>{{ $messageData->response }}</blockquote>
        <p>You can view and continue the conversation on the platform.</p>
        <a href="{{ $platformUrl }}/messages/{{ $messageData->id }}" class="button">View Response</a>
    </div>
    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
    </div>
</body>
</html>
