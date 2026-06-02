@extends('layouts.admin')
@section('title','Settings')
@section('topbar_title','Settings')

@section('content')
<div class="page-header"><div><h1>Settings</h1><p>Site configuration and preferences</p></div></div>

<form method="POST" action="{{ route('admin.settings.update') }}">
  @csrf @method('PUT')
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div>
      <div class="card">
        <div class="card-header"><h3><i class="fas fa-globe" style="color:#F9B040;margin-right:6px"></i>General</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-input" value="{{ $settings['site_name'] ?? 'My Perfect Stitch' }}"/></div>
          <div class="form-group"><label class="form-label">Tagline</label><input type="text" name="site_tagline" class="form-input" value="{{ $settings['site_tagline'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Contact Email</label><input type="email" name="contact_email" class="form-input" value="{{ $settings['contact_email'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Contact Phone</label><input type="text" name="contact_phone" class="form-input" value="{{ $settings['contact_phone'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Address</label><input type="text" name="address" class="form-input" value="{{ $settings['address'] ?? 'Lusaka, Zambia' }}"/></div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3><i class="fas fa-credit-card" style="color:#F9B040;margin-right:6px"></i>Payment</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Currency</label><input type="text" name="currency" class="form-input" value="{{ $settings['currency'] ?? 'ZMW' }}"/></div>
          <div class="form-group"><label class="form-label">Currency Symbol</label><input type="text" name="currency_symbol" class="form-input" value="{{ $settings['currency_symbol'] ?? 'K' }}"/></div>
        </div>
      </div>
    </div>

    <div>
      <div class="card">
        <div class="card-header"><h3><i class="fab fa-whatsapp" style="color:#25D366;margin-right:6px"></i>Social & WhatsApp</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">WhatsApp Number (no +)</label><input type="text" name="whatsapp_number" class="form-input" value="{{ $settings['whatsapp_number'] ?? '260968531630' }}" placeholder="260968531630"/><div class="form-hint">Include country code, no + sign</div></div>
          <div class="form-group"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url" class="form-input" value="{{ $settings['facebook_url'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-input" value="{{ $settings['instagram_url'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-input" value="{{ $settings['linkedin_url'] ?? '' }}"/></div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3><i class="fas fa-search" style="color:#F9B040;margin-right:6px"></i>SEO</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-input" value="{{ $settings['meta_title'] ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-textarea" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea></div>
        </div>
      </div>

      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center"><i class="fas fa-save"></i> Save Settings</button>
    </div>
  </div>
</form>
@endsection
