@extends('layouts.frontend')

@push('title')
    Tambah Paket Pricing Baru
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Tambah Paket Pricing Baru</h3>
                    <p class="text-muted">Buat paket training/workshop baru untuk peserta</p>
                </div>
                <a href="{{ route('agent.pricing.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Form Paket Baru</h5>
                </div>

                <form action="{{ route('agent.pricing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        
                        {{-- Basic Information --}}
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h6>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   name="title" value="{{ old('title') }}" 
                                   placeholder="contoh: Workshop Digital Marketing Intensive" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Rentang Tanggal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('date_range') is-invalid @enderror" 
                                           name="date_range" value="{{ old('date_range') }}" 
                                           placeholder="contoh: 18-19 April 2025" required>
                                    @error('date_range')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kuota Peserta <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quota') is-invalid @enderror" 
                                           name="quota" value="{{ old('quota') }}" min="1" required>
                                    @error('quota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hari Pelaksanaan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('days') is-invalid @enderror" 
                                           name="days" value="{{ old('days') }}" 
                                           placeholder="contoh: Sabtu - Minggu" required>
                                    @error('days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Waktu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('time') is-invalid @enderror" 
                                           name="time" value="{{ old('time') }}" 
                                           placeholder="contoh: 09.00 - 17.00 WIB" required>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Location Information --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Informasi Lokasi
                                </h6>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   name="location" value="{{ old('location') }}" 
                                   placeholder="contoh: Hotel Santika Jakarta, Ruang Meeting Lt. 3" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Link Google Maps <small class="text-muted">(opsional)</small></label>
                            <input type="url" class="form-control @error('maps_link') is-invalid @enderror" 
                                   name="maps_link" value="{{ old('maps_link') }}" 
                                   placeholder="https://maps.google.com/...">
                            @error('maps_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contact Information --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-phone me-2"></i>Informasi Kontak
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Kontak <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                           name="contact_name" value="{{ old('contact_name', Auth::user()->name) }}" required>
                                    @error('contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           name="contact_phone" value="{{ old('contact_phone') }}" 
                                           placeholder="08123456789" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Description & Media --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-file-alt me-2"></i>Deskripsi & Media
                                </h6>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Paket</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="4" 
                                      placeholder="Jelaskan secara detail tentang paket training ini, materi yang akan dibahas, target peserta, dan benefit yang akan didapat...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Paket</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   name="image" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pricing & Status --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-dollar-sign me-2"></i>Harga & Status
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Harga (IDR) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           name="price" value="{{ old('price') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Bank Information --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-university me-2"></i>Informasi Rekening
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Rekening <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                           name="bank_account" value="{{ old('bank_account') }}" required>
                                    @error('bank_account')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Bank <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                           name="bank_name" value="{{ old('bank_name') }}" 
                                           placeholder="contoh: BCA, Mandiri, BNI" required>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Atas Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                           name="account_name" value="{{ old('account_name', Auth::user()->name) }}" required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('agent.pricing.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Paket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.form-label.fw-bold {
    color: #495057;
}
.card-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}
.text-primary {
    color: #007bff !important;
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Auto-calculate end date when start date changes
    $('input[name="start_date"]').on('change', function() {
        const startDate = new Date($(this).val());
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 1); // Default to next day
        
        if (!$('input[name="end_date"]').val()) {
            $('input[name="end_date"]').val(endDate.toISOString().split('T')[0]);
        }
    });

    // Format price input
    $('input[name="price"]').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
    });
});
</script>
@endpush