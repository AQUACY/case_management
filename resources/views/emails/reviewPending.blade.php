<!DOCTYPE html>
<html>
<head>
    <title>Review Request Pending</title>
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
    <h1>Your Review Request is Pending</h1>
    <p>Dear {{ $emailData['client_name'] }},</p>
    <p>Your review request for case <span class="case-number">{{ $emailData['order_number'] }}</span> has been received and is currently pending review. Our case management team will carefully evaluate your submission soon.</p>
    <p>You can track the status of your review request by logging into your account. We'll also notify you via email once the review is complete.</p>
    <p>If you have any urgent questions or concerns while waiting, please don't hesitate to reach out to our support team.</p>
    <div class="footer">
        <p>Best regards,<br>
        The Support Team</p>
    </div>
</body>
</html>
