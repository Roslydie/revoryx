
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>New Contact Message</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f6f8; padding: 40px 15px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                       style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 10px; overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1f3c88; padding: 30px; text-align: center;">

                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                               Revoryx & Partners 
                            </h1>

                            <p style="margin: 8px 0 0; color: #dce5ff; font-size: 14px;">
                                New Contact Message
                            </p>

                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 35px 30px;">

                            <h2 style="margin-top: 0; color: #222222; font-size: 22px;">
                                You received a new message
                            </h2>

                            <p style="color: #666666; font-size: 15px; line-height: 1.6;">
                                A visitor has submitted a new message through the
                                contact form on your website.
                            </p>

                            <!-- Contact information -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="margin-top: 25px;">

                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eeeeee;">
                                        <strong style="color: #333333;">Name</strong><br>
                                        <span style="color: #666666;">
                                            {{ $contact->full_name }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eeeeee;">
                                        <strong style="color: #333333;">Email</strong><br>
                                        <span style="color: #666666;">
                                            {{ $contact->email }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eeeeee;">
                                        <strong style="color: #333333;">Phone</strong><br>
                                        <span style="color: #666666;">
                                            {{ $contact->phone }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 12px 0;">
                                        <strong style="color: #333333;">Subject</strong><br>
                                        <span style="color: #666666;">
                                            {{ $contact->subject }}
                                        </span>
                                    </td>
                                </tr>

                            </table>

                            <!-- Message -->
                            <div style="margin-top: 25px; padding: 20px; background-color: #f7f8fa; border-radius: 8px;">

                                <h3 style="margin-top: 0; color: #333333; font-size: 16px;">
                                    Message
                                </h3>

                                <p style="margin-bottom: 0; color: #555555; font-size: 15px; line-height: 1.7;">
                                    {!! nl2br(e($contact->message)) !!}
                                </p>

                            </div>

                            <!-- Reply button -->
                            <div style="text-align: center; margin-top: 30px;">

                                <a href="mailto:{{ $contact->email }}"
                                   style="display: inline-block; padding: 13px 25px; background-color: #1f3c88; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">
                                    Reply to {{ $contact->full_name }}
                                </a>

                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7f8fa; padding: 20px 30px; text-align: center;">

                            <p style="margin: 0; color: #888888; font-size: 12px;">
                                This email was automatically generated by
                                Revoryx & Partners.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>

