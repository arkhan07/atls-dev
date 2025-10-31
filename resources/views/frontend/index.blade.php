@extends('layouts.frontend')
@push('title', get_phrase('Home | ATLS Indonesia'))
@push('meta')@endpush

@push('css')
<script src="{{ asset('assets/frontend/js/mixitup.min.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>

    /* Statistics Cards */
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-number {
        font-size: 3rem;
        font-weight: bold;
        color: #9f2325;
        margin-bottom: 10px;
    }

    /* Region Filter */
    .region-filter {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        padding: 10px 25px;
        border: 2px solid #e9ecef;
        background: white;
        color: #6c757d;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: #9f2325;
        color: white;
        border-color: #9f2325;
    }

    /* Region Cards */
    .region-card {
        background: white;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        margin: 10px;
        border: 1px solid #f0f0f0;
    }
    
    .region-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    
    .region-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
    }
    
    .region-badge {
        display: inline-block;
        padding: 5px 15px;
        background: #ffe6e6;
        color: #9f2325;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 10px;
    }

    /* ATLS Details Section */
    .atls-details {
        background: #9f2325;
        color: white;
        padding: 80px 0;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .feature-icon {
        width: 20px;
        height: 20px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 15px;
        margin-top: 5px;
        flex-shrink: 0;
    }

    /* Refresher Course */
    .refresher-course {
        background: #f8f9fa;
        padding: 80px 0;
    }
    
    .course-illustration {
        width: 100%;
        max-width: 400px;
        height: 300px;
        background: #9f2325;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    /* Gallery Tabs */
    .gallery-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 30px;
    }
    
    .gallery-tab {
        display: inline-block;
        padding: 12px 30px;
        margin-right: 10px;
        cursor: pointer;
        border: none;
        background: none;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .gallery-tab.active {
        color: #9f2325;
        border-bottom-color: #9f2325;
    }
    
    .gallery-content {
        display: none;
    }
    
    .gallery-content.active {
        display: block;
    }
    
    .gallery-item {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 250px;
        margin-bottom: 20px;
        position: relative;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-item:hover {
        transform: scale(1.03);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(159, 35, 37, 0.95), transparent);
        color: white;
        padding: 20px;
        font-weight: 500;
    }

    /* Testimonial */
    .testimonial-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 25px;
        margin: 15px;
    }
    
    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #9f2325;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
    }
    
    .testimonial-stars {
        color: #ffc107;
        margin-top: 5px;
    }

    /* Trusted Companies */
    .trusted-companies-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 30px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .trusted-companies-wrap li {
        width: 150px;
        height: 80px;
        background: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
        padding: 15px;
        transition: all 0.3s ease;
    }
    
    .trusted-companies-wrap li:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .trusted-companies-wrap li img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: grayscale(100%);
        transition: filter 0.3s ease;
    }
    
    .trusted-companies-wrap li:hover img {
        filter: grayscale(0%);
    }

    /* Blog Cards */
    .blog-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        text-decoration: none;
        display: block;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
    }
    
    .blog-image {
        height: 200px;
        background: #9f2325;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        overflow: hidden;
    }
    
    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .blog-badge {
        background: #ffe6e6;
        color: #9f2325;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    /* Scroll Button */
    .scroll-icon-area {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
    }
    
    .scroll-btn {
        width: 50px;
        height: 50px;
        background: #9f2325;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(159, 35, 37, 0.3);
        transition: all 0.3s ease;
    }
    
    .scroll-btn:hover {
        background: #7a1c1e;
        color: white;
        transform: translateY(-3px);
    }
    
    /* Custom Button Colors */
    .btn-danger {
        background-color: #9f2325 !important;
        border-color: #9f2325 !important;
    }
    
    .btn-danger:hover {
        background-color: #7a1c1e !important;
        border-color: #7a1c1e !important;
    }

    /* Swiper Customization */
    .swiper-pagination-bullet-active {
        background: #9f2325;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        color: #9f2325;
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
    }
    
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: #9f2325;
        color: white;
    }
    
    .max-w-600px {
        max-width: 600px;
    }
</style>
@endpush

