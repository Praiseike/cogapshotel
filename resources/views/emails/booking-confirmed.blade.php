@php
    $hotelName = config('app.name', 'Cogaps Hotel');
    $guestName = $booking->user?->name ?? $booking->guest_name ?? 'Guest';
    $siteEmail = \App\Models\Setting::getValue('hotel_email', 'reservations@cogapshotel.com');
    $sitePhone = \App\Models\Setting::getValue('hotel_phone', '+234 800 555 0134');
    $siteAddress = \App\Models\Setting::getValue('hotel_address', '12 Independence Avenue, Victoria Island, Lagos');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed — {{ $hotelName }}</title>
</head>
<body style="margin:0;padding:0;background-color:#14110d;font-family:Georgia,'Times New Roman',serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">
        Your reservation at {{ $hotelName }} is confirmed — {{ $booking->room?->name ?? 'Room' }}, {{ $booking->check_in->format('M d') }} to {{ $booking->check_out->format('M d, Y') }}.
    </div>
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#14110d;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background-color:#14110d;">
                    {{-- gold hairline --}}
                    <tr>
                        <td style="background-color:#b08d46;font-size:0;line-height:0;height:3px;">&nbsp;</td>
                    </tr>
                    {{-- masthead --}}
                    <tr>
                        <td align="center" style="padding:36px 30px 8px;">
                            <p style="margin:0;color:#d5bd85;font-size:11px;letter-spacing:4px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">&#10022; &nbsp;Stay With Us&nbsp; &#10022;</p>
                            <h1 style="margin:12px 0 0;color:#fdfcf9;font-size:32px;font-weight:500;letter-spacing:1px;">{{ $hotelName }}</h1>
                            <p style="margin:8px 0 0;color:#d5bd85;font-size:10px;letter-spacing:5px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Hotel &middot; Suites &middot; Residence</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:20px 30px 28px;">
                            <p style="margin:0;color:#fdfcf9;font-size:22px;font-style:italic;">&ldquo;Arrive as a guest, leave as family.&rdquo;</p>
                        </td>
                    </tr>
                    {{-- confirmation card --}}
                    <tr>
                        <td style="background-color:#faf8f3;padding:36px 36px 32px;">
                            <p style="margin:0;color:#96742f;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Reservations</p>
                            <h2 style="margin:8px 0 0;color:#14110d;font-size:28px;font-weight:500;">Booking Confirmed</h2>
                            <p style="margin:14px 0 0;color:#353023;font-size:15px;line-height:1.7;font-family:Arial,Helvetica,sans-serif;">Dear {{ $guestName }},</p>
                            <p style="margin:8px 0 0;color:#353023;font-size:15px;line-height:1.7;font-family:Arial,Helvetica,sans-serif;">Your reservation has been confirmed. We look forward to welcoming you.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 0;background-color:#ffffff;border:1px solid #e8e0cf;">
                                <tr>
                                    <td style="padding:14px 18px;color:#6b6252;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Booking Reference</td>
                                    <td align="right" style="padding:14px 18px;color:#14110d;font-weight:bold;font-size:14px;font-family:'Courier New',monospace;">{{ $booking->payment_reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-top:1px solid #f3eee3;color:#6b6252;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Room</td>
                                    <td align="right" style="padding:14px 18px;border-top:1px solid #f3eee3;color:#14110d;font-weight:bold;font-size:15px;">{{ $booking->room?->name ?? 'Room' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-top:1px solid #f3eee3;color:#6b6252;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Check-in</td>
                                    <td align="right" style="padding:14px 18px;border-top:1px solid #f3eee3;color:#14110d;font-weight:bold;font-size:15px;">{{ $booking->check_in->format('l, M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-top:1px solid #f3eee3;color:#6b6252;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Check-out</td>
                                    <td align="right" style="padding:14px 18px;border-top:1px solid #f3eee3;color:#14110d;font-weight:bold;font-size:15px;">{{ $booking->check_out->format('l, M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-top:1px solid #f3eee3;color:#6b6252;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Guests</td>
                                    <td align="right" style="padding:14px 18px;border-top:1px solid #f3eee3;color:#14110d;font-weight:bold;font-size:15px;">{{ $booking->guests_count }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0 18px;">
                                        <div style="border-top:1px solid #b08d46;">&nbsp;</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 18px 20px;color:#96742f;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Total Paid</td>
                                    <td align="right" style="padding:6px 18px 20px;color:#96742f;font-weight:bold;font-size:26px;">&#8358;{{ number_format($booking->total_amount, 2) }}</td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0;color:#6b6252;font-size:13px;line-height:1.7;font-family:Arial,Helvetica,sans-serif;">Check-in 2PM &middot; Check-out 12PM. Please present this reference on arrival. If you need anything before your stay, simply reply to this email.</p>

                            <p style="margin:20px 0 0;color:#353023;font-size:15px;line-height:1.7;font-family:Arial,Helvetica,sans-serif;">Warm regards,<br><strong>{{ $hotelName }}</strong></p>
                        </td>
                    </tr>
                    {{-- footer --}}
                    <tr>
                        <td align="center" style="padding:28px 30px 8px;">
                            <p style="margin:0;color:#d5bd85;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Since &middot; Heritage &middot; Honour</p>
                            <p style="margin:12px 0 0;color:rgba(253,252,249,0.65);font-size:13px;line-height:1.8;font-family:Arial,Helvetica,sans-serif;">{{ $siteAddress }}<br>{{ $sitePhone }} &middot; {{ $siteEmail }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#b08d46;font-size:0;line-height:0;height:3px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:16px 30px 32px;">
                            <p style="margin:0;color:rgba(253,252,249,0.4);font-size:11px;letter-spacing:2px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">&copy; {{ date('Y') }} {{ $hotelName }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
