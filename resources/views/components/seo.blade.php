@props(['title' => null, 'description' => null, 'image' => null, 'type' => 'website'])

@php
    $hotelName = \App\Models\Setting::getValue('hotel_name', config('app.name', 'Hotel'));
    $defaultDesc = \App\Models\Setting::getValue('hotel_description', 'A classic grand hotel — timeless comfort, refined service, quiet luxury');
    $fullTitle = $title ? $title . ' — ' . $hotelName : $hotelName;
    $metaDesc = $description ?: $defaultDesc;
    $canonical = url()->current();
    $ogImage = $image ?: asset('favicon.ico');
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ Str::limit($metaDesc, 160) }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ Str::limit($metaDesc, 160) }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $hotelName }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ Str::limit($metaDesc, 160) }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@php
    $hotelAddress = \App\Models\Setting::getValue('hotel_address', '12 Independence Avenue, Victoria Island, Lagos');
    $hotelPhone = \App\Models\Setting::getValue('hotel_phone', '+234 800 555 0134');
    $hotelEmail = \App\Models\Setting::getValue('hotel_email', 'reservations@hotel.com');
@endphp
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Hotel',
    'name' => $hotelName,
    'description' => $defaultDesc,
    'url' => url('/'),
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $hotelAddress,
    ],
    'telephone' => $hotelPhone,
    'email' => $hotelEmail,
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!}
</script>
