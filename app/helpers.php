<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('image_url')) {
    function image_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'data:', '/'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $phone, ?string $message = null): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        // wa.me requires full international without plus. Assume already includes country code.
        $url = 'https://wa.me/'.$digits;

        if (! blank($message)) {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }
}

if (! function_exists('whatsapp_number')) {
    function whatsapp_number(): ?string
    {
        return \App\Models\Setting::getValue('hotel_whatsapp')
            ?? \App\Models\Setting::getValue('hotel_phone');
    }
}
