{{-- resources/views/auth/phone-login.blade.php --}}
@extends('layouts.frontend')
@push('title', get_phrase('Login with WhatsApp'))

@section('frontend_layout')
    <style>
        .ua-slider-banner>img {
            height: 600px;
        }
        .phone-input {
            position: relative;
        }
        .country-code {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 1;
            font-weight: 500;
        }
        .phone-number-input {
            padding-left: 50px !important;
        }
        .btn-whatsapp-otp {
            background: #25D366;
            border-color: #25D366;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-whatsapp-otp:hover {
            background: #128C7E;
            border-color: #128C7E;
            color: white;
            transform: translateY(-1px);
        }
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .whatsapp-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }
    </style>

    <!-- Start Main Area -->
    <section class="mb-60px mt-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-5 col-lg-6">
                    <div>
                        <div class="mb-50px">
                            <div class="text-center mb-4">
                                <svg class="whatsapp-icon" fill="#25D366" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                </svg>
                            </div>
                            <h3 class="mb-3 in-title2-24px text-center">{{ get_phrase('Login with WhatsApp') }}</h3>
                            <p class="in-subtitle3-16px lh-base text-center">{{ get_phrase('Enter your WhatsApp number to receive OTP code') }}</p>
                        </div>
                        
                        <div class="mb-30px">
                            <form id="phoneLoginForm">
                                @csrf
                                <div class="mb-4">
                                    <label for="phone" class="form-label ua-form-label mb-3">{{ get_phrase('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                    <div class="phone-input">
                                        <span class="country-code">+62</span>
                                        <input type="tel" 
                                               class="form-control ua-form-control phone-number-input" 
                                               id="phone" 
                                               name="phone" 
                                               placeholder="8123456789" 
                                               pattern="[0-9]{8,13}"
                                               autocomplete="tel"
                                               required>
                                    </div>
                                    <small class="text-muted">{{ get_phrase('Example: 8123456789 (without +62 or 0)') }}</small>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <button type="submit" class="btn btn-whatsapp-otp w-100 mb-3" id="sendOtpBtn">
                                    <span class="loading-spinner"></span>
                                    <svg class="whatsapp-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                    </svg>
                                    {{ get_phrase('Send OTP Code') }}
                                </button>
                                
                                <div class="text-center">
                                    <p class="ua-subtitle-16px mb-2">{{ get_phrase('Don\'t have an account?') }} 
                                        <a href="{{ route('register') }}" class="fw-semibold ua-link">{{ get_phrase('Sign up now') }}</a>
                                    </p>
                                    <p class="ua-subtitle-16px">
                                        <a href="{{ route('login') }}" class="fw-semibold ua-link">{{ get_phrase('Login with Email') }}</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-7 col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-end">
                        <div class="ua-banner-slider-wrap">
                            @php
                                $homeBanner = json_decode(get_frontend_settings('mother_homepage_banner'), true);
                            @endphp
                            <!-- Swiper -->
                            <div class="swiper ua-slider">
                                <div class="swiper-wrapper">
                                    @if (!empty($homeBanner) && is_array($homeBanner))
                                        @foreach ($homeBanner as $banner)
                                            <div class="swiper-slide">
                                                <div class="ua-slider-banner">
                                                    <img class="banner" src="{{ asset('uploads/mother_homepage_banner/' . $banner['image']) }}" alt="banner">
                                                </div>
                                                <div class="ua-slider-content">
                                                    <img class="mb-3" src="{{ asset('assets/frontend/images/login/ua-star-shape.svg') }}" alt="shape">
                                                    <h1 class="ua-title-36px text-white mb-3">{{ $banner['title'] }}</h1>
                                                    <p class="in-subtitle-16px text-white ua-slider-subtitle-margin">{{ $banner['description'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <div class="ua-slider-banner">
                                                <img class="banner" src="{{ asset('assets/frontend/images/login/ua-login-banner1.webp') }}" alt="banner">
                                            </div>
                                            <div class="ua-slider-content">
                                                <img class="mb-3" src="{{ asset('assets/frontend/images/login/ua-star-shape.svg') }}" alt="shape">
                                                <h1 class="ua-title-36px text-white mb-3">{{ get_phrase('Welcome') }}</h1>
                                                <p class="in-subtitle-16px text-white ua-slider-subtitle-margin">{{ get_phrase('Login with WhatsApp OTP') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Main Area -->

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('phoneLoginForm');
    const submitBtn = document.getElementById('sendOtpBtn');
    const spinner = submitBtn.querySelector('.loading-spinner');
    const phoneInput = document.getElementById('phone');
    
    // Phone number validation
    phoneInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        e.target.value = e.target.value.replace(/\D/g, '');
        
        // Validate phone number
        const phoneNumber = e.target.value;
        const isValid = phoneNumber.length >= 8 && phoneNumber.length <= 13;
        
        if (phoneNumber.length > 0) {
            if (isValid) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else {
                e.target.classList.remove('is-valid');
                e.target.classList.add('is-invalid');
                e.target.nextElementSibling.nextElementSibling.textContent = '{{ get_phrase("Phone number must be 8-13 digits") }}';
            }
        } else {
            e.target.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const phoneNumber = phoneInput.value.trim();
        
        // Validate phone number
        if (!phoneNumber || phoneNumber.length < 8 || phoneNumber.length > 13) {
            phoneInput.classList.add('is-invalid');
            phoneInput.nextElementSibling.nextElementSibling.textContent = '{{ get_phrase("Please enter a valid WhatsApp number") }}';
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        
        const formData = new FormData(form);
        
        fetch('{{ route("auth.send-login-otp") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                
                // Redirect to OTP verification page after short delay
                setTimeout(() => {
                    window.location.href = '{{ route("auth.verify-otp") }}?phone=' + encodeURIComponent(data.phone_number) + '&type=login';
                }, 1000);
            } else {
                showAlert('error', data.message || '{{ get_phrase("Something went wrong. Please try again.") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', '{{ get_phrase("Network error. Please check your connection.") }}');
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.style.display = 'none';
        });
    });
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        const existingAlert = form.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Add new alert
        form.insertAdjacentHTML('afterend', alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
@endpush

@endsection