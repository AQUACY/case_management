<!DOCTYPE html>
<html>
<head>
    <title>Review Request Approved</title>
</head>
<body>
    <h1>Your Review Request Has Been Approved</h1>
    <p>Dear {{ $record->client_name }},</p>
    <p>We are pleased to inform you that your review request for the case <strong>{{ $record->case_id }}</strong> has been approved.</p>
    <p>Please log in to your account to view the finalized details.</p>
    <p>Thank you for choosing our service.</p>
    <p>Best regards,</p>
    <p>The Support Team</p>
</body>
</html>
