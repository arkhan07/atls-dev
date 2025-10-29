@extends('layouts.frontend')

@push('title')
    Kelola Paket Pricing
@endpush

@push('meta')
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Kelola Paket Pricing</h3>
                    <p class="text-muted">Manage your training packages and monitor registrations</p>
                </div>
                <a href="{{ route('agent.pricing.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Paket Baru
                </a>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_packages'] }}</h4>
                                    <span>Total Paket</span>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['active_packages'] }}</h4>
                                    <span>Paket Aktif</span>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_registrations'] }}</h4>
                                    <span>Total Peserta</span>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                                    <span>Total Revenue</span>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filter Buttons --}}
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <a href="{{ route('agent.pricing.index') }}" 
                                   class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Semua Paket
                                </a>
                                <a href="{{ route('agent.pricing.index', ['status' => 'active']) }}" 
                                   class="btn {{ $status === 'active' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Aktif
                                </a>
                                <a href="{{ route('agent.pricing.index', ['status' => 'inactive']) }}" 
                                   class="btn {{ $status === 'inactive' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Tidak Aktif
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted">Total: {{ $packages->count() }} paket</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Packages Grid --}}
                    @if($packages->count() > 0)
                        <div class="row">
                            @foreach($packages as $package)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card h-100 {{ $package->status === 'inactive' ? 'border-danger' : '' }}">
                                        {{-- Package Image --}}
                                        <div class="position-relative">
                                            @if($package->image)
                                                <img src="{{ $package->image_url }}" class="card-img-top" 
                                                     alt="{{ $package->title }}" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                            
                                            @if($package->status === 'inactive')
                                                <div class="position-absolute top-0 end-0 p-2">
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="card-body">
                                            {{-- Title & Status --}}
                                            <h5 class="card-title mb-3">
                                                {{ $package->title }}
                                                <span class="badge {{ $package->status === 'active' ? 'bg-success' : 'bg-danger' }} ms-2">
                                                    {{ $package->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </h5>

                                            {{-- Package Info --}}
                                            <ul class="list-unstyled small">
                                                <li class="mb-2">
                                                    <i class="fas fa-calendar text-primary"></i> 
                                                    <strong>Tanggal:</strong> {{ $package->date_range }}
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-clock text-primary"></i> 
                                                    <strong>Hari:</strong> {{ $package->days }}
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-clock text-primary"></i> 
                                                    <strong>Waktu:</strong> {{ $package->time }}
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-map-marker-alt text-danger"></i> 
                                                    <strong>Lokasi:</strong> {{ Str::limit($package->location, 30) }}
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-users text-success"></i> 
                                                    <strong>Kuota:</strong> {{ $package->quota }} Peserta
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-phone text-info"></i> 
                                                    <strong>Kontak:</strong> {{ $package->contact_name }}
                                                </li>
                                            </ul>

                                            {{-- Description --}}
                                            @if($package->description)
                                                <div class="mt-3 mb-3">
                                                    <p class="small text-muted mb-0">
                                                        {{ Str::limit($package->description, 80) }}
                                                    </p>
                                                </div>
                                            @endif

                                            <hr>

                                            {{-- Price & Registration Info --}}
                                            <div class="mt-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-bold">Harga:</span>
                                                    <span class="fw-bold text-primary fs-5">{{ $package->formatted_price }}</span>
                                                </div>

                                                {{-- Registration Progress --}}
                                                @php
                                                    $registered = $package->registration_count;
                                                    $remaining = $package->remaining_quota;
                                                    $percentage = $package->registration_percentage;
                                                    $progressClass = $percentage >= 80 ? 'bg-danger' : ($percentage >= 50 ? 'bg-warning' : 'bg-success');
                                                @endphp

                                                <div class="mt-3">
                                                    <div class="d-flex justify-content-between mb-1 small">
                                                        <span>Terdaftar: {{ $registered }}</span>
                                                        <span>Sisa: {{ $remaining }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                             style="width: {{ $percentage }}%" 
                                                             aria-valuenow="{{ $percentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('agent.pricing.edit', $package->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form method="POST" action="{{ route('agent.pricing.toggle-status', $package->id) }}" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $package->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                                title="{{ $package->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="fas {{ $package->status === 'active' ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('agent.pricing.destroy', $package->id) }}" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                @if($registered > 0)
                                                    <a href="{{ route('agent.pricing.show', $package->id) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-users"></i> {{ $registered }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Paket</h5>
                            <p class="text-muted">
                                @if($status === 'all')
                                    Anda belum memiliki paket pricing. Mulai dengan membuat paket pertama Anda.
                                @elseif($status === 'active')
                                    Saat ini tidak ada paket yang aktif.
                                @else
                                    Saat ini tidak ada paket yang tidak aktif.
                                @endif
                            </p>
                            <a href="{{ route('agent.pricing.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Buat Paket Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush