@component('mail::message')
<img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; margin-bottom: 20px;">

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

Best regards,<br/>
Admin {{ config('app.name') }}
@endcomponent
