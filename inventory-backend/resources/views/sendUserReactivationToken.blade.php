<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Reactivation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
        }
        .token {
            font-size: 28px;
            font-weight: bold;
            color: #2d89ef;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Reactivation Request</h2>
        <p>Hello, {{ $user->email }}!</p>
        <p>You requested to reactivate your account. Use the reactivation token below to proceed:</p>
        <div class="token">{{ $token }}</div>
        <p>This token is valid for a limited time. If you did not request reactivation, please ignore this email.</p>
        <p class="footer">Thank you,<br> {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
