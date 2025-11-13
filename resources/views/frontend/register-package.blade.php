@extends('layouts.frontend')
@push('title', 'Pendaftaran - ' . $package->title)
@push('meta')@endpush
@section('frontend_layout')

<style>
    .registration-form {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 30px;
    }
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    .form-section:last-child {
        border-bottom: none;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i {
        color: #a02526;
    }
    .package-info-box {
        background: linear-gradient(135deg, #a02526 0%, #8b1f20 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
    }
</style>

<!-- Start Registration Area -->
<section class="mt-60px mb-80px">
    <div class="container">
        <div class="row">
            <!-- Registration Form -->
            <div class="col-lg-8">
                <!-- Package Info -->
                <div class="package-info-box">
                    <h3 class="mb-3">{{ $package->title }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $package->region->name ?? 'N/A' }}</p>
                            <p class="mb-2"><i class="fas fa-calendar me-2"></i>{{ $package->date_range }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-clock me-2"></i>{{ $package->days }} - {{ $package->time }}</p>
                            <p class="mb-2"><i class="fas fa-users me-2"></i>Kuota: {{ $package->registration_count }}/{{ $package->quota }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                        <h4>{{ $package->formatted_price }}</h4>
                    </div>
                </div>

                <div class="registration-form">
                    <h2 class="mb-4">Form Pendaftaran ATLS</h2>
                    
                    <form action="{{ route('registration.store', $package->id) }}" method="POST">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-user"></i>
                                Data Pribadi
                            </h4>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->name ?? '') }}" required>
                                    @error('full_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" required>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor KTP/Passport</label>
                                    <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="male">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="female">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-briefcase"></i>
                                Data Profesional
                            </h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Profesi</label>
                                    <select name="profession" class="form-select">
                                        <option value="">Pilih Profesi</option>
                                        <option value="Dokter" {{ old('profession') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                        <option value="Perawat" {{ old('profession') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                                        <option value="Paramedis" {{ old('profession') == 'Paramedis' ? 'selected' : '' }}>Paramedis</option>
                                        <option value="Mahasiswa Kedokteran" {{ old('profession') == 'Mahasiswa Kedokteran' ? 'selected' : '' }}>Mahasiswa Kedokteran</option>
                                        <option value="Lainnya" {{ old('profession') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Spesialisasi</label>
                                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="Contoh: Bedah, Anestesi">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institusi/Rumah Sakit</label>
                                    <input type="text" name="institution" class="form-control" value="{{ old('institution') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor STR</label>
                                    <input type="text" name="license_number" class="form-control" value="{{ old('license_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Alamat
                            </h4>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Informasi Tambahan
                            </h4>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_certified" id="is_certified" value="1" {{ old('is_certified') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_certified">
                                        Saya sudah pernah mengikuti pelatihan ATLS sebelumnya
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3" id="prev_cert_date_wrapper" style="display: none;">
                                <label class="form-label">Tanggal Sertifikasi Sebelumnya</label>
                                <input type="date" name="previous_certification_date" class="form-control" value="{{ old('previous_certification_date') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Informasi tambahan atau pertanyaan">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('region.detail', $package->region->slug) }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: #a02526; border-color: #a02526;">
                                <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="registration-form">
                    <h5 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Penting</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pendaftaran akan dikonfirmasi dalam 1x24 jam</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pembayaran dapat dilakukan setelah konfirmasi</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat akan diberikan setelah menyelesaikan pelatihan</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Hubungi admin untuk pertanyaan lebih lanjut</li>
                    </ul>

                    @if($package->contact_name || $package->contact_phone)
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3">Kontak</h6>
                        @if($package->contact_name)
                        <p class="mb-2"><i class="fas fa-user me-2"></i>{{ $package->contact_name }}</p>
                        @endif
                        @if($package->contact_phone)
                        <p class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $package->contact_phone }}">{{ $package->contact_phone }}</a>
                        </p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $package->contact_phone) }}" class="btn btn-success btn-sm w-100 mt-2" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>Chat via WhatsApp
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                @if($package->location)
                <div class="registration-form mt-3">
                    <h6 class="mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i>Lokasi</h6>
                    <p class="mb-2">{{ $package->location }}</p>
                    @if($package->maps_link)
                    <a href="{{ $package->maps_link }}" class="btn btn-outline-primary btn-sm w-100" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Lihat di Google Maps
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('js')
<script>
// Show/hide previous certification date field
document.getElementById('is_certified').addEventListener('change', function() {
    document.getElementById('prev_cert_date_wrapper').style.display = this.checked ? 'block' : 'none';
});

// Trigger on page load if already checked
if (document.getElementById('is_certified').checked) {
    document.getElementById('prev_cert_date_wrapper').style.display = 'block';
}
</script>
@endpush

@endsection