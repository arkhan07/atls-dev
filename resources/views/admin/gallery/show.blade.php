{{-- resources/views/admin/gallery/show.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('Gallery Details') . ' - ' . $gallery->title)
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-eye me-2"></i>
                {{ get_phrase('Gallery Details') }}
            </h4>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-edit"></span>
                    <span>{{ get_phrase('Edit Item') }}</span>
                </a>
                <div class="dropdown">
                    <button class="btn ol-btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fi-rr-menu-dots-vertical"></i> {{ get_phrase('More Actions') }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item fs-14px" href="javascript:void(0);" onclick="duplicateItem()">
                                <i class="fi-rr-copy me-2"></i>{{ get_phrase('Duplicate') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fs-14px" href="javascript:void(0);" onclick="toggleFeatured()">
                                <i class="fi-rr-star me-2"></i>
                                {{ $gallery->is_featured ? get_phrase('Remove Featured') : get_phrase('Make Featured') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fs-14px" href="{{ route('admin.gallery.status', [$gallery->id, $gallery->status == 'active' ? 'inactive' : 'active']) }}">
                                <i class="fi-rr-{{ $gallery->status == 'active' ? 'cross' : 'check' }} me-2"></i>
                                {{ $gallery->status == 'active' ? get_phrase('Deactivate') : get_phrase('Activate') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger fs-14px" href="javascript:void(0);" onclick="delete_modal('{{ route('admin.gallery.destroy', $gallery) }}')">
                                <i class="fi-rr-trash me-2"></i>{{ get_phrase('Delete Item') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-arrow-left"></span>
                    <span>{{ get_phrase('Back to Gallery') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="ol-card mb-3">
            <div class="ol-card-body p-0">
                <!-- Gallery Image -->
                @if($gallery->image && file_exists(public_path('uploads/gallery/' . $gallery->image)))
                    <div class="gallery-hero-image position-relative">
                        <img src="{{ asset('uploads/gallery/' . $gallery->image) }}" 
                             class="img-fluid w-100 rounded-top" 
                             alt="{{ $gallery->title }}"
                             style="max-height: 500px; object-fit: cover; cursor: pointer;"
                             onclick="openImageModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}')">
                        
                        <!-- Image Overlay Info -->
                        <div class="image-overlay position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <div class="d-flex justify-content-between align-items-end text-white">
                                <div>
                                    @if(isset($gallery->meta_data['photographer']))
                                        <small><i class="fi-rr-user me-1"></i>{{ $gallery->meta_data['photographer'] }}</small><br>
                                    @endif
                                    @if(isset($gallery->meta_data['camera_settings']) || isset($gallery->meta_data['camera_info']))
                                        <small><i class="fi-rr-settings me-1"></i>{{ $gallery->meta_data['camera_settings'] ?? $gallery->meta_data['camera_info'] }}</small>
                                    @endif
                                </div>
                                <button class="btn btn-light btn-sm" onclick="openImageModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}')">
                                    <i class="fi-rr-expand"></i> {{ get_phrase('View Full Size') }}
                                </button>
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $gallery->status == 'active' ? 'success' : 'secondary' }} me-1">
                                {{ ucfirst($gallery->status) }}
                            </span>
                            @if($gallery->is_featured)
                                <span class="badge bg-warning">
                                    <i class="fi-rr-star"></i> {{ get_phrase('Featured') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="no-image-placeholder text-center py-5 bg-light rounded-top">
                        <i class="fi-rr-picture fs-1 text-muted"></i>
                        <p class="text-muted mb-0">{{ get_phrase('No image available') }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-3">
                    <!-- Title and Basic Info -->
                    <div class="mb-4">
                        <h2 class="mb-2">{{ $gallery->title }}</h2>
                        
                        <!-- Meta Information -->
                        <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
                            @if($gallery->category)
                                <span>
                                    <i class="fi-rr-tags me-1"></i>
                                    {{ \App\Models\Gallery::getCategories()[$gallery->category] ?? $gallery->category }}
                                </span>
                            @endif
                            <span>
                                <i class="fi-rr-calendar me-1"></i>
                                {{ $gallery->created_at->format('M d, Y \a\t H:i') }}
                            </span>
                            @if($gallery->updated_at->ne($gallery->created_at))
                                <span>
                                    <i class="fi-rr-edit me-1"></i>
                                    {{ get_phrase('Updated') }} {{ $gallery->updated_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        <!-- Slug/URL -->
                        <div class="alert alert-light border-start border-4 border-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ get_phrase('Public URL') }}:</strong><br>
                                    <a href="{{ url('/') }}/gallery/{{ $gallery->slug }}" 
                                       target="_blank" class="text-decoration-none">
                                        {{ url('/') }}/gallery/{{ $gallery->slug }}
                                        <i class="fi-rr-external-link ms-1"></i>
                                    </a>
                                </div>
                                <button class="btn btn-outline-info btn-sm" 
                                        onclick="copyToClipboard('{{ url('/') }}/gallery/{{ $gallery->slug }}')">
                                    <i class="fi-rr-copy"></i> {{ get_phrase('Copy') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($gallery->description)
                        <div class="mb-4">
                            <h5 class="mb-2">{{ get_phrase('Description') }}</h5>
                            <div class="description-content">
                                <p class="mb-0">{!! nl2br(e($gallery->description)) !!}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if(isset($gallery->meta_data['tags']) && !empty($gallery->meta_data['tags']))
                        <div class="mb-4">
                            <h6 class="mb-2">{{ get_phrase('Tags') }}</h6>
                            <div class="tags-container">
                                @php
                                    $tags = is_array($gallery->meta_data['tags']) 
                                        ? $gallery->meta_data['tags'] 
                                        : explode(',', $gallery->meta_data['tags']);
                                @endphp
                                @foreach($tags as $tag)
                                    <span class="badge bg-light text-dark border me-1 mb-1">#{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Technical Details -->
                    @if($gallery->meta_data && count($gallery->meta_data) > 0)
                        <div class="technical-details bg-light p-3 rounded">
                            <h6 class="mb-3">{{ get_phrase('Technical Details') }}</h6>
                            <div class="row g-3">
                                @if(isset($gallery->meta_data['alt_text']))
                                    <div class="col-md-6">
                                        <strong>{{ get_phrase('Alt Text') }}:</strong><br>
                                        <span class="text-muted">{{ $gallery->meta_data['alt_text'] }}</span>
                                    </div>
                                @endif
                                
                                @if(isset($gallery->meta_data['photographer']))
                                    <div class="col-md-6">
                                        <strong>{{ get_phrase('Photographer') }}:</strong><br>
                                        <span class="text-muted">{{ $gallery->meta_data['photographer'] }}</span>
                                    </div>
                                @endif
                                
                                @if(isset($gallery->meta_data['camera_settings']) || isset($gallery->meta_data['camera_info']))
                                    <div class="col-md-12">
                                        <strong>{{ get_phrase('Camera Information') }}:</strong><br>
                                        <span class="text-muted">{{ $gallery->meta_data['camera_settings'] ?? $gallery->meta_data['camera_info'] }}</span>
                                    </div>
                                @endif
                                
                                <!-- Image File Information -->
                                @if($gallery->image && file_exists(public_path('uploads/gallery/' . $gallery->image)))
                                    @php
                                        $imagePath = public_path('uploads/gallery/' . $gallery->image);
                                        $imageSize = getimagesize($imagePath);
                                        $fileSize = filesize($imagePath);
                                        $fileExtension = pathinfo($gallery->image, PATHINFO_EXTENSION);
                                    @endphp
                                    <div class="col-md-4">
                                        <strong>{{ get_phrase('Dimensions') }}:</strong><br>
                                        <span class="text-muted">{{ $imageSize[0] }} x {{ $imageSize[1] }}px</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>{{ get_phrase('File Size') }}:</strong><br>
                                        <span class="text-muted">{{ number_format($fileSize/1024, 2) }} KB</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>{{ get_phrase('Format') }}:</strong><br>
                                        <span class="text-muted">{{ strtoupper($fileExtension) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Items (if any) -->
        @if($gallery->category)
            @php
                $relatedItems = \App\Models\Gallery::where('category', $gallery->category)
                    ->where('id', '!=', $gallery->id)
                    ->where('status', 'active')
                    ->limit(6)
                    ->get();
            @endphp
            
            @if($relatedItems->count() > 0)
                <div class="ol-card">
                    <div class="ol-card-body p-3">
                        <h5 class="mb-3">{{ get_phrase('Related Items') }}</h5>
                        <div class="row g-3">
                            @foreach($relatedItems as $related)
                                <div class="col-md-4 col-sm-6">
                                    <div class="related-item card h-100">
                                        @if($related->image && file_exists(public_path('uploads/gallery/thumbnails/' . $related->image)))
                                            <img src="{{ asset('uploads/gallery/thumbnails/' . $related->image) }}" 
                                                 class="card-img-top" style="height: 120px; object-fit: cover;" 
                                                 alt="{{ $related->title }}">
                                        @endif
                                        <div class="card-body p-2">
                                            <h6 class="card-title fs-12px mb-1">{{ Str::limit($related->title, 40) }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ $related->created_at->format('M d, Y') }}</small>
                                                <div>
                                                    <a href="{{ route('admin.gallery.show', $related) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fi-rr-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="ol-card mb-3">
            <div class="ol-card-body p-3">
                <h6 class="mb-3">{{ get_phrase('Quick Actions') }}</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn ol-btn-primary">
                        <i class="fi-rr-edit me-2"></i>{{ get_phrase('Edit Item') }}
                    </a>
                    <button type="button" class="btn btn-outline-{{ $gallery->is_featured ? 'warning' : 'secondary' }}" 
                            onclick="toggleFeatured()">
                        <i class="fi-rr-star me-2"></i>
                        {{ $gallery->is_featured ? get_phrase('Remove Featured') : get_phrase('Make Featured') }}
                    </button>
                    <a href="{{ route('admin.gallery.status', [$gallery->id, $gallery->status == 'active' ? 'inactive' : 'active']) }}" 
                       class="btn btn-outline-{{ $gallery->status == 'active' ? 'secondary' : 'success' }}">
                        <i class="fi-rr-{{ $gallery->status == 'active' ? 'cross' : 'check' }} me-2"></i>
                        {{ $gallery->status == 'active' ? get_phrase('Deactivate') : get_phrase('Activate') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="btn btn-outline-info" onclick="duplicateItem()">
                        <i class="fi-rr-copy me-2"></i>{{ get_phrase('Duplicate Item') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="delete_modal('{{ route('admin.gallery.destroy', $gallery) }}')">
                        <i class="fi-rr-trash me-2"></i>{{ get_phrase('Delete Item') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Gallery Information -->
        <div class="ol-card mb-3">
            <div class="ol-card-body p-3">
                <h6 class="mb-3">{{ get_phrase('Gallery Information') }}</h6>
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('ID') }}:</strong>
                    <span class="text-muted">#{{ $gallery->id }}</span>
                </div>
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('Status') }}:</strong>
                    <span class="badge bg-{{ $gallery->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($gallery->status) }}
                    </span>
                </div>
                
                @if($gallery->category)
                    <div class="info-item mb-3 p-2 bg-light rounded">
                        <strong>{{ get_phrase('Category') }}:</strong>
                        <span class="text-muted">{{ \App\Models\Gallery::getCategories()[$gallery->category] ?? $gallery->category }}</span>
                    </div>
                @endif
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('Sort Order') }}:</strong>
                    <span class="text-muted">{{ $gallery->sort_order }}</span>
                </div>
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('Featured') }}:</strong>
                    <span class="badge bg-{{ $gallery->is_featured ? 'warning' : 'light text-dark' }}">
                        {{ $gallery->is_featured ? get_phrase('Yes') : get_phrase('No') }}
                    </span>
                </div>
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('Created By') }}:</strong>
                    <span class="text-muted">{{ $gallery->creator->name ?? get_phrase('Unknown') }}</span>
                </div>
                
                <div class="info-item mb-3 p-2 bg-light rounded">
                    <strong>{{ get_phrase('Created') }}:</strong>
                    <span class="text-muted">{{ $gallery->created_at->format('M d, Y H:i') }}</span>
                </div>
                
                @if($gallery->updated_at->ne($gallery->created_at))
                    <div class="info-item p-2 bg-light rounded">
                        <strong>{{ get_phrase('Last Updated') }}:</strong>
                        <span class="text-muted">{{ $gallery->updated_at->format('M d, Y H:i') }}</span>
                        <br><small class="text-muted">({{ $gallery->updated_at->diffForHumans() }})</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Navigation -->
        <div class="ol-card mb-3">
            <div class="ol-card-body p-3">
                <h6 class="mb-3">{{ get_phrase('Navigation') }}</h6>
                
                @php
                    $prevGallery = \App\Models\Gallery::where('id', '<', $gallery->id)->orderBy('id', 'desc')->first();
                    $nextGallery = \App\Models\Gallery::where('id', '>', $gallery->id)->orderBy('id', 'asc')->first();
                @endphp
                
                <div class="nav-buttons d-grid gap-2">
                    @if($prevGallery)
                        <a href="{{ route('admin.gallery.show', $prevGallery) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fi-rr-arrow-left me-2"></i>{{ get_phrase('Previous Item') }}
                        </a>
                    @endif
                    
                    @if($nextGallery)
                        <a href="{{ route('admin.gallery.show', $nextGallery) }}" class="btn btn-outline-secondary btn-sm">
                            {{ get_phrase('Next Item') }}<i class="fi-rr-arrow-right ms-2"></i>
                        </a>
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-secondary btn-sm">
                        <i class="fi-rr-list me-2"></i>{{ get_phrase('All Gallery Items') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <h6 class="mb-3">{{ get_phrase('Statistics') }}</h6>
                
                <div class="stats-item d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <span>{{ get_phrase('Views') }}:</span>
                    <span class="badge bg-info">{{ $gallery->meta_data['views'] ?? 0 }}</span>
                </div>
                
                <div class="stats-item d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <span>{{ get_phrase('Likes') }}:</span>
                    <span class="badge bg-success">{{ $gallery->meta_data['likes'] ?? 0 }}</span>
                </div>
                
                <div class="stats-item d-flex justify-content-between align-items-center p-2 bg-light rounded">
                    <span>{{ get_phrase('Downloads') }}:</span>
                    <span class="badge bg-warning">{{ $gallery->meta_data['downloads'] ?? 0 }}</span>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">{{ get_phrase('Statistics are updated in real-time') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-1">
                <img id="imageModalImg" src="" class="img-fluid rounded" alt="" style="max-height: 80vh;">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div class="image-info">
                        <small class="text-muted">
                            @if($gallery->image)
                                {{ $gallery->image }}
                                @if(file_exists(public_path('uploads/gallery/' . $gallery->image)))
                                    @php
                                        $imagePath = public_path('uploads/gallery/' . $gallery->image);
                                        $imageSize = getimagesize($imagePath);
                                        $fileSize = filesize($imagePath);
                                    @endphp
                                    • {{ $imageSize[0] }}x{{ $imageSize[1] }}px • {{ number_format($fileSize/1024, 2) }} KB
                                @endif
                            @endif
                        </small>
                    </div>
                    <div>
                        <a href="{{ asset('uploads/gallery/' . $gallery->image) }}" 
                           target="_blank" class="btn btn-sm btn-primary">
                            <i class="fi-rr-external-link me-1"></i>{{ get_phrase('Open Original') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gallery-hero-image img {
    transition: transform 0.3s ease;
}

.gallery-hero-image:hover img {
    transform: scale(1.02);
}

.related-item {
    transition: transform 0.2s ease;
    border: 1px solid #dee2e6;
}

.related-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.info-item {
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-1px);
}

.description-content {
    line-height: 1.6;
    color: #6c757d;
}

.tags-container .badge {
    font-size: 0.75em;
    font-weight: normal;
}

.technical-details {
    border-left: 4px solid #0d6efd;
}

@media (max-width: 768px) {
    .gallery-hero-image img {
        max-height: 300px;
    }
}
</style>

<script>
// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Toggle featured status
function toggleFeatured() {
    $.post('{{ route("admin.gallery.toggle-featured", $gallery) }}')
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(response.message || '{{ get_phrase("Something went wrong") }}');
            }
        })
        .fail(function() {
            toastr.error('{{ get_phrase("Failed to update featured status") }}');
        });
}

// Duplicate item
function duplicateItem() {
    if (confirm('{{ get_phrase("Create a duplicate of this gallery item?") }}')) {
        toastr.info('{{ get_phrase("Duplication feature will be available soon") }}');
    }
}

// Open image modal
function openImageModal(imageSrc, title) {
    document.getElementById('imageModalImg').src = imageSrc;
    document.getElementById('imageModalTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('{{ get_phrase("URL copied to clipboard") }}');
    }, function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        toastr.success('{{ get_phrase("URL copied to clipboard") }}');
    });
}
</script>

@endsection