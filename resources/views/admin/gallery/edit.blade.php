{{-- resources/views/admin/gallery/edit.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('Edit Gallery Item'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-edit me-2"></i>
                {{ get_phrase('Edit Gallery Item') }}: {{ $gallery->title }}
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-eye"></span>
                    <span>{{ get_phrase('Preview') }}</span>
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-arrow-left"></span>
                    <span>{{ get_phrase('Back to Gallery') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data" id="galleryEditForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label ol-form-label">
                            {{ get_phrase('Title') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control ol-form-control @error('title') is-invalid @enderror" 
                               name="title" id="title" value="{{ old('title', $gallery->title) }}" required
                               placeholder="{{ get_phrase('Enter gallery title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug (Read-only display) -->
                    <div class="mb-3">
                        <label for="slug_display" class="form-label ol-form-label">{{ get_phrase('URL Slug') }}</label>
                        <input type="text" class="form-control ol-form-control" 
                               id="slug_display" value="{{ $gallery->slug }}" readonly>
                        <small class="form-text text-muted">
                            {{ get_phrase('Permalink') }}: 
                            <a href="{{ url('/') }}/gallery/{{ $gallery->slug }}" target="_blank" class="text-decoration-none">
                                {{ url('/') }}/gallery/{{ $gallery->slug }} <i class="fi-rr-external-link"></i>
                            </a>
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                        <textarea class="form-control ol-form-control @error('description') is-invalid @enderror" 
                                  name="description" id="description" rows="4" 
                                  placeholder="{{ get_phrase('Enter description (optional)') }}">{{ old('description', $gallery->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Image Display -->
                    @if($gallery->image && file_exists(public_path('uploads/gallery/' . $gallery->image)))
                        <div class="mb-3">
                            <label class="form-label ol-form-label">{{ get_phrase('Current Image') }}</label>
                            <div class="current-image-display border rounded p-3 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <img src="{{ asset('uploads/gallery/thumbnails/' . $gallery->image) }}" 
                                             class="img-fluid rounded cursor-pointer"
                                             onclick="openImageModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}')"
                                             alt="{{ $gallery->title }}">
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-2">{{ get_phrase('Image Information') }}</h6>
                                        <div class="image-info">
                                            <p class="mb-1"><strong>{{ get_phrase('Filename') }}:</strong> {{ $gallery->image }}</p>
                                            @if(file_exists(public_path('uploads/gallery/' . $gallery->image)))
                                                @php
                                                    $imagePath = public_path('uploads/gallery/' . $gallery->image);
                                                    $imageSize = getimagesize($imagePath);
                                                    $fileSize = filesize($imagePath);
                                                @endphp
                                                @if($imageSize)
                                                    <p class="mb-1"><strong>{{ get_phrase('Dimensions') }}:</strong> {{ $imageSize[0] }} x {{ $imageSize[1] }}px</p>
                                                    <p class="mb-0"><strong>{{ get_phrase('File Size') }}:</strong> {{ number_format($fileSize/1024, 2) }} KB</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Image Upload (Replace/New) -->
                    <div class="mb-3">
                        <label for="image" class="form-label ol-form-label">
                            {{ $gallery->image ? get_phrase('Replace Image') : get_phrase('Gallery Image') }}
                            @if(!$gallery->image) <span class="text-danger">*</span> @endif
                        </label>
                        <div class="upload-area border-2 border-dashed rounded p-4 text-center" id="uploadArea">
                            <input type="file" class="form-control d-none @error('image') is-invalid @enderror" 
                                   name="image" id="image" accept="image/*" 
                                   {{ !$gallery->image ? 'required' : '' }} onchange="previewImage(this)">
                            
                            <div id="uploadPlaceholder">
                                <i class="fi-rr-cloud-upload fs-2 text-muted mb-2"></i>
                                <p class="mb-1">{{ get_phrase('Click to upload or drag and drop') }}</p>
                                <p class="small text-muted mb-0">{{ get_phrase('Supported formats: JPG, PNG, GIF. Max size: 5MB') }}</p>
                                @if($gallery->image)
                                    <p class="small text-info mt-2">{{ get_phrase('Leave empty to keep current image') }}</p>
                                @endif
                            </div>
                            
                            <!-- New Image Preview -->
                            <div id="imagePreview" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                        <i class="fi-rr-trash me-1"></i>{{ get_phrase('Remove') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="changeImage()">
                                        <i class="fi-rr-edit me-1"></i>{{ get_phrase('Change') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Advanced Options -->
                    <div class="advanced-options">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0">{{ get_phrase('Advanced Options') }}</h6>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none ms-2" 
                                    data-bs-toggle="collapse" data-bs-target="#advancedOptions" 
                                    @if(old('alt_text') || old('tags') || old('photographer') || old('camera_info') || ($gallery->meta_data && count($gallery->meta_data) > 0)) aria-expanded="true" @endif>
                                <i class="fi-rr-angle-down"></i>
                            </button>
                        </div>
                        
                        <div class="collapse @if(old('alt_text') || old('tags') || old('photographer') || old('camera_info') || ($gallery->meta_data && count($gallery->meta_data) > 0)) show @endif" id="advancedOptions">
                            <div class="row g-3">
                                <!-- Alt Text -->
                                <div class="col-md-6">
                                    <label for="alt_text" class="form-label ol-form-label">{{ get_phrase('Alt Text') }}</label>
                                    <input type="text" class="form-control ol-form-control" 
                                           name="alt_text" id="alt_text" 
                                           value="{{ old('alt_text', $gallery->meta_data['alt_text'] ?? '') }}"
                                           placeholder="{{ get_phrase('Alternative text for image') }}">
                                </div>
                                
                                <!-- Tags -->
                                <div class="col-md-6">
                                    <label for="tags" class="form-label ol-form-label">{{ get_phrase('Tags') }}</label>
                                    <input type="text" class="form-control ol-form-control" 
                                           name="tags" id="tags" 
                                           value="{{ old('tags', is_array($gallery->meta_data['tags'] ?? null) ? implode(', ', $gallery->meta_data['tags']) : ($gallery->meta_data['tags'] ?? '')) }}"
                                           placeholder="{{ get_phrase('Enter tags separated by commas') }}">
                                </div>
                                
                                <!-- Photographer -->
                                <div class="col-md-6">
                                    <label for="photographer" class="form-label ol-form-label">{{ get_phrase('Photographer') }}</label>
                                    <input type="text" class="form-control ol-form-control" 
                                           name="photographer" id="photographer" 
                                           value="{{ old('photographer', $gallery->meta_data['photographer'] ?? '') }}"
                                           placeholder="{{ get_phrase('Photographer name') }}">
                                </div>
                                
                                <!-- Camera Info -->
                                <div class="col-md-6">
                                    <label for="camera_info" class="form-label ol-form-label">{{ get_phrase('Camera Info') }}</label>
                                    <input type="text" class="form-control ol-form-control" 
                                           name="camera_info" id="camera_info" 
                                           value="{{ old('camera_info', $gallery->meta_data['camera_settings'] ?? $gallery->meta_data['camera_info'] ?? '') }}"
                                           placeholder="{{ get_phrase('Camera model and settings') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Settings) -->
                <div class="col-md-4">
                    <div class="settings-card border rounded p-3 bg-light">
                        <h6 class="mb-3">{{ get_phrase('Gallery Settings') }}</h6>
                        
                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label ol-form-label">{{ get_phrase('Category') }}</label>
                            <select class="ol-select2 @error('category') is-invalid @enderror" name="category" id="category" data-minimum-results-for-search="Infinity">
                                <option value="">{{ get_phrase('Select Category') }}</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}" {{ old('category', $gallery->category) == $key ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label ol-form-label">
                                {{ get_phrase('Status') }} <span class="text-danger">*</span>
                            </label>
                            <select class="ol-select2 @error('status') is-invalid @enderror" name="status" id="status" required data-minimum-results-for-search="Infinity">
                                <option value="active" {{ old('status', $gallery->status) == 'active' ? 'selected' : '' }}>{{ get_phrase('Active') }}</option>
                                <option value="inactive" {{ old('status', $gallery->status) == 'inactive' ? 'selected' : '' }}>{{ get_phrase('Inactive') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-3">
                            <label for="sort_order" class="form-label ol-form-label">{{ get_phrase('Sort Order') }}</label>
                            <input type="number" class="form-control ol-form-control @error('sort_order') is-invalid @enderror" 
                                   name="sort_order" id="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" min="0">
                            <small class="form-text text-muted">{{ get_phrase('Lower numbers appear first') }}</small>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Featured -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" 
                                       {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label ol-form-label" for="is_featured">
                                    {{ get_phrase('Mark as Featured') }}
                                </label>
                            </div>
                            <small class="form-text text-muted">{{ get_phrase('Featured items appear prominently') }}</small>
                        </div>

                        <!-- Publication Info -->
                        <div class="publication-info bg-white p-3 rounded border">
                            <h6 class="mb-2">{{ get_phrase('Publication Info') }}</h6>
                            <div class="d-flex align-items-center text-muted small mb-1">
                                <i class="fi-rr-user me-2"></i>
                                <span>{{ get_phrase('Created by') }}: {{ $gallery->creator->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted small mb-1">
                                <i class="fi-rr-calendar me-2"></i>
                                <span>{{ get_phrase('Created') }}: {{ $gallery->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($gallery->updated_at->ne($gallery->created_at))
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fi-rr-edit me-2"></i>
                                    <span>{{ get_phrase('Updated') }}: {{ $gallery->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="quick-actions mt-3 pt-3 border-top">
                            <h6 class="mb-2">{{ get_phrase('Quick Actions') }}</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fi-rr-eye me-1"></i>{{ get_phrase('Preview') }}
                                </a>
                                <button type="button" class="btn btn-outline-{{ $gallery->is_featured ? 'warning' : 'secondary' }} btn-sm" 
                                        onclick="toggleFeatured()">
                                    <i class="fi-rr-star me-1"></i>
                                    {{ $gallery->is_featured ? get_phrase('Remove Featured') : get_phrase('Make Featured') }}
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="delete_modal('{{ route('admin.gallery.destroy', $gallery) }}')">
                                    <i class="fi-rr-trash me-1"></i>{{ get_phrase('Delete Item') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-secondary px-4">
                            <i class="fi-rr-arrow-left me-1"></i>{{ get_phrase('Cancel') }}
                        </a>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="save_continue" class="btn ol-btn-outline-primary px-4">
                                <i class="fi-rr-disk me-1"></i>{{ get_phrase('Save & Continue') }}
                            </button>
                            <button type="submit" name="action" value="save_close" class="btn ol-btn-primary px-4">
                                <i class="fi-rr-check me-1"></i>{{ get_phrase('Save & Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
.upload-area {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #0d6efd;
    background: #e7f3ff;
}

.settings-card {
    position: sticky;
    top: 20px;
}

.current-image-display {
    border-left: 4px solid #198754;
}

.current-image-display img {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.current-image-display img:hover {
    transform: scale(1.05);
}

.publication-info {
    border-left: 4px solid #0d6efd;
}

.advanced-options {
    border-top: 1px solid #dee2e6;
    margin-top: 20px;
    padding-top: 20px;
}

@media (max-width: 768px) {
    .settings-card {
        position: static;
        margin-top: 20px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Setup drag and drop
    setupDragAndDrop();
});

function setupDragAndDrop() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('image');

    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    });
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (!file.type.match('image.*')) {
            toastr.error('{{ get_phrase("Please select a valid image file") }}');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            toastr.error('{{ get_phrase("File size must be less than 5MB") }}');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('uploadPlaceholder').style.display = 'none';
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('preview').src = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('imagePreview').style.display = 'none';
}

function changeImage() {
    document.getElementById('image').click();
}

function openImageModal(imageSrc, title) {
    document.getElementById('imageModalImg').src = imageSrc;
    document.getElementById('imageModalTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function toggleFeatured() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('{{ route("admin.gallery.toggle-featured", $gallery) }}')
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            } else {
                toastr.error(response.message || '{{ get_phrase("Something went wrong") }}');
            }
        })
        .fail(function() {
            toastr.error('{{ get_phrase("Failed to update featured status") }}');
        });
}
</script>

@endsection