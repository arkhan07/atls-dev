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
    .required-mark {
        color: #dc2626;
        font-weight: bold;
    }
    .form-note {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
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
                    <p class="text-muted mb-4">Mohon lengkapi data berikut dengan benar. Field bertanda <span class="required-mark">*</span> wajib diisi.</p>
                    
                    <form action="{{ route('registration.store', $package->id) }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-user"></i>
                                Data Pribadi
                            </h4>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Gelar/Title</label>
                                    <select name="title" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="Dr." {{ old('title') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                        <option value="dr." {{ old('title') == 'dr.' ? 'selected' : '' }}>dr.</option>
                                        <option value="Prof." {{ old('title') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                        <option value="Ns." {{ old('title') == 'Ns.' ? 'selected' : '' }}>Ns.</option>
                                    </select>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->name ?? '') }}" required>
                                    @error('full_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Panggilan/Nickname</label>
                                    <input type="text" name="nickname" class="form-control" value="{{ old('nickname') }}">
                                    @error('nickname')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="required-mark">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Telepon <span class="required-mark">*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" required placeholder="08xxxxxxxxxx">
                                    <small class="form-note">Notifikasi akan dikirim via WhatsApp ke nomor ini</small>
                                    @error('phone')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="required-mark">*</span></label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Usia</label>
                                    <input type="number" id="age" class="form-control" readonly placeholder="Akan dihitung otomatis">
                                    <small class="form-note">Dihitung otomatis dari tanggal lahir</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="required-mark">*</span></label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="male">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="female">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Agama</label>
                                    <select name="religion" class="form-select">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('religion') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor KTP</label>
                                    <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}" maxlength="16">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK (untuk undangan Plataran Sehat)</label>
                                    <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" maxlength="16">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Akun Plataran Sehat</label>
                                    <input type="text" name="plataran_sehat_name" class="form-control" value="{{ old('plataran_sehat_name') }}">
                                </div>
                                @if(in_array($package->region->slug ?? '', ['jakarta', 'bandung', 'surabaya']))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ukuran Baju</label>
                                    <select name="shirt_size" class="form-select">
                                        <option value="">Pilih Ukuran</option>
                                        <option value="XS" {{ old('shirt_size') == 'XS' ? 'selected' : '' }}>XS</option>
                                        <option value="S" {{ old('shirt_size') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('shirt_size') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('shirt_size') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('shirt_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('shirt_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ old('shirt_size') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    </select>
                                    <small class="form-note">Tersedia untuk wilayah tertentu</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Alamat Domisili <span class="required-mark">*</span>
                            </h4>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap <span class="required-mark">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota/Kabupaten</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address for ATLS Book -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-shipping-fast"></i>
                                Alamat Pengiriman Buku ATLS
                            </h4>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="same_as_domicile" checked>
                                    <label class="form-check-label" for="same_as_domicile">
                                        Sama dengan alamat domisili
                                    </label>
                                </div>
                            </div>
                            <div id="shipping_address_fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="shipping_address" class="form-control" rows="2">{{ old('shipping_address') }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kota/Kabupaten</label>
                                        <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="shipping_province" class="form-control" value="{{ old('shipping_province') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="shipping_postal_code" class="form-control" value="{{ old('shipping_postal_code') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-file-upload"></i>
                                Upload Dokumen
                            </h4>
                            <div class="mb-3">
                                <label class="form-label">Sertifikat (Ijazah Kedokteran/Sertifikat Spesialis)</label>
                                <input type="file" name="certificate_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-note">Format: PDF, JPG, PNG. Maksimal 5MB</small>
                                @error('certificate_file')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-comment"></i>
                                Catatan Tambahan
                            </h4>
                            <div class="mb-3">
                                <textarea name="notes" class="form-control" rows="3" placeholder="Pertanyaan, permintaan khusus, atau informasi tambahan">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-file-contract"></i>
                                Syarat dan Ketentuan
                            </h4>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="agreed_to_terms" id="agreed_to_terms" value="1" {{ old('agreed_to_terms') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="agreed_to_terms">
                                        Saya telah membaca dan menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku <span class="required-mark">*</span>
                                    </label>
                                </div>
                                @error('agreed_to_terms')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
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
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Isi formulir dengan lengkap dan benar</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Notifikasi akan dikirim via WhatsApp</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pendaftaran dikonfirmasi dalam 1x24 jam</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pembayaran setelah konfirmasi</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat setelah menyelesaikan pelatihan</li>
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

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Syarat dan Ketentuan Pendaftaran ATLS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Pendaftaran</h6>
                <p>Peserta wajib mengisi formulir pendaftaran dengan lengkap dan benar. Data yang tidak lengkap dapat menyebabkan penolakan pendaftaran.</p>
                
                <h6>2. Pembayaran</h6>
                <p>Pembayaran dilakukan setelah mendapat konfirmasi dari panitia. Biaya pendaftaran yang sudah dibayarkan tidak dapat dikembalikan kecuali event dibatalkan oleh panitia.</p>
                
                <h6>3. Persyaratan Peserta</h6>
                <ul>
                    <li>Peserta adalah tenaga kesehatan (dokter, perawat, paramedis) atau mahasiswa kedokteran</li>
                    <li>Peserta wajib mengikuti seluruh rangkaian acara pelatihan</li>
                    <li>Peserta wajib mematuhi protokol kesehatan yang berlaku</li>
                </ul>
                
                <h6>4. Sertifikat</h6>
                <p>Sertifikat hanya diberikan kepada peserta yang mengikuti pelatihan secara penuh dan lulus ujian.</p>
                
                <h6>5. Pembatalan</h6>
                <p>Jika peserta berhalangan hadir, wajib memberitahukan panitia maksimal 7 hari sebelum acara. Dalam kondisi tertentu, biaya dapat dialihkan ke event berikutnya.</p>
                
                <h6>6. Privasi Data</h6>
                <p>Data pribadi peserta akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan administrasi pelatihan ATLS.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
// Calculate age from birth date
document.getElementById('birth_date').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age >= 0 ? age : '';
});

// Toggle shipping address fields
document.getElementById('same_as_domicile').addEventListener('change', function() {
    const shippingFields = document.getElementById('shipping_address_fields');
    shippingFields.style.display = this.checked ? 'none' : 'block';
});

// Trigger age calculation on page load if date exists
if (document.getElementById('birth_date').value) {
    document.getElementById('birth_date').dispatchEvent(new Event('change'));
}
</script>
@endpush

@endsection