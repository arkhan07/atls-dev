{{-- resources/views/admin/gallery/index.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('Gallery Management'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-images me-2"></i>
                {{ get_phrase('Gallery Management') }}
            </h4>
            <a href="{{ route('admin.gallery.create') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Add New Gallery') }}</span>
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form method="GET" action="{{ route('admin.gallery.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control ol-form-control" 
                       placeholder="{{ get_phrase('Search galleries...') }}" 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="category" class="ol-select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ get_phrase('All Categories') }}</option>
                    @foreach(\App\Models\Gallery::getCategories() as $key => $category)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="ol-select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ get_phrase('All Status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ get_phrase('Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ get_phrase('Inactive') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="featured" class="ol-select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ get_phrase('All Items') }}</option>
                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>{{ get_phrase('Featured Only') }}</option>
                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>{{ get_phrase('Non-Featured') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Filter') }}</button>
                    <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-secondary">{{ get_phrase('Reset') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Gallery Grid -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        @if(count($galleries) > 0)
            <!-- Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="bulk-actions" style="display: none;">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                            <i class="fi-rr-trash"></i> {{ get_phrase('Delete Selected') }}
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="bulkStatusUpdate('active')">
                            <i class="fi-rr-check"></i> {{ get_phrase('Activate Selected') }}
                        </button>
                    </div>
                </div>
                
                <div class="showing-results">
                    {{ get_phrase('Showing') }} {{ $galleries->firstItem() ?? 0 }} {{ get_phrase('to') }} {{ $galleries->lastItem() ?? 0 }} 
                    {{ get_phrase('of') }} {{ $galleries->total() }} {{ get_phrase('results') }}
                </div>
            </div>

            <div class="row" id="sortable-gallery">
                @foreach($galleries as $gallery)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" data-id="{{ $gallery->id }}">
                        <div class="card gallery-card h-100">
                            <div class="card-header p-0 position-relative">
                                <!-- Checkbox for bulk selection -->
                                <div class="form-check position-absolute top-0 start-0 m-2 z-3">
                                    <input class="form-check-input gallery-checkbox" type="checkbox" 
                                           value="{{ $gallery->id }}" data-id="{{ $gallery->id }}">
                                </div>
                                
                                <!-- Featured badge -->
                                @if($gallery->is_featured)
                                    <span class="badge bg-warning position-absolute top-0 end-0 m-2 z-3">
                                        <i class="fi-rr-star"></i> {{ get_phrase('Featured') }}
                                    </span>
                                @endif
                                
                                <!-- Gallery Image -->
                                <div class="gallery-image-container">
                                    @if($gallery->image && file_exists(public_path('uploads/gallery/thumbnails/' . $gallery->image)))
                                        <img src="{{ asset('uploads/gallery/thumbnails/' . $gallery->image) }}" 
                                             class="card-img-top gallery-image" 
                                             alt="{{ $gallery->title }}"
                                             onclick="openImageModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}')">
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fi-rr-picture fs-3 text-muted"></i>
                                            <p class="text-muted small mb-0">{{ get_phrase('No Image') }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Hover Overlay -->
                                <div class="card-overlay">
                                    <div class="overlay-actions">
                                        <a href="{{ route('admin.gallery.show', $gallery) }}" 
                                           class="btn btn-sm btn-light" title="{{ get_phrase('View') }}">
                                            <i class="fi-rr-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.gallery.edit', $gallery) }}" 
                                           class="btn btn-sm btn-primary" title="{{ get_phrase('Edit') }}">
                                            <i class="fi-rr-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="delete_modal('{{ route('admin.gallery.destroy', $gallery) }}')" 
                                                title="{{ get_phrase('Delete') }}">
                                            <i class="fi-rr-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2 fw-semibold">
                                    {{ Str::limit($gallery->title, 30) }}
                                </h6>
                                
                                @if($gallery->description)
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit($gallery->description, 60) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="status-badges">
                                        @if($gallery->category)
                                            <span class="badge bg-info">{{ \App\Models\Gallery::getCategories()[$gallery->category] ?? $gallery->category }}</span>
                                        @endif
                                        <span class="badge {{ $gallery->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($gallery->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fi-rr-calendar me-1"></i>
                                        {{ $gallery->created_at->format('M d, Y') }}
                                    </small>
                                    
                                    <div class="dropdown ol-icon-dropdown">
                                        <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="fi-rr-menu-dots-vertical"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item fs-14px" href="{{ route('admin.gallery.show', $gallery) }}">
                                                    {{get_phrase('View')}}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item fs-14px" href="{{ route('admin.gallery.edit', $gallery) }}">
                                                    {{get_phrase('Edit')}}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item fs-14px" href="{{ route('admin.gallery.status', [$gallery->id, $gallery->status == 'active' ? 'inactive' : 'active']) }}">
                                                    {{ $gallery->status == 'active' ? get_phrase('Deactivate') : get_phrase('Activate') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item fs-14px" href="javascript:void(0);" onclick="toggleFeatured('{{ $gallery->id }}')">
                                                    {{ $gallery->is_featured ? get_phrase('Remove Featured') : get_phrase('Make Featured') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item fs-14px" 
                                                   onclick="delete_modal('{{ route('admin.gallery.destroy', $gallery) }}')" 
                                                   href="javascript:void(0);">
                                                    {{get_phrase('Delete')}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $galleries->withQueryString()->links() }}
            </div>
        @else
            @include('layouts.no_data_found')
        @endif
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageModalImg" src="" class="img-fluid rounded" alt="">
            </div>
        </div>
    </div>
</div>

<style>
.gallery-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.gallery-image-container {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.05);
}

.no-image-placeholder {
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-card:hover .card-overlay {
    opacity: 1;
}

.overlay-actions .btn {
    margin: 0 4px;
}

@media (max-width: 768px) {
    .gallery-card .card-overlay {
        opacity: 1;
        background: rgba(0,0,0,0.5);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Toggle featured status
function toggleFeatured(galleryId) {
    $.post(`/admin/gallery/${galleryId}/toggle-featured`)
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

// Bulk selection handling
$('.gallery-checkbox').change(function() {
    const checkedBoxes = $('.gallery-checkbox:checked');
    if (checkedBoxes.length > 0) {
        $('.bulk-actions').show();
    } else {
        $('.bulk-actions').hide();
    }
});

// Bulk delete
function bulkDelete() {
    const selectedIds = $('.gallery-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('{{ get_phrase("Please select items to delete") }}');
        return;
    }
    
    if (confirm('{{ get_phrase("Are you sure you want to delete selected items?") }}')) {
        $.post('{{ route("admin.gallery.bulk-delete") }}', {
            ids: selectedIds
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(response.message || '{{ get_phrase("Something went wrong") }}');
            }
        })
        .fail(function() {
            toastr.error('{{ get_phrase("Failed to delete items") }}');
        });
    }
}

// Bulk status update
function bulkStatusUpdate(status) {
    const selectedIds = $('.gallery-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('{{ get_phrase("Please select items to update") }}');
        return;
    }
    
    $.post('{{ route("admin.gallery.bulk-status-update") }}', {
        ids: selectedIds,
        status: status
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(response.message || '{{ get_phrase("Something went wrong") }}');
        }
    })
    .fail(function() {
        toastr.error('{{ get_phrase("Failed to update status") }}');
    });
}

// Open image modal
function openImageModal(imageSrc, title) {
    document.getElementById('imageModalImg').src = imageSrc;
    document.getElementById('imageModalTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Initialize Sortable if gallery exists
if (document.getElementById('sortable-gallery')) {
    new Sortable(document.getElementById('sortable-gallery'), {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function (evt) {
            updateSortOrder();
        }
    });
}

// Update sort order
function updateSortOrder() {
    const orders = [];
    $('#sortable-gallery .col-xl-3').each(function(index) {
        orders.push({
            id: $(this).data('id'),
            position: index
        });
    });
    
    $.post('{{ route("admin.gallery.update-sort-order") }}', {
        orders: orders
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
        }
    })
    .fail(function() {
        toastr.error('{{ get_phrase("Failed to update sort order") }}');
    });
}
</script>

@endsection