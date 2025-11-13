{{-- resources/views/admin/setting/whatsapp_otp_settings.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('WhatsApp OTP Settings'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings me-2"></i>
                {{ get_phrase('WhatsApp OTP Settings') }}
            </h4>
        </div>
    </div>
</div>

<div class="row mt-3">
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ get_phrase('OneSender API Configuration') }}</h4>
                <p class="card-description">{{ get_phrase('Configure your OneSender WhatsApp API settings') }}</p>
                
                <form method="POST" action="{{ route('admin.whatsapp.otp.settings.update') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp_otp_enabled">{{ get_phrase('Enable WhatsApp OTP') }}</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="whatsapp_otp_enabled" 
                                           name="whatsapp_otp_enabled" value="1" 
                                           {{ get_settings('whatsapp_otp_enabled') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="whatsapp_otp_enabled">
                                        {{ get_phrase('Enable WhatsApp OTP Login') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ get_phrase('Connection Test') }}</label>
                                <div>
                                    <button type="button" class="btn btn-info btn-sm" id="testConnectionBtn">
                                        <i class="mdi mdi-wifi"></i> {{ get_phrase('Test Connection') }}
                                    </button>
                                    <div id="connectionResult" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="onesender_api_url">{{ get_phrase('OneSender API URL') }} <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="onesender_api_url" name="onesender_api_url" 
                                       value="{{ get_settings('onesender_api_url') ?: 'https://sender.api-wa.my.id/api/v1/messages' }}" 
                                       placeholder="https://sender.api-wa.my.id/api/v1/messages" required>
                                <small class="form-text text-muted">{{ get_phrase('Your OneSender API endpoint URL') }}</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="onesender_api_key">{{ get_phrase('OneSender API Key') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="onesender_api_key" name="onesender_api_key" 
                                           value="{{ get_settings('onesender_api_key') }}" 
                                           placeholder="Your OneSender API Key" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleApiKey">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">{{ get_phrase('Your OneSender Bearer token') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <h5>{{ get_phrase('Message Templates') }}</h5>
                    <p class="text-muted">{{ get_phrase('Customize your OTP and welcome messages. Use {app_name}, {otp_code}, {name} as placeholders.') }}</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="otp_login_message">{{ get_phrase('Login OTP Message') }}</label>
                                <textarea class="form-control" id="otp_login_message" name="otp_login_message" 
                                          rows="6" placeholder="{{ get_phrase('Login OTP message template') }}">{{ get_settings('otp_login_message') ?: "🔐 *{app_name} - Kode Verifikasi Login*\n\nKode OTP Anda: *{otp_code}*\n\nMasukkan kode ini untuk login ke akun Anda.\nKode berlaku selama 5 menit.\n\n⚠️ Jangan bagikan kode ini kepada siapapun!" }}</textarea>
                                <small class="form-text text-muted">{{ get_phrase('Available placeholders: {app_name}, {otp_code}') }}</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="otp_register_message">{{ get_phrase('Registration OTP Message') }}</label>
                                <textarea class="form-control" id="otp_register_message" name="otp_register_message" 
                                          rows="6" placeholder="{{ get_phrase('Registration OTP message template') }}">{{ get_settings('otp_register_message') ?: "🔐 *{app_name} - Kode Verifikasi Registrasi*\n\nKode OTP Anda: *{otp_code}*\n\nMasukkan kode ini untuk menyelesaikan proses registrasi.\nKode berlaku selama 5 menit.\n\n⚠️ Jangan bagikan kode ini kepada siapapun!" }}</textarea>
                                <small class="form-text text-muted">{{ get_phrase('Available placeholders: {app_name}, {otp_code}') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="otp_welcome_message">{{ get_phrase('Welcome Message') }}</label>
                                <textarea class="form-control" id="otp_welcome_message" name="otp_welcome_message" 
                                          rows="5" placeholder="{{ get_phrase('Welcome message template') }}">{{ get_settings('otp_welcome_message') ?: "🎉 *Selamat datang di {app_name}!*\n\nHalo {name}! 👋\n\nTerima kasih telah bergabung dengan kami. Akun Anda telah berhasil didaftarkan.\n\nSelamat menggunakan layanan kami! 🚀" }}</textarea>
                                <small class="form-text text-muted">{{ get_phrase('Available placeholders: {app_name}, {name}') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="mdi mdi-content-save"></i> {{ get_phrase('Save Settings') }}
                        </button>
                        <a href="{{ route('admin.system.setting') }}" class="btn btn-light">
                            {{ get_phrase('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Info Card --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ get_phrase('Setup Instructions') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ get_phrase('OneSender Setup:') }}</h6>
                        <ol>
                            <li>{{ get_phrase('Visit OneSender website') }} (<a href="https://onesender.net" target="_blank">https://onesender.net</a>)</li>
                            <li>{{ get_phrase('Create an account and WhatsApp instance') }}</li>
                            <li>{{ get_phrase('Get your API URL and API Key from dashboard') }}</li>
                            <li>{{ get_phrase('Enter the credentials above and test connection') }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ get_phrase('Usage:') }}</h6>
                        <ul>
                            <li>{{ get_phrase('Users can login using WhatsApp OTP') }}</li>
                            <li>{{ get_phrase('Registration requires WhatsApp verification') }}</li>
                            <li>{{ get_phrase('OTP expires after 5 minutes') }}</li>
                            <li>{{ get_phrase('Rate limiting: 1 OTP per minute per number') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle API Key visibility
    const toggleApiKey = document.getElementById('toggleApiKey');
    const apiKeyInput = document.getElementById('onesender_api_key');
    
    toggleApiKey.addEventListener('click', function() {
        const type = apiKeyInput.getAttribute('type') === 'password' ? 'text' : 'password';
        apiKeyInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        if (type === 'text') {
            icon.classList.remove('mdi-eye-outline');
            icon.classList.add('mdi-eye-off-outline');
        } else {
            icon.classList.remove('mdi-eye-off-outline');
            icon.classList.add('mdi-eye-outline');
        }
    });

    // Test Connection
    const testConnectionBtn = document.getElementById('testConnectionBtn');
    const connectionResult = document.getElementById('connectionResult');
    
    testConnectionBtn.addEventListener('click', function() {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> {{ get_phrase("Testing...") }}';
        this.disabled = true;
        
        connectionResult.innerHTML = '';
        
        fetch('{{ route("admin.test.onesender") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            let alertClass = data.success ? 'alert-success' : 'alert-danger';
            let icon = data.success ? 'mdi-check-circle' : 'mdi-alert-circle';
            
            connectionResult.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="mdi ${icon}"></i> ${data.message}
                </div>
            `;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                connectionResult.innerHTML = '';
            }, 5000);
        })
        .catch(error => {
            connectionResult.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="mdi mdi-alert-circle"></i> {{ get_phrase("Connection test failed") }}
                </div>
            `;
        })
        .finally(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
});
</script>
@endpush

@endsection