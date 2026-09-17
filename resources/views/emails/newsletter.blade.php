<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background:#eef3f8;font-family:Arial,Helvetica,sans-serif;color:#26384d;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">The latest news and updates from Revoryx &amp; Partners.</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#eef3f8;padding:38px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 12px 35px rgba(24,55,91,.12);">
                    <tr>
                        <td style="height:6px;background:#65d5a0;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:30px 38px;background:#123b6d;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#a9efd0;font-size:11px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Revoryx &amp; Partners</td>
                                    <td align="right" style="color:#d9e8f7;font-size:12px;">Newsletter</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top:22px;color:#ffffff;font-size:28px;line-height:1.25;font-weight:bold;">{{ $subject }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 38px 28px;">
                            <div style="height:3px;width:48px;margin-bottom:24px;background:#65d5a0;">&nbsp;</div>
                            <div style="font-size:16px;line-height:1.8;color:#42566d;">{!! $content !!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 38px 34px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f2f8f5;border:1px solid #d9eee3;border-radius:10px;">
                                <tr>
                                    <td style="padding:18px 20px;color:#365447;font-size:13px;line-height:1.6;">
                                        Thank you for being part of the Revoryx &amp; Partners community. We are glad to keep you informed.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 38px;background:#f7f9fb;border-top:1px solid #e5ebf0;text-align:center;">
                            <p style="margin:0 0 12px;color:#718198;font-size:12px;line-height:1.6;">You are receiving this email because you subscribed to the Revoryx &amp; Partners newsletter.</p>
                            <p style="margin:0 0 14px;font-size:12px;">
                                <a href="{{ url('/api/newsletter/unsubscribe/' . $id) }}" style="color:#123b6d;font-weight:bold;text-decoration:underline;">Unsubscribe from this newsletter</a>
                            </p>
                            <p style="margin:0;color:#9aa8b6;font-size:11px;line-height:1.6;">&copy; {{ date('Y') }} Revoryx &amp; Partners. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>