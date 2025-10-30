@extends('layouts.app')

@section('title', $region->meta_title ?: $region->name)
@section('meta_description', $region->meta_description)

@section('content')
<style>
    .pricing-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .pricing-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .pricing-price {
        font-size: 2rem;
        font-weight: bold;
        color: #2563eb;
    }
    .pricing-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(37, 99, 235, 0.9);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .quota-progress {
        height: 8px;
        border-radius: 10px;
    }
    .whatsapp-btn {
        background: #25D366;
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .whatsapp-btn:hover {
        background: #128C7E;
        color: white;
        transform: scale(1.05);
    }
</style>

<div class="container py-5">
    @if($region->banner_image)
        <div class="region-banner mb-4">
            <img src="{{ $region->banner_image_url }}" 
                 alt="{{ $region->name }}" 
                 class="img-fluid w-100 rounded"
                 style="max-height: 400px; object-fit: cover;">
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            @if($region->page && $region->page->content)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        {!! $region->page->content !!}
                    </div>
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h1>{{ $region->name }}</h1>
                        <p class="lead">Selamat datang di wilayah {{ $region->name }}.</p>
                    </div>
                </div>
            @endif

            <!-- Packages Section -->
            @if($packages && $packages->count() > 0)
                <div class="mt-5">
                    <h2 class="mb-4">Paket ATLS Tersedia di {{ $region->name }}</h2>
                    <div class="row g-4">
                        @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="card pricing-card h-100">
                                <div class="position-relative">
                                    @if($package->image)
                                        <img src="{{ asset('uploads/packages/' . $package->image) }}" 
                                             class="pricing-image" 
                                             alt="{{ $package->title }}">
                                    @else
                                        <div class="pricing-image bg-gradient d-flex align-items-center justify-content-center" 
                                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fas fa-box-open fa-4x text-white opacity-50"></i>
                                        </div>
                                    @endif
                                    <div class="pricing-badge">
                                        <i class="fas fa-users"></i> {{ $package->remaining_quota }} Tersisa
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-3">{{ $package->title }}</h5>
                                    
                                    <ul class="list-unstyled small text-muted mb-3 flex-grow-1">
                                        <li class="mb-2">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            {{ $package->date_range }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            {{ $package->days }} - {{ $package->time }}
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            {{ $package->location }}
                                            @if($package->maps_link)
                                                <a href="{{ $package->maps_link }}" target="_blank" class="text-decoration-none ms-1">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-user-tie text-primary me-2"></i>
                                            {{ $package->user->name ?? 'Agent' }}
                                        </li>
                                    </ul>

                                    <!-- Quota Progress -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>{{ $package->registration_count }}/{{ $package->quota }} Terdaftar</span>
                                            <span>{{ number_format($package->registration_percentage, 0) }}%</span>
                                        </div>
                                        <div class="progress quota-progress">
                                            <div class="progress-bar {{ $package->registration_percentage >= 80 ? 'bg-danger' : 'bg-success' }}" 
                                                 style="width: {{ $package->registration_percentage }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="text-center mb-3">
                                        <div class="pricing-price">{{ $package->formatted_price }}</div>
                                        <small class="text-muted">per peserta</small>
                                    </div>

                                    <!-- Daftar Button -->
                                    @if($package->remaining_quota > 0)
                                        <a href="https://wa.me/{{ $package->user->phone ?? $package->contact_phone }}?text=Halo, saya ingin mendaftar untuk paket {{ urlencode($package->title) }} di {{ $region->name }}" 
                                           target="_blank" 
                                           class="btn whatsapp-btn w-100 btn-lg">
                                            <i class="fab fa-whatsapp me-2"></i>Daftar Sekarang
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100 btn-lg" disabled>
                                            <i class="fas fa-times-circle me-2"></i>Kuota Penuh
                                        </button>
                                    @endif
                                </div>

                                @if($package->description)
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ Str::limit($package->description, 100) }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada paket ATLS yang tersedia untuk wilayah {{ $region->name }} saat ini.
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    @if($region->icon_image)
                        <img src="{{ $region->icon_image_url }}" 
                             alt="{{ $region->name }}" 
                             class="img-fluid mb-3"
                             style="max-height: 100px;">
                    @endif
                    
                    <h5 class="mb-3">{{ $region->name }}</h5>
                    
                    <a href="{{ $region->whatsapp_link }}" 
                       class="btn btn-success btn-lg w-100"
                       target="_blank">
                        <i class="fab fa-whatsapp me-2"></i> Hubungi WhatsApp
                    </a>

                    @if($packages && $packages->count() > 0)
                        <hr class="my-4">
                        <div class="text-start">
                            <h6 class="text-muted mb-3">Statistik Paket</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Paket:</span>
                                <strong>{{ $packages->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Paket Tersedia:</span>
                                <strong class="text-success">{{ $packages->where('remaining_quota', '>', 0)->count() }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection