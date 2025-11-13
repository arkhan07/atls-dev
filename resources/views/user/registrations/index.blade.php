@extends('layouts.frontend')
@push('title', 'Registrasi ATLS Saya')
@push('meta')@endpush
@section('frontend_layout')

<style>
    .registration-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .registration-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-confirmed { background-color: #d1fae5; color: #065f46; }
    .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    .status-completed { background-color: #dbeafe; color: #1e40af; }
</style>

<section class="ca-wraper-main mb-90px mt-4">
    <div class="container">
        <div class="row gx-20px">
            <div class="col-lg-4 col-xl-3">
                @include('user.navigation')
            </div>
            <div class="col-lg-8 col-xl-9">
                <!-- Header -->
                <div class="d-flex align-items-start justify-content-between gap-2 mb-20px">
                    <div class="d-flex justify-content-between align-items-start gap-12px flex-column flex-lg-row w-100">
                        <h1 class="ca-title-18px">Registrasi ATLS Saya</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">Registrasi</li>
                            </ol>
                        </nav>
                    </div>
                    <button class="btn ca-menu-btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#user-sidebar-offcanvas">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 5.25H3C2.59 5.25 2.25 4.91 2.25 4.5C2.25 4.09 2.59 3.75 3 3.75H21C21.41 3.75 21.75 4.09 21.75 4.5C21.75 4.91 21.41 5.25 21 5.25Z" fill="#242D47"/>
                            <path d="M21 10.25H3C2.59 10.25 2.25 9.91 2.25 9.5C2.25 9.09 2.59 8.75 3 8.75H21C21.41 8.75 21.75 9.09 21.75 9.5C21.75 9.91 21.41 10.25 21 10.25Z" fill="#242D47"/>
                            <path d="M21 15.25H3C2.59 15.25 2.25 14.91 2.25 14.5C2.25 14.09 2.59 13.75 3 13.75H21C21.41 13.75 21.75 14.09 21.75 14.5C21.75 14.91 21.41 15.25 21 15.25Z" fill="#242D47"/>
                        </svg>
                    </button>
                </div>

                <div class="ca-content-card">
                    @if($registrations->count() > 0)
                        @foreach($registrations as $registration)
                        <div class="registration-card">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-2">{{ $registration->package->title }}</h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>{{ $registration->package->region->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar me-2"></i>{{ $registration->package->date_range }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-clock me-2"></i>Didaftarkan: {{ $registration->registered_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="mb-3">
                                        <span class="status-badge status-{{ $registration->status }}">{{ $registration->status_label }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ $registration->package->formatted_price }}</strong>
                                    </div>
                                    <a href="{{ route('customer.registrations.show', $registration->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $registrations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h4>Belum Ada Registrasi</h4>
                            <p class="text-muted">Anda belum memiliki registrasi ATLS. Daftar sekarang untuk memulai!</p>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-search me-2"></i>Cari Pelatihan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection