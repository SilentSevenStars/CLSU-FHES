<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale:1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:20px 0;background-color:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,'Open Sans','Helvetica Neue',sans-serif;">
    <div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden;">

                <!-- ══ BODY ══════════════════════════════════════════════════ -->
                <tr>
                    <td style="background:#ffffff;padding:36px 40px;border:1px solid #d1fae5;border-top:none;">

                        <!-- Greeting -->
                        <p style="margin:0 0 18px;font-size:16px;color:#374151;font-family:Arial,sans-serif;">
                            Dear <strong>{{ $applicantName }}</strong>,
                        </p>

        {!! $notificationMessage !!}




    </div>
</body>
</html>
