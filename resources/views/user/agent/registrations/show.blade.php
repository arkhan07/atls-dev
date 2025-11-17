@extends('layouts.frontend')
@push('title', 'Detail Pendaftaran')
@push('meta')@endpush
@section('frontend_layout')

<style>
    .table-detail {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }
    .table-detail th {
        background-color: #f8f9fa;
        font-weight: 600;
        width: 30%;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
    }
    .table-detail td {
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
    }
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending { background-color: #fef3c7; color: #92400e; }
    .status-confirmed { background-color: #d1fae5; color: #065f46; }
    .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    .status-completed { background-color: #dbeafe; color: #1e40af; }
    .action-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .payment-proof-preview {
        max-height: 400px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .payment-proof-preview:hover {
        transform: scale(1.02);
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
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h1 class="ca-title-18px mb-0">Detail Pendaftaran #{{ $registration->id }}</h1>
                    <a href="{{ route('agent.registrations.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <!-- Status & Actions -->
                <div class="action-section mb-4">
                    <div class="row g-3">
                        <!-- Status Pendaftaran -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-2">
                                <i class="fas fa-check-circle me-2"></i>Status Pendaftaran
                            </label>
                            <div class="mb-3">
                                <span class="status-badge status-{{ $registration->status }}">{{ $registration->status_label }}</span>
                            </div>
                            <form action="{{ route('agent.registrations.update_status', $registration->id) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <select name="status" class="form-select" required>
                                        <option value="">Ubah Status...</option>
                                        <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $registration->status == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                        <option value="cancelled" {{ $registration->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        <option value="completed" {{ $registration->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin mengubah status pendaftaran?')">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Status Pembayaran -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-2">
                                <i class="fas fa-credit-card me-2"></i>Status Pembayaran
                            </label>
                            <div class="mb-3">
                                @if($registration->payment_status == 'paid')
                                <span class="badge bg-success">{{ $registration->payment_status_label }}</span>
                                @elseif($registration->payment_status == 'pending')
                                <span class="badge bg-warning">{{ $registration->payment_status_label }}</span>
                                @else
                                <span class="badge bg-secondary">{{ $registration->payment_status_label }}</span>
                                @endif
                            </div>
                            @if($registration->payment_proof_url && $registration->payment_status !== 'paid')
                            <div class="d-flex gap-2">
                                <form action="{{ route('agent.registrations.update_payment_status', $registration->id) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Setujui pembayaran ini?')">
                                        <i class="fas fa-check me-1"></i>Setujui
                                    </button>
                                </form>
                                <form action="{{ route('agent.registrations.update_payment_status', $registration->id) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="payment_status" value="unpaid">
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Tolak pembayaran ini?')">
                                        <i class="fas fa-times me-1"></i>Tolak
                                    </button>
                                </form>
                            </div>
                            @elseif(!$registration->payment_proof_url)
                            <div class="alert alert-info alert-sm mb-0 py-2">
                                <small><i class="fas fa-info-circle me-1"></i>Belum ada bukti transfer</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bukti Transfer -->
                @if($registration->payment_proof_url)
                <div class="ca-content-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Bukti Transfer</h5>
                    <div class="text-center">
                        <a href="{{ $registration->payment_proof_url }}" target="_blank">
                            <img src="{{ $registration->payment_proof_url }}" alt="Payment Proof" class="img-fluid payment-proof-preview">
                        </a>
                        <p class="text-muted mt-2 mb-0 small">
                            <i class="fas fa-clock me-1"></i>Upload: {{ $registration->payment_proof_uploaded_at ? $registration->payment_proof_uploaded_at->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>
                @endif

                <!-- Detail Paket -->
                <div class="ca-content-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-box me-2"></i>Detail Paket</h5>
                    <table class="table table-detail table-sm mb-0">
                        <tr>
                            <th>Nama Paket</th>
                            <td><strong>{{ $registration->package->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>Wilayah</th>
                            <td>{{ $registration->package->region->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pelaksanaan</th>
                            <td>{{ $registration->package->date_range }}</td>
                        </tr>
                        <tr>
                            <th>Durasi & Waktu</th>
                            <td>{{ $registration->package->days }} - {{ $registration->package->time }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td><strong class="text-success">{{ $registration->package->formatted_price }}</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Data Pendaftar -->
                <div class="ca-content-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Data Pendaftar</h5>
                    <table class="table table-detail table-sm mb-0">
                        <tr>
                            <th>Nama Lengkap</th>
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
                            <td><a href="mailto:{{ $registration->email }}">{{ $registration->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Telepon / WhatsApp</th>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->phone) }}" target="_blank" class="text-success">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $registration->phone }}
                                </a>
                            </td>
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
                            <td><span class="badge bg-secondary">{{ $registration->shirt_size }}</span></td>
                        </tr>
                        @endif
                        @if($registration->address)
                        <tr>
                            <th>Alamat Lengkap</th>
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
                            <td>
                                <a href="{{ $registration->certificate_file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Download Sertifikat
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Tanggal Penting -->
                <div class="ca-content-card">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Tanggal Penting</h5>
                    <table class="table table-detail table-sm mb-0">
                        <tr>
                            <th>Tanggal Pendaftaran</th>
                            <td>{{ $registration->registered_at->format('d M Y, H:i') }} WIB</td>
                        </tr>
                        @if($registration->confirmed_at)
                        <tr>
                            <th>Tanggal Konfirmasi</th>
                            <td>{{ $registration->confirmed_at->format('d M Y, H:i') }} WIB</td>
                        </tr>
                        @endif
                        @if($registration->payment_proof_uploaded_at)
                        <tr>
                            <th>Upload Bukti Transfer</th>
                            <td>{{ $registration->payment_proof_uploaded_at->format('d M Y, H:i') }} WIB</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
