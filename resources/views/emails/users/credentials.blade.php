@component('mail::message')
# Welcome to {{ config('app.name') }}

Dear {{ $user->name }},

Your account has been created successfully. Below are your login credentials:

**Email:** {{ $user->email }}
**Password:** {{ $password }}

Please use these credentials to login to your account.

@component('mail::button', ['url' => $loginUrl])
Login Now
@endcomponent

For security reasons, we recommend changing your password after your first login.

Best regards,
{{ config('app.name') }}
@endcomponent
