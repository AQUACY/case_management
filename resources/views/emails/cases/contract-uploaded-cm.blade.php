@component('mail::message')
<img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; margin-bottom: 20px;">
# Case Contract Uploaded

Dear {{ $isUser ? $case->user->name : $case->caseManager->name }},

The contract for the case <span class="case-number text-bold">( {{ $case->order_number }} )</span> has been uploaded.

Attached is the copy of the contract for your records.

Best regards,<br/>
Admin {{ config('app.name') }}
@endcomponent
