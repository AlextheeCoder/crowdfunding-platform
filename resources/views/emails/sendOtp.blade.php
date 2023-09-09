<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
        }
        .container {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 0 0 10px;
        }
        .otp {
            font-weight: bold;
            font-size: 20px;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>OTP Verification</h1>
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>Your OTP code for verification code is: <span class="otp">{{ $otp }}</span></p>
            <p>Thank you!</p>
        </div>
    </div>
</body>
</html>