@section('frontend_layout')
    <div class="header-banner-wrap">
        <!-- Banner slider -->
        <div class="swiper banner-slider">
            <div class="swiper-wrapper">
                @php
                    $homeBanner = json_decode(get_frontend_settings('mother_homepage_banner'), true);
                @endphp

                @if (!empty($homeBanner) && is_array($homeBanner))
                    @foreach ($homeBanner as $banner)
                        <div class="swiper-slide">
                            <div class="banner-slider-wrap" style="background-image: url('{{ asset('uploads/mother_homepage_banner/' . $banner['image']) }}');">
                                <div class="banner-slider-content">
                                    <h1 class="mb-3 lg-title-78px text-white text-capitalize text-center">{{ $banner['title'] }}</h1>
                                    <p class="in-subtitle-16px text-white text-center max-w-621px mx-auto">{{ $banner['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback design when no banners are available --}}
                    <div class="swiper-slide">
                        <div class="banner-slider-wrap" style="background-image: url('{{ asset('assets/frontend/images/home/home-banner1.webp') }}');">
                            <div class="banner-slider-content">
                                <h1 class="mb-3 lg-title-78px text-white text-capitalize text-center"></h1>
                                <p class="in-subtitle-16px text-white text-center max-w-621px mx-auto"></p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
<!-- Region Slider Section -->
@if(isset($regions) && count($regions) > 0)
    @include('components.region-slider', ['regions' => $regions])
@endif

<!-- Sub Division Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
        <!-- Statistics -->
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">378</div>
                    <h6 class="text-muted">Instructor</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">521</div>
                    <h6 class="text-muted">Projects</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">24/7</div>
                    <h6 class="text-muted">Hours of Support</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">23</div>
                    <h6 class="text-muted">Sub Division</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ATLS Details Section -->
<section class="atls-details">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="pe-lg-5">
                    <h2 class="mb-4">ATLS ADVANCED TRAUMA LIFE SUPPORT</h2>
                    <h4 class="mb-4">A Lifesaving Approach to Trauma Management.</h4>
                    <p class="mb-4">
                        ATLS, or Advanced Trauma Life Support, is a globally recognized training program designed to equip healthcare professionals with the knowledge and skills to manage trauma patients effectively, especially in critical situations.
                    </p>
                    
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <p class="mb-0">Prioritize Patient Safety: Focus on identifying and addressing life-threatening injuries first.</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <p class="mb-0">Systematic Approach: Standardized methods for assessment and treatment, ensuring no critical steps are missed.</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <p class="mb-0">Global Standard: Training based on evidence-based practices recognized worldwide.</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <p class="mb-0">Empowering Healthcare Providers: Equipping doctors, nurses, and paramedics with essential trauma management skills.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                        <i class="fas fa-user-md" style="font-size: 5rem; color: #9f2325;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ATLS Refresher Course Section -->
<section class="refresher-course">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="mb-4">Refresh Your Skills with the ATLS Refresher Course</h2>
                <p class="mb-4">
                    In the dynamic and fast-paced world of trauma care, staying updated is critical. The Advanced Trauma Life Support (ATLS) Refresher Course is specifically designed for certified ATLS providers to revisit and enhance their skills.
                </p>
                <p class="mb-0">
                    The ATLS Refresher Course is a condensed training program that revisits the key principles of the Advanced Trauma Life Support system. It focuses on reinforcing knowledge, refreshing technical skills, and updating participants on the latest evidence-based practices in trauma management.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <div class="course-illustration mx-auto">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fas fa-graduation-cap text-white" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trusted Companies Section -->
<section class="mt-5 pt-4">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="in-title-36px text-center text-capitalize max-w-538px mx-auto">
                    We Have Worked with <span class="text-danger">10,000+</span> Trusted Companies
                </h1>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12">
                <ul class="trusted-companies-wrap">
                    <li>
                        <img src="https://logo.clearbit.com/microsoft.com" alt="Microsoft">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/google.com" alt="Google">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/apple.com" alt="Apple">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/amazon.com" alt="Amazon">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/ibm.com" alt="IBM">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/oracle.com" alt="Oracle">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/salesforce.com" alt="Salesforce">
                    </li>
                    <li>
                        <img src="https://logo.clearbit.com/cisco.com" alt="Cisco">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-uppercase text-muted small mb-2">GALLERY</p>
                <h2 class="mb-4">CHECK OUR GALLERY</h2>
            </div>
        </div>
        
        @if(isset($galleries) && count($galleries) > 0)
            <!-- Gallery Tabs -->
            <div class="gallery-tabs">
                @php 
                    $isFirst = true;
                    $categoryMap = [
                        'hotel' => 'Training Events',
                        'restaurant' => 'Medical Conference',
                        'beauty' => 'Workshop',
                        'doctor' => 'Certification',
                        'car' => 'Automotive',
                        'real-estate' => 'Real Estate',
                        'event' => 'Events',
                        'other' => 'Other'
                    ];
                @endphp
                
                @foreach($galleryCategories as $key => $category)
                    @if(isset($galleries[$key]) && count($galleries[$key]) > 0)
                        <button class="gallery-tab {{ $isFirst ? 'active' : '' }}" data-tab="{{ $key }}">
                            {{ $categoryMap[$key] ?? $category }}
                        </button>
                        @php $isFirst = false; @endphp
                    @endif
                @endforeach
            </div>
            
            @php $isFirstContent = true; @endphp
            @foreach($galleryCategories as $key => $category)
                @if(isset($galleries[$key]) && count($galleries[$key]) > 0)
                    <!-- {{ $category }} Gallery -->
                    <div class="gallery-content {{ $isFirstContent ? 'active' : '' }}" id="{{ $key }}">
                        <div class="row g-4">
                            @foreach($galleries[$key]->take(6) as $gallery)
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-item">
                                    <img src="{{ asset('uploads/gallery/' . $gallery->image) }}" 
                                         alt="{{ $gallery->title }}"
                                         onerror="this.src='{{ asset('assets/frontend/images/placeholder.jpg') }}'">
                                    <div class="gallery-overlay">{{ $gallery->title }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @php $isFirstContent = false; @endphp
                @endif
            @endforeach
            
            <div class="text-end mt-4">
                <a href="{{ route('frontend.gallery') }}" class="btn btn-danger">See More</a>
            </div>
        @else
            <!-- Fallback when no galleries exist -->
            <div class="alert alert-info text-center">
                <i class="fi-rr-info-circle me-2"></i>
                {{ get_phrase('No gallery items available at the moment.') }}
            </div>
        @endif
    </div>
</section>

<!-- Team Section -->
@if(isset($teamMembers) && count($teamMembers) > 0)
    @include('components.team-slider', ['teamMembers' => $teamMembers])
@endif

 
    <!-- Start Blog Area -->
    <section>
        <div class="container">
            <div class="row mb-30px">
                <div class="col-12">
                    <h1 class="in-title-36px text-center text-capitalize">{{ get_phrase('Pickup New Updates') }}</h1>
                </div>
            </div>
            <div class="row mb-100px g-4">
                @if (count($blogs) > 0)
                    @foreach ($blogs as $blog)
                        @php
                            $category_name = App\Models\Blog_category::where('id', $blog->category)->first();
                            $usersBlog = App\Models\User::where('id', $blog->user_id)->first();
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('blog.details', ['id' => $blog->id, 'slug' => slugify($blog->title)]) }}" class="mh-blog-link max-w-md-450px mx-auto">
                                <div class="mh-blog-card">
                                    <div class="mh-blog-card-image">
                                        <img src="{{ get_all_image('blog-images/' . $blog->image) }}" alt="">
                                    </div>
                                    <div class="p-20px">
                                        <p class="at-category-badge mb-12px">{{ $category_name->name }}</p>
                                        <div class="mh-blog-titles mb-2 pb-3">
                                            <h4 class="in-title-20px fw-semibold at-text-dark mb-12px">{{ $blog->title }}</h4>
                                            <p class="in-subtitle-16px at-text-secondary">
                                            <p> {{ Str::limit(strip_tags($blog->description), 80) }}</p>
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-3 gap-3">
                                            <div class="d-flex align-items-center gap-10px">
                                                <div class="image-circle-40px">
                                                    <img src="{{ get_all_image('users/' . $usersBlog['image']) }}" alt="">
                                                </div>
                                                <div>
                                                    <h6 class="in-title-16px at-text-dark mb-2">{{ $usersBlog->name }}</h6>
                                                    <p class="in-subtitle-14px lh-1 fw-medium at-text-secondary">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y ') }}</p>
                                                </div>
                                            </div>
                                            <div class="mh-blog-arrow">
                                                <img src="{{asset('image/1.svg')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    </style>
    <!-- Scroll btn -->
    <div class="scroll-icon-area">
        <a href="javascript:;" class="scroll-btn">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>
    <script src="{{ asset('assets/frontend/js/swiper-bundle.min.js') }}"></script>
@endsection

@push('js')
<script>
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {



        // Gallery Tabs
        const galleryTabs = document.querySelectorAll('.gallery-tab');
        const galleryContents = document.querySelectorAll('.gallery-content');

        galleryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                galleryTabs.forEach(t => t.classList.remove('active'));
                galleryContents.forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });

        // Smooth scroll
        const scrollBtn = document.querySelector('.scroll-btn');
        if (scrollBtn) {
            scrollBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollBtn.style.display = 'flex';
                } else {
                    scrollBtn.style.display = 'none';
                }
            });
            
            scrollBtn.style.display = 'none';
        }
    });
</script>
@endpush