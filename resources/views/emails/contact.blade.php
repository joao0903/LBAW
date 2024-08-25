<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
    <h1>You received a message from {{ $name }} ({{ $email }}):</h1>
    <p>{{ $senderMessage }}</p>
</body>
</html>
