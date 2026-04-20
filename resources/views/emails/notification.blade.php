<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:10px 0;background-color:#f8fafc;font-family:Arial,sans-serif;">

    <div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden;">

        {{-- ══ HEADER ══ --}}
        <div style="background-color:#1E7F3E;padding:32px 40px 28px;text-align:center;">
            <table cellpadding="0" cellspacing="0" style="margin:0 auto 16px;">
                <tr>
                    <td style="padding-right:12px;vertical-align:middle;">
                        {{-- Inline logo using base64 to guarantee rendering in email clients --}}
                        <div style="width:56px;height:56px;background:#ffffff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;overflow:hidden;">
                            <img src="{{ url('image/clsu-logo-green.png') }}"
                                 alt="CLSU Logo"
                                 width="48" height="48"
                                 style="width:48px;height:48px;object-fit:contain;display:block;">
                        </div>
                    </td>
                    <td style="vertical-align:middle;text-align:left;">
                        <div style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:.5px;">CLSU FHES</div>
                        <div style="color:#a7f3d0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">Faculty Hiring Evaluation System</div>
                    </td>
                </tr>
            </table>
            <div style="color:#ffffff;font-size:22px;font-weight:700;">
                {{ $subject }}
            </div>
        </div>

        {{-- ══ BODY ══ --}}
        <div style="padding:36px 40px;border:1px solid #d1fae5;border-top:none;">

            {{-- Inner content built by buildNotificationBody() — greeting, message, placement details --}}
            {!! $notificationMessage !!}

            {{-- ── Attachments ─────────────────────────────────────────── --}}
            @if(!empty($attachedFiles))
            <div style="margin:28px 0 0;">
                <p style="margin:0 0 10px;font-weight:700;color:#1E7F3E;font-size:15px;">📎 Attachments ({{ count($attachedFiles) }})</p>
                <table style="width:100%;border-collapse:collapse;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    @foreach($attachedFiles as $file)
                    @php
                        $ext  = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $size = $file['size'] >= 1048576
                            ? round($file['size'] / 1048576, 1) . ' MB'
                            : round($file['size'] / 1024,    1) . ' KB';
                    @endphp
                    <tr>
                        <td style="padding:10px 16px;border-bottom:1px solid #e5e7eb;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:36px;height:36px;background:#1E7F3E;border-radius:6px;text-align:center;vertical-align:middle;">
                                        <span style="color:white;font-size:11px;font-weight:700;">{{ $ext }}</span>
                                    </td>
                                    <td style="padding-left:12px;vertical-align:middle;">
                                        <div style="font-size:15px;font-weight:600;color:#111827;">{{ $file['name'] }}</div>
                                        <div style="font-size:13px;color:#9ca3af;">{{ $size }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding:10px 16px;">
                            <span style="font-size:13px;color:#9ca3af;">Files are attached directly to this email.</span>
                        </td>
                    </tr>
                </table>
            </div>
            @endif

            {{-- CTA button --}}
            <div style="text-align:center;margin:36px 0 8px;">
                <a href="{{ url('/applicant/notifications') }}"
                   style="display:inline-block;padding:16px 48px;background:#1E7F3E;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:700;font-size:16px;letter-spacing:.3px;">
                    View All Notifications →
                </a>
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