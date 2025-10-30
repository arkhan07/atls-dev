@extends('layouts.frontend')
@push('title', get_phrase('My Packages ATLS'))
@push('meta')@endpush
@section('frontend_layout')

<style>
    .package-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .package-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .package-image {
        height: 200px;
        object-fit: cover;
    }
    .badge-status {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

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
                        <h1 class="ca-title-18px">{{ get_phrase('My Packages ATLS') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb cap-breadcrumb">
                                <li class="breadcrumb-item cap-breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item cap-breadcrumb-item active" aria-current="page">{{ get_phrase('Packages') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <button class="btn ca-menu-btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#user-sidebar-offcanvas" aria-controls="user-sidebar-offcanvas">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 5.25H3C2.59 5.25 2.25 4.91 2.25 4.5C2.25 4.09 2.59 3.75 3 3.75H21C21.41 3.75 21.75 4.09 21.75 4.5C21.75 4.91 21.41 5.25 21 5.25Z" fill="#242D47"/>
                            <path d="M21 10.25H3C2.59 10.25 2.25 9.91 2.25 9.5C2.25 9.09 2.59 8.75 3 8.75H21C21.41 8.75 21.75 9.09 21.75 9.5C21.75 9.91 21.41 10.25 21 10.25Z" fill="#242D47"/>
                            <path d="M21 15.25H3C2.59 15.25 2.25 14.91 2.25 14.5C2.25 14.09 2.59 13.75 3 13.75H21C21.41 13.75 21.75 14.09 21.75 14.5C21.75 14.91 21.41 15.25 21 15.25Z" fill="#242D47"/>
                            <path d="M21 20.25H3C2.59 20.25 2.25 19.91 2.25 19.5C2.25 19.09 2.59 18.75 3 18.75H21C21.41 18.75 21.75 19.09 21.75 19.5C21.75 19.91 21.41 20.25 21 20.25Z" fill="#242D47"/>
                        </svg>
                    </button>
                </div>

                <div class="ca-content-card">
                    <!-- Filter & Add Button -->
                    <div class="mb-20px d-flex align-items-center column-gap-3 row-gap-3 justify-content-between flex-wrap">
                        <div class="d-flex column-gap-3 row-gap-2 flex-wrap">
                            <a href="{{ route('agent.packages.index') }}" class="btn btn-sm {{ $filterStatus == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ get_phrase('All Packages') }}
                            </a>
                            <a href="{{ route('agent.packages.index', ['status' => 'active']) }}" class="btn btn-sm {{ $filterStatus == 'active' ? 'btn-success' : 'btn-outline-success' }}">
                                {{ get_phrase('Active') }}
                            </a>
                            <a href="{{ route('agent.packages.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $filterStatus == 'inactive' ? 'btn-warning' : 'btn-outline-warning' }}">
                                {{ get_phrase('Inactive') }}
                            </a>
                        </div>
                        <a href="{{ route('agent.packages.create') }}" class="btn cap2-btn-primary d-flex gap-6px align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="14" height="14">
                                    <path d="M23,11H13V1a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1V11H1a1,1,0,0,0-1,1H0a1,1,0,0,0,1,1H11V23a1,1,0,0,0,1,1h0a1,1,0,0,0,1-1V13H23a1,1,0,0,0,1-1h0A1,1,0,0,0,23,11Z"/>
                                </svg>
                            </span>
                            <span>{{ get_phrase('Add Package') }}</span>
                        </a>
                    </div>

                    @if($packages->count() > 0)
                        <div class="row g-3">
                            @foreach($packages as $package)
                            <div class="col-md-6">
                                <div class="card package-card h-100">
                                    <div class="position-relative">
                                        @if($package->image)
                                            <img src="{{ asset('uploads/packages/' . $package->image) }}" class="card-img-top package-image" alt="{{ $package->title }}">
                                        @else
                                            <div class="card-img-top package-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="badge badge-status {{ $package->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($package->status) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">{{ $package->title }}</h5>
                                        
                                        <ul class="list-unstyled small text-muted mb-3">
                                            <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $package->region->name ?? 'N/A' }}</li>
                                            <li class="mb-1"><i class="fas fa-calendar me-2"></i>{{ $package->date_range }}</li>
                                            <li class="mb-1"><i class="fas fa-clock me-2"></i>{{ $package->days }} - {{ $package->time }}</li>
                                            <li class="mb-1"><i class="fas fa-users me-2"></i>Quota: {{ $package->registration_count }}/{{ $package->quota }}</li>
                                        </ul>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="text-primary mb-0">{{ $package->formatted_price }}</h5>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('agent.packages.edit', $package->id) }}" class="btn btn-outline-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('agent.packages.toggle-status', $package->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-{{ $package->status == 'active' ? 'warning' : 'success' }}" title="Toggle Status">
                                                        <i class="fas fa-{{ $package->status == 'active' ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $package->id }}, '{{ $package->title }}')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @if($package->registration_count > 0)
                                        <a href="{{ route('agent.packages.registrations', $package->id) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                                            <i class="fas fa-users me-1"></i> View {{ $package->registration_count }} Registrants
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $packages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <h4>{{ get_phrase('No packages found') }}</h4>
                            <p class="text-muted">{{ get_phrase('Create your first package to get started') }}</p>
                            <a href="{{ route('agent.packages.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i>{{ get_phrase('Add Package') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ get_phrase('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ get_phrase('Are you sure you want to delete') }} <strong id="packageTitle"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Cancel') }}</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ get_phrase('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
function confirmDelete(packageId, packageTitle) {
    document.getElementById('packageTitle').textContent = packageTitle;
    document.getElementById('deleteForm').action = '/agent/packages/' + packageId + '/delete';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush

@endsection