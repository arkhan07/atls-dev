@extends('layouts.admin')
@section('title', 'Riwayat Pendaftaran ATLS')
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-list me-2"></i>
                Riwayat Pendaftaran ATLS
            </h4>
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <!-- Filters -->
        <form method="GET" class="mb-3">
            <div class="row g-2">
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
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau telepon..." value="{{ $search }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fi-rr-search"></i> Cari
                        </button>
                        @if($search)
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">
                            <i class="fi-rr-cross-small"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        @if(count($registrations))
        <div class="table-responsive">
            <table class="table table-hover table-striped nowrap w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Email / Phone</th>
                        <th>Paket</th>
                        <th>Wilayah</th>
                        <th>Tgl Daftar</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                    <tr>
                        <td>{{ $reg->id }}</td>
                        <td>
                            <strong>{{ $reg->full_name }}</strong>
                            @if($reg->profession)
                            <br><small class="text-muted">{{ $reg->profession }}</small>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $reg->email }}<br>
                                {{ $reg->phone }}
                            </small>
                        </td>
                        <td>{{ $reg->package->title ?? 'N/A' }}</td>
                        <td>{{ $reg->package->region->name ?? 'N/A' }}</td>
                        <td>{{ $reg->registered_at->format('d M Y') }}</td>
                        <td>
                            @if($reg->status == 'pending')
                            <span class="badge bg-warning">{{ $reg->status_label }}</span>
                            @elseif($reg->status == 'confirmed')
                            <span class="badge bg-success">{{ $reg->status_label }}</span>
                            @elseif($reg->status == 'cancelled')
                            <span class="badge bg-danger">{{ $reg->status_label }}</span>
                            @else
                            <span class="badge bg-info">{{ $reg->status_label }}</span>
                            @endif
                        </td>
                        <td>
                            @if($reg->payment_status == 'paid')
                            <span class="badge bg-success">{{ $reg->payment_status_label }}</span>
                            @elseif($reg->payment_status == 'pending')
                            <span class="badge bg-warning">{{ $reg->payment_status_label }}</span>
                            @else
                            <span class="badge bg-secondary">{{ $reg->payment_status_label }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.registrations.show', $reg->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                <i class="fi-rr-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $registrations->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fi-rr-document text-muted" style="font-size: 48px;"></i>
            <h5 class="mt-3">Belum Ada Pendaftaran</h5>
            <p class="text-muted">Pendaftaran ATLS akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

@endsection