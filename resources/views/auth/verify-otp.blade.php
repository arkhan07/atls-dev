{{-- resources/views/auth/verify-otp.blade.php --}}
@extends('layouts.frontend')
@push('title', get_phrase('Verify WhatsApp OTP'))

@section('frontend_layout')
    <style>
        .ua-slider-banner>img {
            height: 600px;
        }
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }
        .otp-input:focus {
            border-color: #25D366;
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }
        .otp-input.filled {
            border-color: #25D366;
            background-color: rgba(37, 211, 102, 0.1);
        }
        .btn-verify-otp {
            background: #25D366;
            border-color: #25D366;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-verify-otp:hover:not(:disabled) {
            background: #128C7E;
            border-color: #128C7E;
            color: white;
            transform: translateY(-1px);
        }
        .btn-verify-otp:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .resend-section {
            text-align: center;
            margin-top: 20px;
        }
        .resend-timer {
            color: #666;
            font-size: 14px;
        }
        .btn-resend {
            background: none;
            border: none;
            color: #25D366;
            text-decoration: underline;
            cursor: pointer;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .btn-resend:hover:not(:disabled) {
            color: #128C7E;
        }
        .btn-resend:disabled {
            color: #ccc;
            cursor: not-allowed;
            text-decoration: none;
        }
        .whatsapp-icon {
            color: #25D366;
            font-size: 48px;
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
        .phone-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #25D366;
        }
        @media (max-width: 576px) {
            .otp-input {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
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
                                <svg class="whatsapp-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                </svg>
                            </div>
                            <h3 class="mb-3 in-title2-24px text-center">{{ get_phrase('WhatsApp Verification') }}</h3>
                            <p class="in-subtitle3-16px lh-base text-center">
                                {{ get_phrase('Enter the 6-digit code sent to') }}<br>
                                <span class="phone-display">+{{ $phoneNumber }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-30px">
                            <form id="otpVerifyForm">
                                @csrf
                                <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                
                                <div class="mb-4">
                                    <label class="form-label ua-form-label mb-3 text-center d-block">{{ get_phrase('OTP Code') }}</label>
                                    <div class="otp-inputs">
                                        @for ($i = 1; $i <= 6; $i++)
                                            <input type="text" 
                                                   class="otp-input" 
                                                   id="otp{{ $i }}" 
                                                   maxlength="1" 
                                                   pattern="[0-9]"
                                                   inputmode="numeric"
                                                   autocomplete="off"
                                                   data-index="{{ $i - 1 }}">
                                        @endfor
                                    </div>
                                    <div class="text-center">
                                        <small class="text-muted">{{ get_phrase('Enter the 6-digit code from WhatsApp') }}</small>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-verify-otp w-100 mb-3" id="verifyBtn" disabled>
                                    <span class="loading-spinner"></span>
                                    {{ get_phrase('Verify Code') }}
                                </button>
                                
                                <div class="resend-section">
                                    <div id="resendTimer" class="resend-timer">
                                        {{ get_phrase('Resend code in') }} <span id="countdown">60</span> {{ get_phrase('seconds') }}
                                    </div>
                                    <button type="button" id="resendBtn" class="btn-resend" disabled>
                                        {{ get_phrase('Resend OTP Code') }}
                                    </button>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <p class="ua-subtitle-16px">
                                        <a href="{{ $type === 'register' ? route('register') : route('auth.phone-form') }}" class="fw-semibold ua-link">
                                            {{ get_phrase('Change Phone Number') }}
                                        </a>
                                    </p>
                                    @if($type === 'login')
                                        <p class="ua-subtitle-16px">
                                            <a href="{{ route('login') }}" class="fw-semibold ua-link">{{ get_phrase('Back to Email Login') }}</a>
                                        </p>
                                    @endif
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
                                                <h1 class="ua-title-36px text-white mb-3">{{ get_phrase('Verify Code') }}</h1>
                                                <p class="in-subtitle-16px text-white ua-slider-subtitle-margin">{{ get_phrase('Enter OTP from WhatsApp') }}</p>
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
    const otpInputs = document.querySelectorAll('.otp-input');
    const form = document.getElementById('otpVerifyForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');
    const countdown = document.getElementById('countdown');
    const spinner = verifyBtn.querySelector('.loading-spinner');
    
    let countdownTime = 60;
    let countdownInterval;
    
    // Start countdown timer
    function startCountdown() {
        countdownTime = 60;
        resendBtn.disabled = true;
        resendTimer.style.display = 'block';
        
        countdownInterval = setInterval(function() {
            countdownTime--;
            countdown.textContent = countdownTime;
            
            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                resendTimer.style.display = 'none';
                resendBtn.disabled = false;
            }
        }, 1000);
    }
    
    // Start initial countdown
    startCountdown();
    
    // Handle OTP input navigation and validation
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            // Only allow digits
            e.target.value = e.target.value.replace(/\D/g, '');
            
            if (e.target.value.length === 1) {
                // Add filled class
                e.target.classList.add('filled');
                
                // Move to next input
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('filled');
            }
            
            // Check if all inputs are filled
            checkOTPComplete();
            
            // Auto-submit when all fields are filled
            if (index === otpInputs.length - 1 && e.target.value.length === 1) {
                const otpCode = Array.from(otpInputs).map(input => input.value).join('');
                if (otpCode.length === 6) {
                    setTimeout(() => form.dispatchEvent(new Event('submit')), 500);
                }
            }
        });
        
        input.addEventListener('keydown', function(e) {
            // Backspace handling
            if (e.key === 'Backspace') {
                if (e.target.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                } else {
                    e.target.value = '';
                    e.target.classList.remove('filled');
                }
                checkOTPComplete();
            }
            
            // Arrow key navigation
            if (e.key === 'ArrowLeft' && index > 0) {
                otpInputs[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });
        
        // Handle paste event
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const pasteValue = paste.replace(/\D/g, '').slice(0, 6);
            
            // Clear all inputs first
            otpInputs.forEach(inp => {
                inp.value = '';
                inp.classList.remove('filled');
            });
            
            // Fill inputs with pasted value
            for (let i = 0; i < pasteValue.length && i < otpInputs.length; i++) {
                otpInputs[i].value = pasteValue[i];
                otpInputs[i].classList.add('filled');
            }
            
            checkOTPComplete();
            
            // Auto-submit if complete
            if (pasteValue.length === 6) {
                setTimeout(() => form.dispatchEvent(new Event('submit')), 500);
            }
        });
    });
    
    function checkOTPComplete() {
        const otpCode = Array.from(otpInputs).map(input => input.value).join('');
        verifyBtn.disabled = otpCode.length !== 6;
    }
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const otpCode = Array.from(otpInputs).map(input => input.value).join('');
        if (otpCode.length !== 6) {
            showAlert('error', '{{ get_phrase("Please enter complete 6-digit OTP code") }}');
            return;
        }
        
        verifyBtn.disabled = true;
        spinner.style.display = 'inline-block';
        
        const formData = new FormData(form);
        formData.append('otp_code', otpCode);
        
        const verifyRoute = '{{ $type }}' === 'register' ? 
            '{{ route("auth.verify-register-otp") }}' : 
            '{{ route("auth.verify-login-otp") }}';
        
        fetch(verifyRoute, {
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
                showAlert('success', data.message);
                
                // Redirect after success
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '{{ route("admin.dashboard") }}';
                    }
                }, 1000);
            } else {
                showAlert('error', data.message || '{{ get_phrase("Invalid OTP code") }}');
                // Clear OTP inputs
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                otpInputs[0].focus();
                checkOTPComplete();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', '{{ get_phrase("Network error. Please try again.") }}');
        })
        .finally(() => {
            verifyBtn.disabled = false;
            spinner.style.display = 'none';
        });
    });
    
    // Handle resend OTP
    resendBtn.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('phone_number', '{{ $phoneNumber }}');
        formData.append('type', '{{ $type }}');
        
        this.disabled = true;
        const originalText = this.textContent;
        this.textContent = '{{ get_phrase("Sending...") }}';
        
        fetch('{{ route("auth.resend-otp") }}', {
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
                showAlert('success', data.message);
                // Clear OTP inputs and restart countdown
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                otpInputs[0].focus();
                checkOTPComplete();
                startCountdown();
            } else {
                showAlert('error', data.message || '{{ get_phrase("Failed to resend OTP") }}');
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', '{{ get_phrase("Network error. Please try again.") }}');
            this.disabled = false;
        })
        .finally(() => {
            this.textContent = originalText;
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
    
    // Focus on first input on load
    otpInputs[0].focus();
});
</script>
@endpush

@endsection