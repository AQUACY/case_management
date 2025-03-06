@component('mail::message')
<img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; margin-bottom: 20px;">

# Welcome to {{ config('app.name') }}

Dear {{ $user->name }},

Your account has been created successfully. Below are your login credentials:

**Email:** {{ $user->email }}<br/>
**Password:** {{ $password }}

Please use these credentials to login to your account.

@component('mail::button', ['url' => $loginUrl])
Login Now
@endcomponent

For security reasons, we recommend changing your password after your first login.

Best regards,<br/>
Admin {{ config('app.name') }}
@endcomponent
