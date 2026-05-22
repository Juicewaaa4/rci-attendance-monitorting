<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 480px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #ddd;
        }
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .message {
            font-size: 14px;
            color: #555;
            margin: 15px 0;
        }
        .otp-box {
            font-size: 28px;
            font-weight: bold;
            color: #0b57d0;
            background: #e8f0fe;
            padding: 12px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
        }
        .cta-button {
            background: #1a73e8;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
        .footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>RCI AMS</h1>
        <p class="header">Your OTP Code</p>
        <p class="message">Use the code below to verify your identity:</p>
        <div class="otp-box">{{ $otp }}</div>
        <p class="message">This OTP is valid for <strong>5 minutes</strong>. Do not share it with anyone.</p>
        <p class="footer">If you didn’t request this code, please ignore this email or contact support.</p>
    </div>
</body>
</html>
