@component('mail::message')
<img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; margin-bottom: 20px;">
# Case Contract Uploaded

Dear {{ $isUser ? $case->user->name : $case->caseManager->name }},

The contract for your case <span class="case-number text-bold">( {{ $case->order_number }} )</span> has been uploaded.

We have also attached it to this email for your records.

Best regards,
Admin {{ config('app.name') }}
@endcomponent
