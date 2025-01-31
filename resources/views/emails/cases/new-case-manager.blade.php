@component('mail::message')
# New Case Assigned

Dear {{ $caseManager->name }},

A new case has been assigned to you with the following details:

**Case Number:** {{ $case->order_number }}

**Client Details:**
- Name: {{ $user->name }}
- Email: {{ $user->email }}

**Bill Amount:** ${{ number_format($case->bill, 2) }}

**Case Description:**
{{ $case->description }}

Best regards,
{{ config('app.name') }}
@endcomponent 