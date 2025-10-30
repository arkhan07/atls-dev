@extends('layouts.frontend')
@push('title', get_phrase('Edit Package'))
@push('meta')@endpush
@section('frontend_layout')

<!-- Start Main Area -->
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
                        <h1 class="ca-title-18px">{{ get_phrase('Edit Package') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('agent.packages.index') }}">{{ get_phrase('Packages') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">{{ get_phrase('Edit') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="ca-content-card">
                    <form action="{{ route('agent.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Region -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Region') }} <span class="text-danger">*</span></label>
                                <select name="region_id" class="form-select" required>
                                    <option value="">{{ get_phrase('Select Region') }}</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $package->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                @error('region_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Package Title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $package->title) }}" required>
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date Range -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Date Range') }} <span class="text-danger">*</span></label>
                                <input type="text" name="date_range" class="form-control" placeholder="e.g., 15-20 Februari 2025" value="{{ old('date_range', $package->date_range) }}" required>
                                @error('date_range')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Days -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Days') }} <span class="text-danger">*</span></label>
                                <input type="text" name="days" class="form-control" placeholder="e.g., Senin-Jumat" value="{{ old('days', $package->days) }}" required>
                                @error('days')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Time') }} <span class="text-danger">*</span></label>
                                <input type="text" name="time" class="form-control" placeholder="e.g., 08:00 - 17:00 WIB" value="{{ old('time', $package->time) }}" required>
                                @error('time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Location') }} <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $package->location) }}" required>
                                @error('location')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Maps Link -->
                            <div class="col-12">
                                <label class="form-label">{{ get_phrase('Google Maps Link') }}</label>
                                <input type="url" name="maps_link" class="form-control" placeholder="https://maps.google.com/..." value="{{ old('maps_link', $package->maps_link) }}">
                                @error('maps_link')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quota -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Quota (Participants)') }} <span class="text-danger">*</span></label>
                                <input type="number" name="quota" class="form-control" min="1" value="{{ old('quota', $package->quota) }}" required>
                                @error('quota')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Price (Rp)') }} <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" min="0" value="{{ old('price', $package->price) }}" required>
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $package->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('End Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $package->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank Account -->
                            <div class="col-md-4">
                                <label class="form-label">{{ get_phrase('Bank Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control" placeholder="e.g., BCA" value="{{ old('bank_name', $package->bank_name) }}" required>
                                @error('bank_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ get_phrase('Account Number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $package->bank_account) }}" required>
                                @error('bank_account')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ get_phrase('Account Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $package->account_name) }}" required>
                                @error('account_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Contact Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $package->contact_name) }}" required>
                                @error('contact_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Contact Phone') }} <span class="text-danger">*</span></label>
                                <input type="text" name="contact_phone" class="form-control" placeholder="628xxxxxxxxxx" value="{{ old('contact_phone', $package->contact_phone) }}" required>
                                @error('contact_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label">{{ get_phrase('Description') }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $package->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Package Image -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Package Image') }}</label>
                                @if($package->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('uploads/packages/' . $package->image) }}" class="img-thumbnail" style="max-height: 150px;">
                                        <p class="small text-muted mt-1">{{ get_phrase('Current Image') }}</p>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">{{ get_phrase('Leave empty to keep current image. Max size: 2MB. Format: JPG, PNG, GIF') }}</small>
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label">{{ get_phrase('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>{{ get_phrase('Active') }}</option>
                                    <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>{{ get_phrase('Inactive') }}</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('agent.packages.index') }}" class="btn btn-secondary">{{ get_phrase('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Update Package') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection