<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 40px 16px; }
        .container { max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background-color: #0B712C; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 20px; font-weight: bold; line-height: 1.4; }
        .body { padding: 40px; text-align: center; }
        .greeting { font-size: 16px; color: #333; margin-bottom: 12px; }
        .message { font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 32px; }
        .otp-box { display: inline-block; background-color: #f0faf3; border: 2px dashed #0B712C; border-radius: 16px; padding: 24px 48px; margin-bottom: 24px; }
        .otp-label { font-size: 12px; color: #0B712C; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
        .otp-code { font-size: 48px; font-weight: bold; letter-spacing: 14px; color: #0A6025; line-height: 1; }
        .expiry-note { font-size: 13px; color: #888; margin-top: 8px; }
        .warning { background-color: #fff8e1; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 12px 16px; margin-top: 24px; text-align: left; }
        .warning p { font-size: 13px; color: #92400e; line-height: 1.5; }
        .footer { background-color: #f9f9f9; padding: 20px 40px; text-align: center; border-top: 1px solid #eee; }
        .footer p { font-size: 12px; color: #aaa; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Faculty Hiring<br>Evaluation System</h1>
        </div>
        <div class="body">
            <p class="greeting">Hello, <strong>{{ $name }}</strong>!</p>
            <p class="message">
                Thank you for registering. Use the One-Time Password below
                to verify your email address and activate your account.
            </p>
            <div class="otp-box">
                <p class="otp-label">Your OTP Code</p>
                <p class="otp-code">{{ $otp }}</p>
                <p class="expiry-note">Expires in <strong>10 minutes</strong></p>
            </div>
            <div class="warning">
                <p>
                    ⚠️ <strong>Never share this code</strong> with anyone.
                    CLSU FHES staff will never ask for your OTP.
                    If you did not register, please ignore this email.
                </p>
            </div>
        </div>
        <div class="footer">
            <p>
                &copy; {{ date('Y') }} Central Luzon State University<br>
                Faculty Hiring Evaluation System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>