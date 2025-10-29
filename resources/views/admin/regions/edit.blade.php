@extends('layouts.admin')
@section('title', get_phrase('Edit Region'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-map-marker-pen me-2"></i>
                {{ get_phrase('Edit Region') }}: {{ $region->name }}
            </h4>

            <div class="d-flex gap-2">
                <a href="{{route('admin.regions.index')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-arrow-left"></span>
                    <span> {{get_phrase('Back')}} </span>
                </a>
                <a href="/wilayah-detail/{{ $region->slug }}" target="_blank" class="btn ol-btn-outline-primary d-flex align-items-center cg-10px">
                    <span class="fi-rr-external-link"></span>
                    <span> {{get_phrase('View Page')}} </span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form action="{{route('admin.regions.update', $region->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label ol-form-label">{{get_phrase('Region Name')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control ol-form-control" name="name" id="name" 
                               value="{{ old('name', $region->name) }}" placeholder="{{get_phrase('Enter region name')}}" required>
                        <small class="form-text text-muted">{{get_phrase('Changing this will update the URL slug')}}</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label ol-form-label">{{get_phrase('WhatsApp Number')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control ol-form-control" name="whatsapp" id="whatsapp" 
                               value="{{ old('whatsapp', $region->whatsapp) }}" placeholder="628123456789" 
                               pattern="^628[0-9]{8,13}$" required>
                        <small class="form-text text-muted">{{get_phrase('Format: 628xxxxxxxxxx (10-15 digits)')}}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_num" class="form-label ol-form-label">{{get_phrase('Order Number')}} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control ol-form-control" name="order_num" id="order_num" 
                               value="{{ old('order_num', $region->order_num) }}" min="0" placeholder="0" required>
                        <small class="form-text text-muted">{{get_phrase('Used for sorting regions (0 = highest priority)')}}</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label ol-form-label">{{get_phrase('Status')}}</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   {{ old('is_active', $region->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label ol-form-label" for="is_active">
                                <strong>{{get_phrase('Active Region')}}</strong>
                                <br>
                                <small class="text-muted">{{get_phrase('Inactive regions won\'t be displayed publicly')}}</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current URL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label ol-form-label">{{get_phrase('Current URL')}}</label>
                        <div class="form-control ol-form-control bg-light">
                            <a href="/wilayah-detail/{{ $region->slug }}" target="_blank" class="text-decoration-none">
                                /wilayah-detail/{{ $region->slug }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label ol-form-label">{{get_phrase('Meta Title')}}</label>
                        <input type="text" class="form-control ol-form-control" name="meta_title" id="meta_title" 
                               value="{{ old('meta_title', $region->meta_title) }}" maxlength="60">
                        <small class="form-text text-muted">{{get_phrase('Recommended length: 50-60 characters')}}</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="meta_description" class="form-label ol-form-label">{{get_phrase('Meta Description')}}</label>
                        <textarea class="form-control ol-form-control" name="meta_description" id="meta_description" 
                                  rows="3" maxlength="160">{{ old('meta_description', $region->meta_description) }}</textarea>
                        <small class="form-text text-muted">{{get_phrase('Recommended length: 150-160 characters')}}</small>
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label ol-form-label">{{get_phrase('Current Icon')}}</label>
                        @if($region->icon_image)
                            <div class="text-center mb-2">
                                <img src="{{ asset('storage/' . $region->icon_image) }}" 
                                     alt="{{ $region->name }}" class="img-thumbnail rounded" style="max-height: 100px;">
                                <br>
                                <small class="text-muted">{{get_phrase('Current icon')}}</small>
                            </div>
                        @else
                            <div class="text-center mb-2 p-3 bg-light rounded">
                                <i class="fi-rr-image text-muted" style="font-size: 2rem;"></i>
                                <br>
                                <small class="text-muted">{{get_phrase('No icon uploaded')}}</small>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label ol-form-label">{{get_phrase('Current Banner')}}</label>
                        @if($region->banner_image)
                            <div class="text-center mb-2">
                                <img src="{{ asset('storage/' . $region->banner_image) }}" 
                                     alt="{{ $region->name }}" class="img-thumbnail rounded" style="max-height: 100px;">
                                <br>
                                <small class="text-muted">{{get_phrase('Current banner')}}</small>
                            </div>
                        @else
                            <div class="text-center mb-2 p-3 bg-light rounded">
                                <i class="fi-rr-image text-muted" style="font-size: 2rem;"></i>
                                <br>
                                <small class="text-muted">{{get_phrase('No banner uploaded')}}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Images -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="icon_image" class="form-label ol-form-label">{{get_phrase('New Region Icon')}}</label>
                        <input type="file" class="form-control form-control-file" name="icon_image" id="icon_image" 
                               accept="image/jpeg,image/png,image/gif">
                        <small class="form-text text-muted">{{get_phrase('Format: JPG, PNG, GIF. Max: 2MB. Leave empty to keep current.')}}</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="banner_image" class="form-label ol-form-label">{{get_phrase('New Page Banner')}}</label>
                        <input type="file" class="form-control form-control-file" name="banner_image" id="banner_image" 
                               accept="image/jpeg,image/png,image/gif">
                        <small class="form-text text-muted">{{get_phrase('Format: JPG, PNG, GIF. Max: 2MB. Leave empty to keep current.')}}</small>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="mb-3">
                <label for="content" class="form-label ol-form-label">{{get_phrase('Page Content')}}</label>
                <textarea class="form-control ol-form-control" name="content" id="content" 
                          rows="6">{{ old('content', isset($region->page) ? $region->page->content : '') }}</textarea>
                <small class="form-text text-muted">{{get_phrase('HTML content for the region page')}}</small>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn ol-btn-primary px-4">{{get_phrase('Update Region')}}</button>
                <button type="reset" class="btn ol-btn-secondary px-4 ms-2">{{get_phrase('Reset')}}</button>
                
                <!-- Additional Actions -->
                <div class="mt-3 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <form method="POST" action="{{route('admin.regions.toggle-status', $region->id)}}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-{{ $region->is_active ? 'warning' : 'success' }} me-2">
                                    <i class="fi-rr-{{ $region->is_active ? 'pause' : 'play' }} me-1"></i>
                                    {{ $region->is_active ? get_phrase('Deactivate') : get_phrase('Activate') }}
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-outline-info me-2" onclick="duplicateRegion()">
                                <i class="fi-rr-copy me-1"></i>
                                {{get_phrase('Duplicate Region')}}
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-danger" onclick="delete_modal('{{route('admin.regions.destroy', $region->id)}}')">
                                <i class="fi-rr-trash me-1"></i>
                                {{get_phrase('Delete Region')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // WhatsApp number validation
    document.getElementById('whatsapp').addEventListener('input', function() {
        const whatsapp = this.value.replace(/\D/g, '');
        this.value = whatsapp;
        
        // Validate format
        const isValid = /^628\d{8,13}$/.test(whatsapp);
        if (whatsapp && !isValid) {
            this.setCustomValidity('{{get_phrase("WhatsApp number must start with 628 and be 11-16 digits long")}}');
        } else {
            this.setCustomValidity('');
        }
    });

    // Image preview for new icon
    document.getElementById('icon_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{get_phrase("File size too large. Maximum 2MB allowed.")}}');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('newIconPreview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'newIconPreview';
                    preview.className = 'mt-2 text-center';
                    document.getElementById('icon_image').parentNode.appendChild(preview);
                }
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail rounded" style="max-height: 100px;"><br><small class="text-success">{{get_phrase("New Icon Preview")}}</small>';
            };
            reader.readAsDataURL(file);
        }
    });

    // Image preview for new banner
    document.getElementById('banner_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{get_phrase("File size too large. Maximum 2MB allowed.")}}');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('newBannerPreview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'newBannerPreview';
                    preview.className = 'mt-2 text-center';
                    document.getElementById('banner_image').parentNode.appendChild(preview);
                }
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail rounded" style="max-height: 150px;"><br><small class="text-success">{{get_phrase("New Banner Preview")}}</small>';
            };
            reader.readAsDataURL(file);
        }
    });

    function duplicateRegion() {
        if (confirm('{{get_phrase("Are you sure you want to duplicate this region?")}}')) {
            // You can implement duplicate functionality here
            alert('{{get_phrase("Duplicate functionality not yet implemented")}}');
        }
    }
</script>

@endsection