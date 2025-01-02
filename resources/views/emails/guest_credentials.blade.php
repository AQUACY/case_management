<!DOCTYPE html>
<html>
<head>
    <title>Guest Account Credentials</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>Your account has been created. Below are your login details:</p>

    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Password: {{ $password }}</li>
    </ul>

    <p>Please log in to the platform to get started.</p>
</body>
</html>
