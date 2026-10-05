@php
    $waNumber = \App\Models\Setting::getValue('hotel_whatsapp', \App\Models\Setting::getValue('hotel_phone'));
    $waMessage = \App\Models\Setting::getValue('hotel_whatsapp_message', 'Hello! I would like to enquire about availability.');
    $waUrl = whatsapp_url($waNumber, $waMessage);
@endphp
@if($waUrl)
<a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
   class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-[#25D366] text-white pl-4 pr-5 py-3 shadow-[0_12px_30px_rgba(0,0,0,0.25)] hover:bg-[#1ebe59] transition-colors">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M19.05 4.94A9.91 9.91 0 0 0 12.04 2C6.58 2 2.14 6.45 2.14 10.92c0 1.57.41 3.1 1.19 4.45L2 22l6.81-1.79a9.87 9.87 0 0 0 3.23.66h.01c5.46 0 9.9-4.45 9.9-9.92 0-2.65-1.03-5.14-2.9-7.01zm-7.01 15.24h-.01a8.18 8.18 0 0 1-4.17-1.14l-.3-.18-4.04 1.06 1.08-3.94-.2-.4A8.19 8.19 0 0 1 3.86 10.92C3.86 6.42 7.52 2.75 12.03 2.75c2.18 0 4.22.85 5.76 2.4a8.1 8.1 0 0 1 2.4 5.8c0 4.51-3.67 8.18-8.17 8.23zm6.74-6.14c-.37-.19-2.2-1.09-2.54-1.21-.34-.12-.59-.19-.84.19-.25.37-.96 1.21-1.18 1.46-.22.25-.44.28-.81.09-.37-.19-1.56-.57-2.98-1.83-1.1-.98-1.84-2.2-2.06-2.57-.22-.37-.02-.57.16-.76.16-.16.37-.44.56-.66.19-.22.25-.37.37-.62.12-.25.06-.47-.03-.66-.09-.19-.84-2.02-1.15-2.77-.3-.72-.61-.62-.84-.63l-.72-.01c-.25 0-.66.09-1 .47-.34.37-1.31 1.28-1.31 3.12s1.34 3.62 1.53 3.87c.19.25 2.64 4.03 6.39 5.65.89.38 1.59.61 2.13.78.9.29 1.71.25 2.36.15.72-.11 2.2-.9 2.51-1.77.31-.87.31-1.62.22-1.77-.09-.16-.34-.25-.71-.44z"/>
    </svg>
    <span class="text-[11px] font-medium uppercase tracking-[0.18em] hidden sm:inline">Chat on WhatsApp</span>
</a>
@endif
