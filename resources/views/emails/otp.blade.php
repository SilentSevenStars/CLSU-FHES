<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body style="margin:0;padding:10px 0;background-color:#f8fafc;font-family:Arial,sans-serif;">

    <div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden;">

        {{-- ══ HEADER ══ --}}
        <div style="background-color:#1E7F3E;padding:32px 40px 28px;text-align:center;">
            <table cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
                <tr>
                    <td style="padding-right:12px;vertical-align:middle;">
                        <table cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td width="56" height="56"
                                    style="width:56px;height:56px;background:#ffffff;border-radius:50%;
                                           text-align:center;vertical-align:middle;padding:0;overflow:hidden;">
                                    <img src="https://clsu.edu.ph/src/img/general/clsu-logo-green.png"
                                         alt="CLSU Logo"
                                         width="56" height="56"
                                         style="width:56px;height:56px;display:block;border:0;">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align:middle;text-align:left;">
                        <div style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:.5px;">CLSU FHES</div>
                        <div style="color:#a7f3d0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">Faculty Hiring Evaluation System</div>
                    </td>
                </tr>
            </table>
            <div style="color:#ffffff;font-size:22px;font-weight:700;">
                Email Verification
            </div>
        </div>

        {{-- ══ BODY ══ --}}
        <div style="padding:32px 24px;border:1px solid #d1fae5;border-top:none;text-align:center;">

            <p style="font-size:16px;color:#333333;margin:0 0 12px;">
                Hello, <strong>{{ $name }}</strong>!
            </p>
            <p style="font-size:14px;color:#666666;line-height:1.6;margin:0 0 32px;">
                Thank you for registering. Use the One-Time Password below
                to verify your email address and activate your account.
            </p>

            {{-- ── OTP Box ── --}}
            <div style="background-color:#f0faf3;border:2px dashed #1E7F3E;border-radius:16px;padding:24px 16px;margin-bottom:24px;width:100%;max-width:360px;box-sizing:border-box;">
                <p style="font-size:12px;color:#1E7F3E;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin:0 0 8px;">
                    Your OTP Code
                </p>
                <p style="font-size:40px;font-weight:700;letter-spacing:8px;color:#1E7F3E;line-height:1;margin:0;word-break:break-all;">
                    {{ $otp }}
                </p>
                <p style="font-size:13px;color:#888888;margin:8px 0 0;">
                    Expires in <strong>10 minutes</strong>
                </p>
            </div>

            {{-- ── Warning ── --}}
            <div style="background-color:#fff8e1;border-left:4px solid #f59e0b;border-radius:8px;padding:12px 16px;margin-top:8px;text-align:left;">
                <p style="font-size:13px;color:#92400e;line-height:1.5;margin:0;">
                    ⚠️ <strong>Never share this code</strong> with anyone.
                    CLSU FHES staff will never ask for your OTP.
                    If you did not register, please ignore this email.
                </p>
            </div>

        </div>

        {{-- ══ FOOTER ══ --}}
        <div style="background:#f9fafb;padding:24px 40px;text-align:center;border-top:1px solid #e5e7eb;">
            <p style="margin:0 0 4px;font-size:14px;color:#1E7F3E;font-weight:600;">
                CLSU HR Department — Central Luzon State University
            </p>
            <p style="margin:0;font-size:13px;color:#9ca3af;line-height:1.6;">
                This is an automated message. Please do not reply to this email.<br>
                &copy; {{ date('Y') }} Central Luzon State University. All rights reserved.
            </p>
        </div>

    </div>

</body>
</html>