{{-- resources/views/auth/login.blade.php - Updated dengan opsi WhatsApp OTP --}}
@extends('layouts.frontend')
@push('title', get_phrase('Login'))
@push('meta')@endpush
@section('frontend_layout')
    <style>
        .ua-slider-banner>img {
            height: 600px;
        }
        .btn-whatsapp-login {
            background: #25D366;
            border-color: #25D366;
            color: white;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .btn-whatsapp-login:hover {
            background: #128C7E;
            border-color: #128C7E;
            color: white;
            transform: translateY(-1px);
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            color: #666;
            font-size: 14px;
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
                            <h3 class="mb-3 in-title2-24px">{{ get_phrase('Get Started Now') }}</h3>
                            <p class="in-subtitle3-16px lh-base">{{ get_phrase('Enter your credentials to access your account') }}</p>
                        </div>
                        <div class="mb-30px">
                            {{-- WhatsApp Login Button - hanya tampil jika diaktifkan --}}
                            @if(get_settings('whatsapp_otp_enabled') == 1)
                            <a href="{{ route('auth.phone-form') }}" class="btn btn-whatsapp-login w-100">
                                <svg class="whatsapp-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                </svg>
                                {{ get_phrase('Login with WhatsApp') }}
                            </a>
                            
                            <div class="divider">
                                <span>{{ get_phrase('atau') }}</span>
                            </div>
                            @endif
                            
                            <!-- Regular Email Login Form -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="user-email" class="form-label ua-form-label mb-3">{{ get_phrase('Email') }}</label>
                                    <input type="email" class="form-control ua-form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="username@gmail.com" autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-20px">
                                    <div class="d-flex align-items-center justify-content-between column-gap-2 row-gap-1 flex-wrap mb-3">
                                        <label for="password" class="form-label ua-form-label">{{ get_phrase('Password') }}<span>*</span></label>
                                        <a href="{{ route('password.request') }}" class="ua-subtitle-14px ua-link">{{ get_phrase('Forget your password') }} ?</a>
                                    </div>
                                    <div class="at-password-group">
                                        <div class="toggle-icons">
                                            <img src="{{ asset('assets/frontend/images/icons/eye-slash-20.svg') }}" class="password-icon" toggle=".password-field1" alt="">
                                            <img src="{{ asset('assets/frontend/images/icons/eye-show-20.svg') }}" class="password-icon d-none" toggle=".password-field1" alt="">
                                        </div>
                                        <input type="password" class="form-control ua-form-control password-field1 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password" placeholder="Enter password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn ua-btn-dark w-100 mb-3">{{ get_phrase('Login') }}</button>
                                <p class="ua-subtitle-16px text-center">{{ get_phrase('Don\'t an account') }}? <a href="{{ route('register') }}" class="fw-semibold ua-link">{{ get_phrase('Sign up') }}</a></p>
                                
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
                                                <h1 class="ua-title-36px text-white mb-3"></h1>
                                                <p class="in-subtitle-16px text-white ua-slider-subtitle-margin"></p>

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

@endsection