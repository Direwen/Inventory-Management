<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
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
        .otp {
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
        <h2>Password Reset Request</h2>
        <p>Hello, {{ $user->email }}!</p>
        <p>You requested to reset your password. Use the OTP below to proceed:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This OTP is valid for 10 minutes. If you did not request a password reset, please ignore this email.</p>
        <p class="footer">Thank you,<br> {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
