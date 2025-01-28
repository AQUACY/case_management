<!DOCTYPE html>
<html>
<head>
    <title>Review Request Pending</title>
</head>
<body>
    <h1>Your Review Request is Pending</h1>
    <p>Dear {{ $emailData->client_name }},</p>
    <p>Your review request for the case <strong>{{ $emailData->case_id }}</strong> is currently pending. The case manager will review it soon.</p>
    <p>Please log in to your account for further updates.</p>
    <p>If you have any questions, feel free to contact our support team.</p>
    <p>Best regards,</p>
    <p>The Support Team</p>
</body>
</html>
