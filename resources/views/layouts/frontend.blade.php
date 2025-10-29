<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.seo')
    @include('layouts.include_top')
    @stack('css')
    
</head>
@if(!empty($directory) && $directory == 'beauty')
  <body class="beauty-details-body">
@elseif((!empty($directory) && $directory == 'car'))
 <body class="car-details-body">
@elseif((!empty($directory) && $directory == 'hotel'))
 <body class="hotel-details-body">
@elseif((!empty($directory) && $directory == 'real-estate'))
 <body class="real-estate-details-body">
@elseif((!empty($directory) && $directory == 'restaurant'))
 <body class="restaurant-details-body">
@else
<body>

@endif

    <!-- Start Header Top -->
    <section class="header-top-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header-top-area d-flex align-items-center justify-content-between flex-wrap">
                        <div class="header-number-location d-flex align-items-center flex-wrap">
                            <svg class="d-none" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_99_4740" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                <path d="M20 0H0V20H20V0Z" fill="white"/>
                                </mask>
                                <g mask="url(#mask0_99_4740)">
                                <path d="M17.67 16.1905L16.1137 17.7468C16.1137 17.7468 12.0715 19.4792 6.29557 13.7033C0.519687 7.9274 2.2521 3.88511 2.2521 3.88511L3.80832 2.32888C3.97381 2.16326 4.17296 2.03515 4.39229 1.95324C4.61162 1.87133 4.846 1.83752 5.07953 1.85412C5.31307 1.87072 5.54031 1.93733 5.74585 2.04944C5.95139 2.16155 6.13043 2.31653 6.27082 2.50389L7.75634 4.48438C7.9921 4.79901 8.10647 5.18813 8.07843 5.5803C8.0504 5.97247 7.88185 6.34136 7.60372 6.61925L6.29498 7.92799C6.29498 7.92799 6.29498 9.08293 8.60486 11.3928C10.9147 13.7027 12.0697 13.7027 12.0697 13.7027L13.3778 12.3945C13.6558 12.1164 14.0249 11.9478 14.4172 11.9199C14.8095 11.892 15.1987 12.0065 15.5133 12.2425L17.4932 13.7274C17.6808 13.8677 17.836 14.0467 17.9483 14.2522C18.0607 14.4577 18.1275 14.685 18.1442 14.9186C18.161 15.1522 18.1273 15.3867 18.0455 15.6062C17.9637 15.8256 17.8356 16.0249 17.67 16.1905Z" fill="#FF736A"/>
                                </g>
                                </svg>
                            <a href="tel:" class="location">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <mask id="mask0_99_4740" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                    <path d="M20 0H0V20H20V0Z" fill="white"/>
                                    </mask>
                                    <g mask="url(#mask0_99_4740)">
                                    <path d="M17.67 16.1905L16.1137 17.7468C16.1137 17.7468 12.0715 19.4792 6.29557 13.7033C0.519687 7.9274 2.2521 3.88511 2.2521 3.88511L3.80832 2.32888C3.97381 2.16326 4.17296 2.03515 4.39229 1.95324C4.61162 1.87133 4.846 1.83752 5.07953 1.85412C5.31307 1.87072 5.54031 1.93733 5.74585 2.04944C5.95139 2.16155 6.13043 2.31653 6.27082 2.50389L7.75634 4.48438C7.9921 4.79901 8.10647 5.18813 8.07843 5.5803C8.0504 5.97247 7.88185 6.34136 7.60372 6.61925L6.29498 7.92799C6.29498 7.92799 6.29498 9.08293 8.60486 11.3928C10.9147 13.7027 12.0697 13.7027 12.0697 13.7027L13.3778 12.3945C13.6558 12.1164 14.0249 11.9478 14.4172 11.9199C14.8095 11.892 15.1987 12.0065 15.5133 12.2425L17.4932 13.7274C17.6808 13.8677 17.836 14.0467 17.9483 14.2522C18.0607 14.4577 18.1275 14.685 18.1442 14.9186C18.161 15.1522 18.1273 15.3867 18.0455 15.6062C17.9637 15.8256 17.8356 16.0249 17.67 16.1905Z" fill="#6C1CFF"/>
                                    </g>
                                    </svg>
                                                                       
                                <span>{{get_settings('phone')}}</span>
                                </a>
                            <p class="location">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.1833 7.04166C16.3083 3.19166 12.95 1.45833 9.99996 1.45833C9.99996 1.45833 9.99996 1.45833 9.99162 1.45833C7.04996 1.45833 3.68329 3.18333 2.80829 7.03333C1.83329 11.3333 4.46662 14.975 6.84996 17.2667C7.73329 18.1167 8.86662 18.5417 9.99996 18.5417C11.1333 18.5417 12.2666 18.1167 13.1416 17.2667C15.525 14.975 18.1583 11.3417 17.1833 7.04166ZM9.99996 11.2167C8.54996 11.2167 7.37496 10.0417 7.37496 8.59166C7.37496 7.14166 8.54996 5.96666 9.99996 5.96666C11.45 5.96666 12.625 7.14166 12.625 8.59166C12.625 10.0417 11.45 11.2167 9.99996 11.2167Z" fill="#6C1CFF"/>
                                    </svg>
                                <span>{{get_settings('address')}}</span>
                            </p>
                        </div>
                        <ul class="header-social-list d-flex align-items-center flex-wrap">
                            <li><a href="{{get_settings('facebook')}}" target="_Blank">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.902 9.00006C18.902 4.02953 14.6706 0.00012207 9.45099 0.00012207C4.23135 0.00012207 0 4.02953 0 9.00006C0 13.4921 3.45607 17.2155 7.97427 17.8907V11.6016H5.57461V9.00006H7.97427V7.01726C7.97427 4.76165 9.38528 3.51572 11.5441 3.51572C12.5778 3.51572 13.6596 3.6915 13.6596 3.6915V5.90633H12.4679C11.2939 5.90633 10.9277 6.60014 10.9277 7.31257V9.00006H13.5489L13.1299 11.6016H10.9277V17.8907C15.4459 17.2155 18.902 13.4921 18.902 9.00006Z" fill="white"/>
                                </svg>                                    
                            </a></li>
                            <li><a href="{{get_settings('twitter')}}" target="_Blank">
                                <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_8_237)">
                                    <path d="M11.5246 7.63303L18.4561 0.0529327H16.8135L10.795 6.63463L5.98794 0.0529327H0.443604L7.71277 10.0056L0.443604 17.9545H2.08623L8.44201 11.004L13.5186 17.9545H19.0629L11.5242 7.63303H11.5246ZM9.27482 10.0933L8.5383 9.10225L2.67809 1.21624H5.20107L9.93033 7.5805L10.6668 8.57156L16.8143 16.8441H14.2913L9.27482 10.0937V10.0933Z" fill="white"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_8_237">
                                    <rect width="19.0285" height="17.9016" fill="white" transform="translate(0.239258 0.0529327)"/>
                                    </clipPath>
                                    </defs>
                                </svg>                                    
                            </a></li>
                            <li><a href="{{get_settings('linkedin')}}" target="_Blank">
                                <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_8_234)">
                                    <path d="M17.6483 0H1.54101C0.769421 0 0.145508 0.580074 0.145508 1.29726V16.6991C0.145508 17.4163 0.769421 17.9999 1.54101 17.9999H17.6483C18.4199 17.9999 19.0475 17.4163 19.0475 16.7026V1.29726C19.0475 0.580074 18.4199 0 17.6483 0ZM5.75334 15.3386H2.94758V6.74644H5.75334V15.3386ZM4.35046 5.57574C3.44966 5.57574 2.72238 4.88317 2.72238 4.02888C2.72238 3.17459 3.44966 2.48201 4.35046 2.48201C5.24757 2.48201 5.97485 3.17459 5.97485 4.02888C5.97485 4.87965 5.24757 5.57574 4.35046 5.57574ZM16.2528 15.3386H13.4507V11.162C13.4507 10.1671 13.4323 8.88392 11.9925 8.88392C10.5342 8.88392 10.3127 9.97025 10.3127 11.0917V15.3386H7.51433V6.74644H10.202V7.92065H10.2389C10.6117 7.24565 11.5273 6.53199 12.8896 6.53199C15.7286 6.53199 16.2528 8.31088 16.2528 10.6241V15.3386Z" fill="white"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_8_234">
                                    <rect width="18.902" height="17.9999" fill="white" transform="translate(0.145508)"/>
                                    </clipPath>
                                    </defs>
                                </svg>                                    
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Header Top -->

    
    @if(!empty($directory))
        @include('layouts.'.$directory.'.header')
    @else
        @include('layouts.header')
    @endif
 
    @yield('frontend_layout')

    @if(!empty($directory))
        @include('layouts.'. $directory . '.footer')
    @else
        @include('layouts.footer')
    @endif

    @include('layouts.include_bottom')
    <!-- toster file -->
    @include('layouts.toaster')
    @stack('js')
    
    @if (addon_status('live_chat') == 1)
    {!!get_settings('tawk_live_chat_code')!!}
    @endif

</body>
</html>
