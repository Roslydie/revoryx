<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Revoryx &amp; Partners</title>
</head>
<body style="margin:0; padding:0; background:#f3f6f4; color:#24332b; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:40px 16px; background:#f3f6f4;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px; overflow:hidden; background:#ffffff; border-radius:12px; box-shadow:0 10px 30px rgba(23, 77, 148, 0.1);">
                    <tr>
                        <td style="padding:32px; text-align:center; background:#123b2a;">
                            <div style="color:#9ee2bd; font-size:12px; font-weight:bold; letter-spacing:3px;">REVORYX &amp; PARTNERS</div>
                            <h1 style="margin:14px 0 0; color:#ffffff; font-size:27px; line-height:1.25;">Welcome to the team</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px;">
                            <p style="margin:0 0 18px; font-size:17px;">Hello {{ $user->full_name }},</p>
                            <p style="margin:0 0 24px; color:#5f6d65; font-size:15px; line-height:1.7;">Your Revoryx &amp; Partners administrator account has been created. You can use the details below to sign in, then change your password from your profile.</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 26px; border:1px solid #dce9e1; border-radius:8px; background:#f7fbf8;">
                                <tr><td style="padding:14px 16px; color:#64736a; font-size:13px;">Email</td><td style="padding:14px 16px; text-align:right; color:#123b2a; font-size:14px; font-weight:bold;">{{ $user->email }}</td></tr>
                                <tr><td style="padding:14px 16px; border-top:1px solid #dce9e1; color:#64736a; font-size:13px;">Temporary password</td><td style="padding:14px 16px; border-top:1px solid #dce9e1; text-align:right; color:#123b2a; font-size:14px; font-weight:bold;">{{ $password }}</td></tr>
                            </table>
                            <div style="text-align:center; margin:30px 0;">
                                <a href="{{ $resetUrl }}" style="display:inline-block; padding:13px 24px; border-radius:6px; color:#ffffff; background:#1d8053; font-size:14px; font-weight:bold; text-decoration:none;">Open your profile</a>
                            </div>
                            <p style="margin:0; color:#718078; font-size:13px; line-height:1.7;">For your security, please sign in and replace this temporary password as soon as possible. Never share your password by email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; text-align:center; background:#f7f9f8; color:#829087; font-size:12px;">This email was sent automatically by Revoryx &amp; Partners.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>