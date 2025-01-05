<!DOCTYPE html>
<html>
<head>
    <title>Review Request</title>
</head>
<body>
    <h1>Review Request</h1>
    <p>A review has been requested for the following case questionnaire:</p>
    <ul>
        <li>Case ID: {{ $caseQuestionnaire->case_id }}</li>
        <li>Case Order: {{ $caseQuestionnaire->case->order_number }}</li>
        <li>Status: {{ $caseQuestionnaire->status }}</li>
        <!-- Add more details as necessary -->
    </ul>
</body>
</html>
