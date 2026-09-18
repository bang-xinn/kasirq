<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'receipt_header' => 'required|string|max:255',
            'receipt_footer' => 'required|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Setting::updateOrCreate(['key' => 'receipt_header'], ['value' => $request->receipt_header]);
        Setting::updateOrCreate(['key' => 'receipt_footer'], ['value' => $request->receipt_footer]);

        if ($request->hasFile('qris_image')) {
            $imageName = 'qris.' . $request->file('qris_image')->extension();
            $request->file('qris_image')->move(public_path('images'), $imageName);
            Setting::updateOrCreate(['key' => 'qris_image'], ['value' => 'images/' . $imageName]);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
