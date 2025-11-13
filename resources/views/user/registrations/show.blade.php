@extends('layouts.frontend')
@push('title', 'Detail Pendaftaran')
@push('meta')@endpush
@section('frontend_layout')

<style>
    .info-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 20px;
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
    .upload-section {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background: #f9fafb;
    }
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
                        <h1 class="ca-title-18px">Detail Pendaftaran #{{ $registration->id }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('customer.registrations.index') }}">Registrasi</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="info-card">
                    <h5 class="mb-3">{{ $registration->package->title }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i>{{ $registration->package->region->name ?? 'N/A' }}</p>
                            <p class="mb-2"><i class="fas fa-calendar me-2 text-primary"></i>{{ $registration->package->date_range }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-clock me-2 text-info"></i>{{ $registration->package->days }} - {{ $registration->package->time }}</p>
                            <p class="mb-0"><strong class="text-success">{{ $registration->package->formatted_price }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Status Pendaftaran</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong><br>
                            <span class="status-badge status-{{ $registration->status }} mt-1">{{ $registration->status_label }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status Pembayaran:</strong><br>
                            @if($registration->payment_status == 'paid')
                            <span class="badge bg-success mt-1">{{ $registration->payment_status_label }}</span>
                            @elseif($registration->payment_status == 'pending')
                            <span class="badge bg-warning mt-1">{{ $registration->payment_status_label }}</span>
                            @else
                            <span class="badge bg-secondary mt-1">{{ $registration->payment_status_label }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pendaftaran:</strong><br>
                            {{ $registration->registered_at->format('d M Y, H:i') }}
                        </div>
                        @if($registration->confirmed_at)
                        <div class="col-md-6">
                            <strong>Tanggal Konfirmasi:</strong><br>
                            {{ $registration->confirmed_at->format('d M Y, H:i') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Upload Payment Proof -->
                @if($registration->payment_status === 'unpaid' || $registration->payment_status === 'pending')
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-upload me-2"></i>Upload Bukti Transfer</h5>
                    
                    @if($registration->payment_proof_url)
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle me-2"></i>
                        Bukti transfer telah diupload. 
                        @if($registration->payment_status === 'pending')
                            <strong>Menunggu verifikasi admin.</strong>
                        @endif
                    </div>
                    <div class="text-center mb-3">
                        <img src="{{ $registration->payment_proof_url }}" alt="Payment Proof" class="img-fluid" style="max-height: 300px; border-radius: 8px;">
                        <p class="text-muted mt-2 mb-0">Upload: {{ $registration->payment_proof_uploaded_at ? $registration->payment_proof_uploaded_at->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#uploadForm">
                            <i class="fas fa-edit me-2"></i>Ganti Bukti Transfer
                        </button>
                    </div>
                    <div class="collapse mt-3" id="uploadForm">
                        <hr>
                        <form action="{{ route('customer.registrations.upload-payment-proof', $registration->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Upload Bukti Transfer Baru</label>
                                <input type="file" name="payment_proof" class="form-control" accept="image/*,application/pdf" required>
                                <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 5MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="upload-section">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h6>Upload Bukti Transfer Anda</h6>
                        <p class="text-muted mb-4">Silakan upload bukti pembayaran untuk memproses pendaftaran Anda</p>
                        
                        <form action="{{ route('customer.registrations.upload-payment-proof', $registration->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="payment_proof" class="form-control" accept="image/*,application/pdf" required>
                                <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 5MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload Bukti Transfer
                            </button>
                        </form>
                    </div>

                    <!-- Bank Info -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-3">Informasi Rekening</h6>
                        <p class="mb-2"><strong>Bank BCA</strong></p>
                        <p class="mb-2">No. Rekening: <strong>1234567890</strong></p>
                        <p class="mb-0">Atas Nama: <strong>ATLS Indonesia</strong></p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Personal Data -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Data Pendaftar</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="200">Nama Lengkap</th>
                            <td>{{ $registration->title }} {{ $registration->full_name }}</td>
                        </tr>
                        @if($registration->nickname)
                        <tr>
                            <th>Nickname</th>
                            <td>{{ $registration->nickname }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Email</th>
                            <td>{{ $registration->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $registration->phone }}</td>
                        </tr>
                        @if($registration->place_of_birth || $registration->birth_date)
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $registration->place_of_birth ?? '-' }}, {{ $registration->birth_date ? $registration->birth_date->format('d M Y') : '-' }} ({{ $registration->calculated_age }} tahun)</td>
                        </tr>
                        @endif
                        @if($registration->gender)
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $registration->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        @endif
                        @if($registration->religion)
                        <tr>
                            <th>Agama</th>
                            <td>{{ $registration->religion }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('customer.registrations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
