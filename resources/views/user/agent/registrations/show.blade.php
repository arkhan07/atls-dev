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
                        <a href="{{ route('agent.registrations.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
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

                <!-- Status & Payment Approval -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-check-circle me-2"></i>Status & Persetujuan Pembayaran</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Status Pendaftaran:</strong><br>
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
                    </div>

                    @if($registration->payment_proof_url)
                    <hr>
                    <h6 class="mb-3">Bukti Transfer</h6>
                    <div class="text-center mb-3">
                        <a href="{{ $registration->payment_proof_url }}" target="_blank">
                            <img src="{{ $registration->payment_proof_url }}" alt="Payment Proof" class="img-fluid" style="max-height: 300px; border-radius: 8px; border: 2px solid #e5e7eb;">
                        </a>
                        <p class="text-muted mt-2 mb-0">Upload: {{ $registration->payment_proof_uploaded_at ? $registration->payment_proof_uploaded_at->format('d M Y, H:i') : '-' }}</p>
                    </div>

                    @if($registration->payment_status !== 'paid')
                    <div class="d-flex gap-2 justify-content-center">
                        <form action="{{ route('agent.registrations.update_payment_status', $registration->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="payment_status" value="paid">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')">
                                <i class="fas fa-check me-2"></i>Setujui Pembayaran
                            </button>
                        </form>
                        <form action="{{ route('agent.registrations.update_payment_status', $registration->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="payment_status" value="unpaid">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                                <i class="fas fa-times me-2"></i>Tolak Pembayaran
                            </button>
                        </form>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Peserta belum mengupload bukti transfer.
                    </div>
                    @endif
                </div>

                <!-- Personal Data -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Data Pendaftar</h5>
                    <table class="table table-sm table-striped">
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
                            <td><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->phone) }}" target="_blank">{{ $registration->phone }}</a></td>
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
                        @if($registration->nik)
                        <tr>
                            <th>NIK</th>
                            <td>{{ $registration->nik }}</td>
                        </tr>
                        @endif
                        @if($registration->plataran_sehat_name)
                        <tr>
                            <th>Plataran Sehat</th>
                            <td>{{ $registration->plataran_sehat_name }}</td>
                        </tr>
                        @endif
                        @if($registration->shirt_size)
                        <tr>
                            <th>Ukuran Baju</th>
                            <td>{{ $registration->shirt_size }}</td>
                        </tr>
                        @endif
                        @if($registration->address)
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $registration->address }}, {{ $registration->city }}, {{ $registration->province }}</td>
                        </tr>
                        @endif
                        @if($registration->shipping_address)
                        <tr>
                            <th>Alamat Pengiriman Buku</th>
                            <td>{{ $registration->shipping_address }}, {{ $registration->shipping_city }}, {{ $registration->shipping_province }} {{ $registration->shipping_postal_code }}</td>
                        </tr>
                        @endif
                        @if($registration->certificate_file_url)
                        <tr>
                            <th>Sertifikat</th>
                            <td><a href="{{ $registration->certificate_file_url }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i>Download</a></td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Dates -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Tanggal Penting</h5>
                    <table class="table table-sm">
                        <tr>
                            <th width="200">Tanggal Pendaftaran</th>
                            <td>{{ $registration->registered_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($registration->confirmed_at)
                        <tr>
                            <th>Tanggal Konfirmasi</th>
                            <td>{{ $registration->confirmed_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
