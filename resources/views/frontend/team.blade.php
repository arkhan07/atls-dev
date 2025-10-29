@extends('layouts.frontend')
@section('title', get_phrase('Our Team'))

@section('content')

<!-- Page Header -->
<section class="page-header-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">{{ get_phrase('Our Team') }}</h1>
                <p class="lead mb-0">{{ get_phrase('Meet our professional team members') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section py-5 bg-light">
    <div class="container">

        <!-- Team Members Slider -->
        @if(isset($teamMembers) && (is_array($teamMembers) ? count($teamMembers) > 0 : $teamMembers->count() > 0))
        <div class="team-slider-container">
            <div class="swiper teamSwiper">
                <div class="swiper-wrapper">
                    @foreach($teamMembers as $member)
                    <div class="swiper-slide">
                        <div class="team-member-card">
                            <div class="member-image-wrapper">
                                @if($member->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->image))
                                    <img src="{{ asset('storage/' . $member->image) }}" 
                                         alt="{{ $member->name }}" 
                                         class="member-image">
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
                
                <!-- Navigation -->
                <div class="swiper-button-next team-nav-next"></div>
                <div class="swiper-button-prev team-nav-prev"></div>
                
                <!-- Pagination -->
                <div class="swiper-pagination team-pagination"></div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-info text-center">
                    <i class="fi-rr-info me-2"></i>
                    {{ get_phrase('No team members found') }}
                </div>
            </div>
        </div>
        @endif
        
    </div>
</section>

<style>
/* Page Header */
.page-header-section {
    position: relative;
}

/* Team Slider */
.team-slider-container {
    padding: 20px 0;
    position: relative;
}

.teamSwiper {
    padding: 20px 10px 60px;
}

/* Team Member Card */
.team-member-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    height: 100%;
}

.team-member-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.15);
}

.member-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 350px;
}

.member-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.team-member-card:hover .member-image {
    transform: scale(1.1);
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
    background: rgba(102, 126, 234, 0.93);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.team-member-card:hover .member-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 50px;
    height: 50px;
    background: white;
    color: #667eea;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.social-link:hover {
    background: #764ba2;
    color: white;
    transform: scale(1.2) rotate(10deg);
}

.member-info {
    padding: 25px;
    text-align: center;
}

.member-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.member-position {
    color: #667eea;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 15px;
}

.member-category {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}

/* Swiper Navigation */
.team-nav-next,
.team-nav-prev {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    color: #667eea;
}

.team-nav-next:after,
.team-nav-prev:after {
    font-size: 20px;
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
    width: 12px;
    height: 12px;
    background: #667eea;
    opacity: 0.3;
}

.team-pagination .swiper-pagination-bullet-active {
    opacity: 1;
    width: 30px;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .member-image-wrapper {
        height: 300px;
    }
    
    .member-name {
        font-size: 1.2rem;
    }
    
    .member-position {
        font-size: 0.9rem;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .team-nav-next,
    .team-nav-prev {
        width: 40px;
        height: 40px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Team Swiper
    var teamSwiper = new Swiper('.teamSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
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
        }
    });
});
</script>

@endsection
