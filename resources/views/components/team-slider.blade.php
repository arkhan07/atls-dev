{{-- resources/views/components/team-slider.blade.php --}}
<section class="team-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="section-title">Tim Kami</h2>
                <p class="section-subtitle">Berkenalan dengan tim profesional kami</p>
            </div>
        </div>

        <!-- Team Members Slider -->
        @if(isset($teamMembers) && count($teamMembers) > 0)
        <div class="team-slider-wrapper">
            <div class="swiper team-swiper">
                <div class="swiper-wrapper">
                    @foreach($teamMembers as $member)
                        <div class="swiper-slide">
                            <div class="team-member-card">
                                <div class="member-image-wrapper">
                                    @if($member->image)
                                        <img src="{{ $member->image_url }}"
                                             alt="{{ $member->name }}"
                                             class="member-image"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image-placeholder\'><i class=\'fi-rr-user\'></i></div>';">
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fi-rr-user"></i>
                                        </div>
                                    @endif
                                    <div class="member-overlay">
                                        <div class="social-links">
                                            @if($member->social_links && is_array($member->social_links))
                                                @foreach($member->social_links as $platform => $url)
                                                    @if($url)
                                                        <a href="{{ $url }}" target="_blank" class="social-link" title="{{ ucfirst($platform) }}">
                                                            <i class="fab fa-{{ $platform }}"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="member-info">
                                    <h4 class="member-name">{{ $member->name }}</h4>
                                    <p class="member-position">{{ $member->position }}</p>
                                    @if($member->category)
                                        <span class="member-category">{{ $member->category }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination team-pagination"></div>
                <div class="swiper-button-next team-nav-next"></div>
                <div class="swiper-button-prev team-nav-prev"></div>
            </div>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fi-rr-info me-2"></i>
            Belum ada data tim
        </div>
        @endif
    </div>
</section>

<style>
/* Team Section Styles */
.team-section {
    background: #ffffff;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 0;
}

/* Team Slider */
.team-slider-wrapper {
    padding: 20px 0;
    position: relative;
}

.team-swiper {
    padding: 20px 10px 60px;
}

/* Team Member Cards */
.team-member-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.team-member-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
}

.member-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 320px;
}

.member-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.team-member-card:hover .member-image {
    transform: scale(1.08);
}

.no-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.no-image-placeholder i {
    font-size: 4rem;
    color: white;
}

.member-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #736d6e33;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.team-member-card:hover .member-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 12px;
}

.social-link {
    width: 45px;
    height: 45px;
    background: white;
    color: #667eea;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-link:hover {
    background: #764ba2;
    color: white;
    transform: scale(1.15) rotate(10deg);
}

.member-info {
    padding: 1.5rem;
    text-align: center;
}

.member-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.member-position {
    color: #667eea;
    font-weight: 500;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.member-category {
    background: #9f2325;
    color: white;
    padding: 0.35rem 0.85rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
}

/* Swiper Navigation */
.team-nav-next,
.team-nav-prev {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    color: #667eea;
}

.team-nav-next:after,
.team-nav-prev:after {
    font-size: 18px;
    font-weight: bold;
}

.team-nav-next:hover,
.team-nav-prev:hover {
    background: #667eea;
    color: white;
}

/* Swiper Pagination */
.team-pagination {
    bottom: 20px !important;
}

.team-pagination .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #667eea;
    opacity: 0.3;
}

.team-pagination .swiper-pagination-bullet-active {
    opacity: 1;
    width: 28px;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .member-image-wrapper {
        height: 280px;
    }
    
    .member-name {
        font-size: 1.1rem;
    }
    
    .member-position {
        font-size: 0.9rem;
    }
    
    .social-link {
        width: 38px;
        height: 38px;
        font-size: 0.95rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for team slider
    const teamSwiperEl = document.querySelector('.team-swiper');
    if (teamSwiperEl) {
        // Count slides
        const slideCount = teamSwiperEl.querySelectorAll('.swiper-slide').length;

        // Only enable loop if there are enough slides (more than max slidesPerView)
        const enableLoop = slideCount > 4;

        const teamSwiper = new Swiper('.team-swiper', {
            slidesPerView: 1,
            spaceBetween: 25,
            loop: enableLoop,
            autoplay: enableLoop ? {
                delay: 3500,
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: '.team-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.team-nav-next',
                prevEl: '.team-nav-prev',
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 25,
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                }
            },
            // Prevent issues when slide count is low
            watchOverflow: true,
            observer: true,
            observeParents: true,
        });
    }
});
</script>

{{-- Usage in your main view file (e.g., resources/views/frontend/about.blade.php) --}}
{{--
@extends('layouts.app')

@section('content')
    @include('components.team-slider', [
        'teamMembers' => $teamMembers,
        'categories' => $categories
    ])
@endsection
--}}

{{-- Controller method to pass data (add to FrontendController or create TeamController) --}}
{{--
public function about()
{
    $teamMembers = TeamMember::active()->ordered()->get();
    $categories = TeamMember::select('category')
        ->distinct()
        ->whereNotNull('category')
        ->where('is_active', true)
        ->pluck('category');

    return view('frontend.about', compact('teamMembers', 'categories'));
}
--}}