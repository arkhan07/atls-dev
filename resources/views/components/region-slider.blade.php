{{-- resources/views/components/region-slider.blade.php --}}
<section class="region-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="section-title">{{ get_phrase('Our Regional Network') }}</h2>
                <p class="section-subtitle">{{ get_phrase('Connect with our regional offices across the country') }}</p>
            </div>
        </div>

        @if(isset($regions) && count($regions) > 0)
        <div class="region-slider-wrapper">
            <div class="swiper regionSwiper">
                <div class="swiper-wrapper">
                    @foreach($regions as $region)
                    <div class="swiper-slide">
                        <div class="region-card">
                            <div class="region-icon-wrapper">
                                @if($region->icon_image_url)
                                    <img src="{{ $region->icon_image_url }}"
                                         alt="{{ $region->name }}"
                                         class="region-icon"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'region-icon-placeholder\'><i class=\'fas fa-map-marker-alt\'></i></div>';">
                                @else
                                    <div class="region-icon-placeholder">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="region-info">
                                <h4 class="region-name">{{ $region->name }}</h4>

                                <div class="region-actions">
                                    {{-- Link to Region Detail Page --}}
                                    <a href="{{ route('region.detail', $region->slug) }}"
                                       class="btn btn-detail">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Detail Wilayah
                                    </a>

                                    {{-- WhatsApp Button --}}
                                    @if($region->whatsapp)
                                        @php
                                            // Clean WhatsApp number - remove any non-numeric characters except +
                                            $cleanWhatsapp = preg_replace('/[^0-9+]/', '', $region->whatsapp);
                                            // If starts with 0, replace with 62
                                            if (substr($cleanWhatsapp, 0, 1) === '0') {
                                                $cleanWhatsapp = '62' . substr($cleanWhatsapp, 1);
                                            }
                                            // Remove + if exists
                                            $cleanWhatsapp = str_replace('+', '', $cleanWhatsapp);
                                        @endphp
                                        <a href="https://wa.me/{{ $cleanWhatsapp }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="btn btn-whatsapp">
                                            <i class="fab fa-whatsapp me-2"></i>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation -->
                <div class="swiper-button-next region-nav-next"></div>
                <div class="swiper-button-prev region-nav-prev"></div>

                <!-- Pagination -->
                <div class="swiper-pagination region-pagination"></div>
            </div>
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fi-rr-info me-2"></i>
            {{ get_phrase('No regional offices available at the moment') }}
        </div>
        @endif
    </div>
</section>

<style>
/* Region Section */
.region-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #f8f9fa 100%);
    position: relative;
    overflow: hidden;
}

.section-title {
    font-size: 18px;
    font-weight: 400;
    color: #2c3e50;
    margin-bottom: 1rem;
    position: relative;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 0;
}

/* Region Slider */
.region-slider-wrapper {
    padding: 20px 0;
    position: relative;
}

.regionSwiper {
    padding: 20px 10px 60px;
}

/* Region Card */
.region-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.region-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.region-icon-wrapper {
    width: 120px;
    height: 120px;
    margin-bottom: 20px;
    position: relative;
}

.region-icon {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
}

.region-card:hover .region-icon {
    transform: scale(1.1);
}

.region-icon-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #9f2325 0%, #c92a2c 100%);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.region-card:hover .region-icon-placeholder {
    box-shadow: 0 6px 25px rgba(159, 35, 37, 0.4);
    transform: scale(1.05);
}

.region-icon-placeholder i {
    font-size: 3.5rem;
    color: white;
}

.region-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
}

.region-name {
    font-size: 18px;
    font-weight: 400;
    color: #2c3e50;
    margin-bottom: 20px;
}

.region-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.btn-detail {
    width: 100%;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 400;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #9f2325;
    color: white;
    border: none;
}

.btn-detail:hover {
    background: #7d1c1e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(159, 35, 37, 0.4);
}

.btn-whatsapp {
    width: 100%;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 400;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #25D366;
    color: white;
    border: none;
}

.btn-whatsapp:hover {
    background: #128C7E;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
}

.btn-whatsapp i,
.btn-detail i {
    font-size: 1.2rem;
}

.no-whatsapp-info {
    width: 100%;
    padding: 10px 15px;
    background: #f8f9fa;
    color: #6c757d;
    border-radius: 10px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Swiper Navigation */
.region-nav-next,
.region-nav-prev {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    color: #9f2325;
}

.region-nav-next:after,
.region-nav-prev:after {
    font-size: 20px;
    font-weight: bold;
}

.region-nav-next:hover,
.region-nav-prev:hover {
    background: #9f2325;
    color: white;
}

/* Swiper Pagination */
.region-pagination {
    bottom: 20px !important;
}

.region-pagination .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: #9f2325;
    opacity: 0.3;
}

.region-pagination .swiper-pagination-bullet-active {
    opacity: 1;
    width: 30px;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }

    .region-icon-wrapper {
        width: 100px;
        height: 100px;
    }

    .region-icon-placeholder i {
        font-size: 3rem;
    }

    .region-name {
        font-size: 1.3rem;
    }

    .btn-whatsapp,
    .btn-detail {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Region Swiper
    const regionSwiperEl = document.querySelector('.regionSwiper');
    if (regionSwiperEl) {
        // Count slides
        const slideCount = regionSwiperEl.querySelectorAll('.swiper-slide').length;

        // Only enable loop if there are enough slides (more than max slidesPerView)
        const enableLoop = slideCount > 4;

        var regionSwiper = new Swiper('.regionSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: enableLoop,
            autoplay: enableLoop ? {
                delay: 4000,
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: '.region-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.region-nav-next',
                prevEl: '.region-nav-prev',
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

{{-- Usage in homepage (resources/views/frontend/index.blade.php) --}}
{{-- @include('components.region-slider', ['regions' => $regions]) --}}
