<x-static-page title="Hotel Policies" meta="House rules, check-in details, cancellation and payment policies for our hotel.">
    <p>To ensure every guest enjoys a serene and orderly stay, we ask you to note our house policies. Please contact us on WhatsApp or at the front desk if you have any questions.</p>

    <h2>Check-in &amp; Check-out</h2>
    <ul>
        <li>Check-in: from 2:00 PM — early check-in subject to availability</li>
        <li>Check-out: by 12:00 PM — late check-out available on request</li>
        <li>Valid government ID and booking confirmation required at reception</li>
        <li>Guests arriving after 10:00 PM should notify us in advance</li>
    </ul>

    <h2>Reservations &amp; Payments</h2>
    <ul>
        <li>All online bookings require full payment via Paystack to confirm</li>
        <li>Rates are per night per room and include applicable taxes unless stated</li>
        <li>Prices may vary by season and length of stay — the rate shown at checkout is final</li>
        <li>We accept Paystack and direct bank transfer on request</li>
    </ul>

    <h2>Cancellation &amp; No-Show</h2>
    <ul>
        <li>Free cancellation up to 24 hours before check-in (2:00 PM previous day)</li>
        <li>Late cancellation or no-show may be charged for the first night</li>
        <li>Refunds are processed to the original payment method within 5–7 business days</li>
        <li>Cancel from your dashboard or contact us directly — WhatsApp is fastest</li>
    </ul>

    <h2>Occupancy &amp; Extra Guests</h2>
    <ul>
        <li>Maximum occupancy per room as listed — extra beds on request where possible</li>
        <li>Children under 6 stay free when sharing existing bedding</li>
        <li>Please inform us of total guests at booking for proper preparation</li>
    </ul>

    <h2>House Rules</h2>
    <ul>
        <li>Quiet hours: 10:00 PM — 7:00 AM</li>
        <li>Smoking only in designated areas</li>
        <li>Pets by prior arrangement only</li>
        <li>Visitors must register at reception after 9:00 PM</li>
    </ul>

    <h2>Dining &amp; Facilities</h2>
    <ul>
        <li>Breakfast served 7:00 AM — 10:30 AM; other dining as per <a href="{{ route('services.index') }}">Services</a></li>
        <li>Pool and fitness accessible to in-house guests — please observe posted hours</li>
        <li>Room service and concierge available 24 hours</li>
    </ul>

    <blockquote>
        Questions? Message us instantly on WhatsApp or call the house. We respond within the hour, day and night.
    </blockquote>

    @php($waUrl = whatsapp_url(\App\Models\Setting::getValue('hotel_whatsapp', \App\Models\Setting::getValue('hotel_phone')), 'Hello, I have a question about your hotel policies.'))
    @if($waUrl)
    <p class="mt-8"><a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn-dark">Chat on WhatsApp</a></p>
    @endif
</x-static-page>
