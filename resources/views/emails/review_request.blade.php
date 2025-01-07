<!DOCTYPE html>
<html>
<head>
    <title>Review Request</title>
</head>
<body>
    <p>A review has been requested for the following case:</p>
    <ul>
        <li>Case ID: {{ $record->case_id }}</li>
        <li>Proposed Endeavor Type: {{ implode(', ', json_decode($record->type)) }}</li>
        <!-- Add more fields as necessary -->
    </ul>
</body>
</html>
