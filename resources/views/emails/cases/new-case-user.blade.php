@component('mail::message')
# New Case Created

Dear {{ $case->user->name }},

A new case has been created for you with the following details:

**Case Number:** {{ $case->order_number }}

**Case Manager Details:**
- Name: {{ $caseManager->name }}
- Email: {{ $caseManager->email }}

**Bill Amount:** ${{ number_format($case->bill, 2) }}

You can access your case by logging in to our platform:

@component('mail::button', ['url' => $loginUrl])
Login to Platform
@endcomponent

Best regards,
{{ config('app.name') }}
@endcomponent 