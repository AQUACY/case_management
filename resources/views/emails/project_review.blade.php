<!DOCTYPE html>
<html>
<head>
    <title>Project Contributions Review Update</title>
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
        .status {
            font-weight: bold;
            color: #2b6cb0;
        }
        .comment {
            margin-top: 20px;
            padding: 15px;
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
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
    <h1>SOC: Project Contributions Review Update</h1>

    <p>Dear {{ $emailData['client_name'] }},</p>

    <p>Your Project Contributions for case <span class="case-number">{{ $emailData['order_number'] }}</span> has been reviewed.</p>

    <p>Status: <span class="status">{{ ucfirst($emailData['status']) }}</span></p>

    @if($emailData['comment'])
        <div class="comment">
            <strong>Reviewer's Comment:</strong>
            <p>{{ $emailData['comment'] }}</p>
        </div>
    @endif

    <p>
        @if($emailData['status'] === 'approved')
            Your Project Contributions have been approved and finalized.
        @else
            Please review the comments and make the necessary updates to your Project Contributions.
        @endif
    </p>

    <div class="footer">
        <p>Best regards,<br>
        The Support Team</p>
    </div>
</body>
</html>
