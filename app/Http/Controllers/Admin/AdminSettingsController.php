<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::allAsKeyValue();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'logo'     => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'site_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo_path');

            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('logo_path', $path, 'image');
        }

        if ($request->filled('site_name')) {
            Setting::set('site_name', $request->site_name, 'text');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
