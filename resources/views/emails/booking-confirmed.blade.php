<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background-color:#d97706;padding:30px;text-align:center;">
                            <h1 style="color:#ffffff;margin:0;font-size:24px;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 30px;">
                            <h2 style="color:#1a1a1a;margin:0 0 20px;font-size:20px;">Booking Confirmed</h2>
                            <p style="color:#555;line-height:1.6;margin:0 0 20px;">Dear {{ $booking->user->name }},</p>
                            <p style="color:#555;line-height:1.6;margin:0 0 20px;">Your reservation has been confirmed. Here are the details:</p>

                            <table width="100%" cellpadding="12" cellspacing="0" style="background-color:#f9f9f9;border-radius:8px;margin:0 0 20px;">
                                <tr>
                                    <td style="color:#555;font-size:14px;">Booking Reference</td>
                                    <td style="color:#1a1a1a;font-weight:bold;text-align:right;">{{ $booking->payment_reference }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#555;font-size:14px;border-top:1px solid #eee;">Room</td>
                                    <td style="color:#1a1a1a;font-weight:bold;text-align:right;border-top:1px solid #eee;">{{ $booking->room->name }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#555;font-size:14px;border-top:1px solid #eee;">Check-in</td>
                                    <td style="color:#1a1a1a;font-weight:bold;text-align:right;border-top:1px solid #eee;">{{ $booking->check_in->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#555;font-size:14px;border-top:1px solid #eee;">Check-out</td>
                                    <td style="color:#1a1a1a;font-weight:bold;text-align:right;border-top:1px solid #eee;">{{ $booking->check_out->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#555;font-size:14px;border-top:1px solid #eee;">Nights</td>
                                    <td style="color:#1a1a1a;font-weight:bold;text-align:right;border-top:1px solid #eee;">{{ $booking->getNightsCount() }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#555;font-size:14px;border-top:1px solid #eee;">Total Paid</td>
                                    <td style="color:#d97706;font-weight:bold;font-size:18px;text-align:right;border-top:1px solid #eee;">&#8358;{{ number_format($booking->total_amount, 2) }}</td>
                                </tr>
                            </table>

                            <p style="color:#555;line-height:1.6;margin:0 0 20px;">We look forward to welcoming you. If you have any questions, please don't hesitate to contact us.</p>

                            <p style="color:#555;line-height:1.6;margin:0;">Warm regards,<br><strong>{{ config('app.name') }}</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f9f9f9;padding:20px 30px;text-align:center;">
                            <p style="color:#999;font-size:12px;margin:0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
