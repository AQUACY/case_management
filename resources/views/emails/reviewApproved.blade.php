<!DOCTYPE html>
<html>
<head>
    <title>Review Request Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c5282;
            border-bottom: 2px solid #4299e1;
            padding-bottom: 10px;
        }
        .case-number {
            background-color: #ebf8ff;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            color: #2b6cb0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9em;
            color: #718096;
        }
    </style>
</head>
<body>
    <h1>Your Review Request Has Been Approved</h1>
    <p>Dear {{ $emailData->client_name }},</p>
    <p>We are pleased to inform you that your review request for the case <strong>{{ $emailData->order_number }}</strong> has been approved.</p>
    <p>Please log in to your account to view the finalized details.</p>
    <p>Thank you for choosing our service.</p>
    <p>Best regards,</p>
    <p>The Support Team</p>
</body>
</html>
