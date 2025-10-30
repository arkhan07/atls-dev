@extends('layouts.frontend')
@push('title', get_phrase('Package Registrants'))
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
                        <h1 class="ca-title-18px">{{ get_phrase('Registrants') }} - {{ $package->title }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('agent.packages.index') }}">{{ get_phrase('Packages') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">{{ get_phrase('Registrants') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Package Summary -->
                <div class="ca-content-card mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">{{ get_phrase('Total Quota') }}</p>
                            <h4>{{ $package->quota }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">{{ get_phrase('Registered') }}</p>
                            <h4 class="text-success">{{ $package->registration_count }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">{{ get_phrase('Remaining') }}</p>
                            <h4 class="text-info">{{ $package->remaining_quota }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">{{ get_phrase('Fill Rate') }}</p>
                            <h4>{{ number_format($package->registration_percentage, 0) }}%</h4>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar {{ $package->registration_percentage >= 80 ? 'bg-success' : 'bg-info' }}" 
                             style="width: {{ $package->registration_percentage }}%"></div>
                    </div>
                </div>

                <!-- Registrants List -->
                <div class="ca-content-card">
                    <h5 class="mb-3">{{ get_phrase('Registrant List') }}</h5>
                    
                    @if($package->registrations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ get_phrase('Name') }}</th>
                                        <th>{{ get_phrase('Email') }}</th>
                                        <th>{{ get_phrase('Phone') }}</th>
                                        <th>{{ get_phrase('Amount') }}</th>
                                        <th>{{ get_phrase('Status') }}</th>
                                        <th>{{ get_phrase('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->registrations as $key => $registration)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $registration->name }}</td>
                                        <td>{{ $registration->email }}</td>
                                        <td>{{ $registration->phone }}</td>
                                        <td>{{ $registration->formatted_amount }}</td>
                                        <td>
                                            <span class="badge {{ $registration->status_badge }}">
                                                {{ ucfirst($registration->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $registration->registration_date->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ get_phrase('No registrants yet') }}</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('agent.packages.index') }}" class="btn btn-secondary">{{ get_phrase('Back to Packages') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection