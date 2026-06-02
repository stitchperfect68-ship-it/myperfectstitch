<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $fields = [
        'general'  => ['site_name', 'site_tagline', 'contact_email', 'contact_phone', 'address'],
        'social'   => ['whatsapp_number', 'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url'],
        'seo'      => ['meta_title', 'meta_description', 'og_image'],
        'payment'  => ['currency', 'currency_symbol'],
    ];

    public function edit()
    {
        $settings = [];
        foreach (array_merge(...array_values($this->fields)) as $key) {
            $settings[$key] = Setting::get($key);
        }

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $allKeys = array_merge(...array_values($this->fields));
        $data    = $request->only($allKeys);

        Setting::setMany($data);

        return back()->with('success', 'Settings saved.');
    }
}
