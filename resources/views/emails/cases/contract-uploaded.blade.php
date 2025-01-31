@component('mail::message')
# Case Contract Uploaded

Dear {{ $isUser ? $case->user->name : $case->caseManager->name }},

The contract for case {{ $case->order_number }} has been uploaded.

The contract file is attached to this email for your records.

Best regards,
{{ config('app.name') }}
@endcomponent 