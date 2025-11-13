@extends('layouts.admin')
@section('title', 'Detail Pendaftaran - ' . $registration->full_name)
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-document me-2"></i>
                Detail Pendaftaran #{{ $registration->id }}
            </h4>
            <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fi-rr-angle-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Left Column -->
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-user me-2"></i>Data Pribadi</h5>
                <table class="table table-sm">
                    <tr>
                        <th width="200">Nama Lengkap</th>
                        <td>{{ $registration->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $registration->email }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $registration->phone }}</td>
                    </tr>
                    @if($registration->id_number)
                    <tr>
                        <th>Nomor KTP/Passport</th>
                        <td>{{ $registration->id_number }}</td>
                    </tr>
                    @endif
                    @if($registration->birth_date)
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $registration->birth_date->format('d M Y') }}</td>
                    </tr>
                    @endif
                    @if($registration->gender)
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $registration->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Professional Information -->
        @if($registration->profession || $registration->institution)
        <div class="ol-card mt-3">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-briefcase me-2"></i>Data Profesional</h5>
                <table class="table table-sm">
                    @if($registration->profession)
                    <tr>
                        <th width="200">Profesi</th>
                        <td>{{ $registration->profession }}</td>
                    </tr>
                    @endif
                    @if($registration->specialization)
                    <tr>
                        <th>Spesialisasi</th>
                        <td>{{ $registration->specialization }}</td>
                    </tr>
                    @endif
                    @if($registration->institution)
                    <tr>
                        <th>Institusi</th>
                        <td>{{ $registration->institution }}</td>
                    </tr>
                    @endif
                    @if($registration->license_number)
                    <tr>
                        <th>Nomor STR</th>
                        <td>{{ $registration->license_number }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif

        <!-- Address -->
        @if($registration->address || $registration->city)
        <div class="ol-card mt-3">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-marker me-2"></i>Alamat</h5>
                <table class="table table-sm">
                    @if($registration->address)
                    <tr>
                        <th width="200">Alamat</th>
                        <td>{{ $registration->address }}</td>
                    </tr>
                    @endif
                    @if($registration->city)
                    <tr>
                        <th>Kota</th>
                        <td>{{ $registration->city }}</td>
                    </tr>
                    @endif
                    @if($registration->province)
                    <tr>
                        <th>Provinsi</th>
                        <td>{{ $registration->province }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif

        <!-- Additional Info -->
        @if($registration->is_certified || $registration->notes)
        <div class="ol-card mt-3">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-info me-2"></i>Informasi Tambahan</h5>
                <table class="table table-sm">
                    @if($registration->is_certified)
                    <tr>
                        <th width="200">Sertifikasi Sebelumnya</th>
                        <td>Ya ({{ $registration->previous_certification_date ? $registration->previous_certification_date->format('d M Y') : 'Tanggal tidak tersedia' }})</td>
                    </tr>
                    @endif
                    @if($registration->notes)
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $registration->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Package Info -->
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-box me-2"></i>Informasi Paket</h5>
                <h6>{{ $registration->package->title }}</h6>
                <p class="mb-2"><i class="fi-rr-marker me-2"></i>{{ $registration->package->region->name ?? 'N/A' }}</p>
                <p class="mb-2"><i class="fi-rr-calendar me-2"></i>{{ $registration->package->date_range }}</p>
                <p class="mb-0"><strong>{{ $registration->package->formatted_price }}</strong></p>
            </div>
        </div>

        <!-- Status -->
        <div class="ol-card mt-3">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-checkbox me-2"></i>Status</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Status Pendaftaran</th>
                        <td>
                            @if($registration->status == 'pending')
                            <span class="badge bg-warning">{{ $registration->status_label }}</span>
                            @elseif($registration->status == 'confirmed')
                            <span class="badge bg-success">{{ $registration->status_label }}</span>
                            @elseif($registration->status == 'cancelled')
                            <span class="badge bg-danger">{{ $registration->status_label }}</span>
                            @else
                            <span class="badge bg-info">{{ $registration->status_label }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>
                            @if($registration->payment_status == 'paid')
                            <span class="badge bg-success">{{ $registration->payment_status_label }}</span>
                            @elseif($registration->payment_status == 'pending')
                            <span class="badge bg-warning">{{ $registration->payment_status_label }}</span>
                            @else
                            <span class="badge bg-secondary">{{ $registration->payment_status_label }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ $registration->package->formatted_price }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>{{ $registration->registered_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- User Info -->
        @if($registration->user)
        <div class="ol-card mt-3">
            <div class="ol-card-body p-3">
                <h5 class="mb-3"><i class="fi-rr-user me-2"></i>User Account</h5>
                <p class="mb-1"><strong>{{ $registration->user->name }}</strong></p>
                <p class="mb-0 text-muted small">{{ $registration->user->email }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection