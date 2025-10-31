@extends('layouts.frontend')
@push('title', $region->meta_title ?: $region->name)
@push('meta')
    @if($region->meta_description)
        <meta name="description" content="{{ $region->meta_description }}">
    @endif
@endpush

@push('css')
<!-- Font Awesome 6 (versi terbaru) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Pricing Card Style - Similar to pricing.blade.php */
    .at-shadow-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .at-shadow-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .at-shadow-card.active {
        background: #a02526;
        color: white;
    }
    
    .sml-radio-iconbox {
        width: 60px;
        height: 60px;
        background: rgba(37, 99, 235, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .at-shadow-card.active .sml-radio-iconbox {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .icon-color {
        color: #2563eb;
    }
    
    .at-shadow-card.active .icon-color {
        color: white;
    }
    
    .at-border-bottom {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .at-shadow-card.active .at-border-bottom {
        border-bottom-color: rgba(255, 255, 255, 0.2);
    }
    
    .at-check-listitem {
        position: relative;
        padding-left: 28px;
        margin-bottom: 8px;
    }
    
    .at-check-listitem:before {
        position: absolute;
        left: 0;
        color: #10b981;
        font-weight: bold;
        font-size: 18px;
    }
    
    .at-shadow-card.active .at-check-listitem:before {
        color: white;
    }
    
    .theme-btn1 {
        background: #2563eb;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
    }
    
    .theme-btn1:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-2px);
    }
    
    .at-btn-white {
        background: white;
        color: #2563eb;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .at-btn-white:hover {
        background: #f3f4f6;
        color: #2563eb;
    }
    
    .quota-badge {
        display: inline-block;
        background: #10b981;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .at-shadow-card.active .quota-badge {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .region-banner-img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .region-info-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    
    .sidebar-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        position: sticky;
        top: 20px;
    }
    
    .stats-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .stats-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('frontend_layout')
<!-- Start Page Content -->
<section class="mt-5 mb-5">
    <div class="container">
        <!-- Banner -->
        @if($region->banner_image)
            <div class="row mb-4">
                <div class="col-12">
                    <img src="{{ $region->banner_image_url }}" 
                         alt="{{ $region->name }}" 
                         class="region-banner-img">
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Region Info -->
                <div class="region-info-card">
                    @if($region->page && $region->page->content)
                        {!! $region->page->content !!}
                    @else
                        <h1 class="mb-3">{{ $region->name }}</h1>
                        <p class="lead">Selamat datang di wilayah {{ $region->name }}.</p>
                    @endif
                </div>

                <!-- Packages Section -->
                @if($packages && $packages->count() > 0)
                    <div class="mt-4">
                        <h1 class="in-title-3 mb-32 mt-2">{{ get_phrase('Paket ATLS Tersedia di') }} {{ $region->name }}</h1>
                        <div class="row row-28 mb-90 justify-content-start">
                            @foreach($packages as $key => $package)
                            <div class="col-lg-6 col-md-6">
                                <div class="at-shadow-card eShadow {{ $key == 0 ? 'active' : '' }}">
                                    <div class="d-flex flex-column h-100 justify-content-between">
                                        <div>
                                            <!-- Icon -->
                                            <div class="sml-radio-iconbox mb-3">
                                                <i class="fa-solid fa-ticket"></i>
                                            </div>
                                            
                                            <!-- Title -->
                                            <h4 class="in-title-4 mb-1 {{ $key == 0 ? 'text-white' : '' }}">{{ $package->title }}</h4>
                                            
                                            <!-- Quota Badge -->
                                            <span class="quota-badge mb-3 d-inline-block">
                                                <i class="fas fa-users"></i> {{ $package->remaining_quota }}/{{ $package->quota }} {{ get_phrase('Slot Available') }}
                                            </span>
                                            
                                            <!-- Price -->
                                            <div class="d-flex align-items-center pb-3 mb-3 at-border-bottom">
                                                <h1 class="in-title-1 {{ $key == 0 ? 'text-white' : '' }}">{{ $package->formatted_price }}</h1>
                                                <p class="in-subtitle-1 fw-medium {{ $key == 0 ? 'text-white' : '' }}">/ {{ get_phrase('participant') }}</p>
                                            </div>
                                            
                                            <!-- Package Details -->
                                            <ul class="d-flex flex-column gap-12px mb-4">
                                                <li class="at-check-listitem {{ $key == 0 ? 'text-white' : '' }}">
                                                    <i class="fas fa-calendar me-2"></i>{{ $package->date_range }}
                                                </li>
                                                <li class="at-check-listitem {{ $key == 0 ? 'text-white' : '' }}">
                                                    <i class="fas fa-clock me-2"></i>{{ $package->days }} - {{ $package->time }}
                                                </li>
                                                <li class="at-check-listitem {{ $key == 0 ? 'text-white' : '' }}">
                                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $package->location }}
                                                </li>
                                                <li class="at-check-listitem {{ $key == 0 ? 'text-white' : '' }}">
                                                    <i class="fas fa-user-tie me-2"></i>{{ $package->contact_name }} - {{ $package->contact_phone }}
                                                </li>
                                            </ul>
                                            
                                            <!-- Registration Progress -->
                                            @if($package->registration_percentage > 0)
                                            <div class="mb-3">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar {{ $package->registration_percentage >= 80 ? 'bg-danger' : 'bg-success' }}" 
                                                         style="width: {{ $package->registration_percentage }}%"></div>
                                                </div>
                                                <small class="{{ $key == 0 ? 'text-white' : 'text-muted' }}">
                                                    {{ $package->registration_count }} {{ get_phrase('registered') }} ({{ number_format($package->registration_percentage, 0) }}%)
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Action Button -->
                                        <div class="mt-3">
                                            @if($package->remaining_quota > 0)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $package->contact_phone) }}?text=Halo, saya ingin mendaftar paket {{ urlencode($package->title) }} di {{ $region->name }}" 
                                                   target="_blank"
                                                   class="{{ $key == 0 ? 'btn at-btn-white' : 'theme-btn1' }} w-100 text-center">
                                                    <i class="fab fa-whatsapp me-2"></i>{{ get_phrase('Daftar Sekarang') }}
                                                </a>
                                            @else
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="fas fa-times-circle me-2"></i>{{ get_phrase('Quota Full') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ get_phrase('Belum ada paket ATLS yang tersedia untuk wilayah') }} {{ $region->name }} {{ get_phrase('saat ini.') }}
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card">
                    @if($region->icon_image)
                        <div class="text-center mb-3">
                            <img src="{{ $region->icon_image_url }}" 
                                 alt="{{ $region->name }}" 
                                 class="img-fluid"
                                 style="max-height: 100px;">
                        </div>
                    @endif
                    
                    <h5 class="text-center mb-3">{{ $region->name }}</h5>
                    
                    <a href="{{ $region->whatsapp_link }}" 
                       class="btn btn-success btn-lg w-100 mb-4"
                       target="_blank">
                        <i class="fab fa-whatsapp me-2"></i> {{ get_phrase('Hubungi WhatsApp') }}
                    </a>

                    @if($packages && $packages->count() > 0)
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">{{ get_phrase('Statistik Paket') }}</h6>
                        <div class="stats-item">
                            <span class="text-muted">{{ get_phrase('Total Paket:') }}</span>
                            <strong>{{ $packages->count() }}</strong>
                        </div>
                        <div class="stats-item">
                            <span class="text-muted">{{ get_phrase('Paket Tersedia:') }}</span>
                            <strong class="text-success">{{ $packages->count() }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Page Content -->

@endsection

@push('js')
<script>
    'use strict';
    // Add any custom JavaScript here if needed
</script>
@endpush