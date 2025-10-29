@extends('layouts.admin')
@section('title', get_phrase('Team Member Details'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-user me-2"></i>
                {{ get_phrase('Team Member Details') }}
            </h4>

            <div class="d-flex gap-2">
                <a href="{{route('admin.team.edit', $team->id)}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-pen-nib"></span>
                    <span> {{get_phrase('Edit Member')}} </span>
                </a>
                <a href="{{route('admin.team.index')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-arrow-left"></span>
                    <span> {{get_phrase('Back')}} </span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Member Profile Card -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-4">
        <div class="row">
            <!-- Profile Image -->
            <div class="col-md-4 text-center mb-4">
                <div class="member-profile-image">
                    @if($team->image)
                        <img src="{{ asset('storage/' . $team->image) }}" 
                             alt="{{ $team->name }}" 
                             class="img-fluid rounded-circle border border-3 border-light shadow"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                             style="width: 200px; height: 200px;">
                            <i class="fi-rr-user text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if(isset($team->is_active))
                        <span class="badge fs-6 {{ $team->is_active ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                            <i class="fi-rr-{{ $team->is_active ? 'check' : 'cross' }} me-1"></i>
                            {{ $team->is_active ? get_phrase('Active') : get_phrase('Inactive') }}
                        </span>
                    @else
                        <span class="badge fs-6 bg-secondary px-3 py-2">
                            <i class="fi-rr-question me-1"></i>
                            {{get_phrase('Status Unknown')}}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Member Information -->
            <div class="col-md-8">
                <div class="member-details">
                    <!-- Name -->
                    <div class="mb-4">
                        <h2 class="text-primary mb-1">{{ $team->name }}</h2>
                        <p class="text-muted mb-0 fs-5">{{ $team->position }}</p>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="row g-3">
                        <!-- Category -->
                        <div class="col-sm-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <label class="form-label ol-form-label text-muted small">
                                    <i class="fi-rr-folder me-1"></i>
                                    {{get_phrase('Category')}}
                                </label>
                                <div class="detail-value">
                                    @if(isset($team->category) && $team->category)
                                        <span class="badge bg-info fs-6 px-2 py-1">{{ $team->category }}</span>
                                    @else
                                        <span class="badge bg-secondary fs-6 px-2 py-1">{{get_phrase('Not Categorized')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Number -->
                        <div class="col-sm-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <label class="form-label ol-form-label text-muted small">
                                    <i class="fi-rr-sort-numeric-down me-1"></i>
                                    {{get_phrase('Display Order')}}
                                </label>
                                <div class="detail-value">
                                    <span class="badge bg-primary fs-6 px-2 py-1">#{{ $team->order_num }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Created Date -->
                        <div class="col-sm-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <label class="form-label ol-form-label text-muted small">
                                    <i class="fi-rr-calendar me-1"></i>
                                    {{get_phrase('Added On')}}
                                </label>
                                <div class="detail-value">
                                    <span class="text-dark">{{ $team->created_at ? $team->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Last Updated -->
                        <div class="col-sm-6">
                            <div class="detail-item p-3 bg-light rounded">
                                <label class="form-label ol-form-label text-muted small">
                                    <i class="fi-rr-clock me-1"></i>
                                    {{get_phrase('Last Updated')}}
                                </label>
                                <div class="detail-value">
                                    <span class="text-dark">{{ $team->updated_at ? $team->updated_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Media Links -->
@if(isset($team->social_links) && $team->social_links)
    @php
        $socialLinks = is_string($team->social_links) ? json_decode($team->social_links, true) : $team->social_links;
        $hasValidLinks = false;
        if ($socialLinks) {
            foreach ($socialLinks as $url) {
                if (!empty($url)) {
                    $hasValidLinks = true;
                    break;
                }
            }
        }
    @endphp
    
    @if($hasValidLinks)
    <div class="ol-card mt-3">
        <div class="ol-card-body p-4">
            <h5 class="card-title mb-3">
                <i class="fi-rr-share me-2"></i>
                {{get_phrase('Social Media Links')}}
            </h5>
            
            <div class="social-links-grid">
                <div class="row g-3">
                    @foreach($socialLinks as $platform => $url)
                        @if(!empty($url))
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <a href="{{ $url }}" target="_blank" 
                                   class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center py-3 text-decoration-none social-link-btn">
                                    <i class="fab fa-{{ $platform }} me-2 fs-5"></i>
                                    <span class="text-capitalize">{{ $platform }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@endif

<!-- Quick Actions -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-4">
        <h5 class="card-title mb-3">
            <i class="fi-rr-settings me-2"></i>
            {{get_phrase('Quick Actions')}}
        </h5>
        
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{route('admin.team.edit', $team->id)}}" class="btn ol-btn-primary">
                <i class="fi-rr-pen-nib me-1"></i>
                {{get_phrase('Edit Member')}}
            </a>
            
            @if(isset($team->is_active))
                <a href="{{route('admin.team.status', [$team->id, $team->is_active ? 0 : 1])}}" 
                   class="btn {{ $team->is_active ? 'ol-btn-warning' : 'ol-btn-success' }}">
                    <i class="fi-rr-{{ $team->is_active ? 'pause' : 'play' }} me-1"></i>
                    {{ $team->is_active ? get_phrase('Deactivate') : get_phrase('Activate') }}
                </a>
            @endif
            
            <button type="button" class="btn ol-btn-danger" 
                    onclick="delete_modal('{{route('admin.team.destroy', $team->id)}}')">
                <i class="fi-rr-trash me-1"></i>
                {{get_phrase('Delete Member')}}
            </button>
        </div>
    </div>
</div>

<style>
.member-profile-image {
    position: relative;
}

.detail-item {
    transition: all 0.3s ease;
}

.detail-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.detail-value {
    margin-top: 5px;
}

.social-link-btn {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
}

.social-link-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.social-link-btn .fab {
    color: #495057;
}

.social-link-btn:hover .fab {
    color: #007bff;
}

@media (max-width: 768px) {
    .member-details .row {
        margin-top: 2rem;
    }
    
    .member-profile-image img,
    .member-profile-image > div {
        width: 150px !important;
        height: 150px !important;
    }
}
</style>

@endsection