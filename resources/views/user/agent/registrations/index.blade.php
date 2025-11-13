@extends('layouts.frontend')
@push('title', 'Daftar Pendaftaran ATLS')
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
                        <h1 class="ca-title-18px">Pendaftaran ATLS</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">Pendaftaran</li>
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

                <!-- Filters & Export -->
                <div class="ca-content-card mb-3">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="package_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ $filterPackageId == 'all' ? 'selected' : '' }}>Semua Paket</option>
                                @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ $filterPackageId == $pkg->id ? 'selected' : '' }}>{{ $pkg->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ $filterStatus == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ $filterStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $filterStatus == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                <option value="cancelled" {{ $filterStatus == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="completed" {{ $filterStatus == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ $filterPaymentStatus == 'all' ? 'selected' : '' }}>Semua Pembayaran</option>
                                <option value="unpaid" {{ $filterPaymentStatus == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="pending" {{ $filterPaymentStatus == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="paid" {{ $filterPaymentStatus == 'paid' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('agent.registrations.export', request()->all()) }}" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau telepon..." value="{{ $search }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search)
                                <a href="{{ route('agent.registrations.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Registrations List -->
                <div class="ca-content-card">
                    @if($registrations->count() > 0)
                        @foreach($registrations as $registration)
                        <div class="registration-card">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <h6 class="mb-2">{{ $registration->full_name }}</h6>
                                    <p class="text-muted mb-1 small">
                                        <i class="fas fa-envelope me-1"></i>{{ $registration->email }}
                                    </p>
                                    <p class="text-muted mb-1 small">
                                        <i class="fas fa-phone me-1"></i>{{ $registration->phone }}
                                    </p>
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-box me-1"></i>{{ $registration->package->title }}
                                    </p>
                                    <p class="text-muted mb-0 small">
                                        <i class="fas fa-clock me-1"></i>{{ $registration->registered_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                    <div class="mb-2">
                                        <span class="status-badge status-{{ $registration->status }}">{{ $registration->status_label }}</span>
                                        @if($registration->payment_status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                        @elseif($registration->payment_status == 'pending')
                                        <span class="badge bg-warning">Verifikasi Pembayaran</span>
                                        @else
                                        <span class="badge bg-secondary">Belum Dibayar</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ $registration->package->formatted_price }}</strong>
                                    </div>
                                    <a href="{{ route('agent.registrations.show', $registration->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $registrations->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h4>Belum Ada Pendaftaran</h4>
                            <p class="text-muted">Pendaftaran untuk paket Anda akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection