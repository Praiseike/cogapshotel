<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'nullable|string|max:255',
            'hotel_email' => 'nullable|email|max:255',
            'hotel_phone' => 'nullable|string|max:20',
            'hotel_whatsapp' => 'nullable|string|max:20',
            'hotel_whatsapp_message' => 'nullable|string|max:500',
            'hotel_address' => 'nullable|string|max:500',
            'hotel_description' => 'nullable|string|max:1000',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
