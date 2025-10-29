@extends('layouts.admin')
@section('title', get_phrase('Add Team Member'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-user-plus me-2"></i>
                {{ get_phrase('Add Team Member') }}
            </h4>

            <a href="{{route('admin.team.index')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-arrow-left"></span>
                <span> {{get_phrase('Back')}} </span>
            </a>   
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form action="{{route('admin.team.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label ol-form-label">{{get_phrase('Full Name')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control ol-form-control" name="name" id="name" 
                               value="{{ old('name') }}" placeholder="{{get_phrase('Enter full name')}}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label ol-form-label">{{get_phrase('Position')}} <span class="text-danger">*</span></label>
                        <select name="position" id="position" class="ol-select2" data-minimum-results-for-search="Infinity" required>
                            <option value="">{{get_phrase('Select Position')}}</option>
                            @foreach($positionOptions as $categoryName => $positions)
                                <optgroup label="{{ $categoryName }}">
                                    @foreach($positions as $position)
                                        <option value="{{ $position }}" {{ old('position') === $position ? 'selected' : '' }}>
                                            {{ $position }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_num" class="form-label ol-form-label">{{get_phrase('Order Number')}} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control ol-form-control" name="order_num" id="order_num" 
                               value="{{ old('order_num', 0) }}" min="0" placeholder="0" required>
                        <small class="form-text text-muted">{{get_phrase('Lower numbers appear first')}}</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label ol-form-label">{{get_phrase('Member Photo')}} <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-file" name="image" id="image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <small class="form-text text-muted">{{get_phrase('Max size: 2MB. Supported: JPG, PNG, GIF')}}</small>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="mb-3">
                <label class="form-label ol-form-label">{{get_phrase('Social Media Links')}}</label>
                <div class="row">
                    @foreach($socialPlatforms as $platform)
                        <div class="col-md-6 mb-2">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fab fa-{{ $platform }}"></i>
                                </span>
                                <input type="url" name="social[{{ $platform }}]" 
                                       class="form-control ol-form-control" 
                                       value="{{ old('social.' . $platform) }}"
                                       placeholder="{{ ucfirst($platform) }} URL">
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="form-text text-muted">{{get_phrase('All social media links are optional')}}</small>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn ol-btn-primary px-4">{{get_phrase('Save Member')}}</button>
                <button type="reset" class="btn ol-btn-secondary px-4 ms-2">{{get_phrase('Reset')}}</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('imagePreview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'imagePreview';
                    preview.className = 'mt-2 text-center';
                    document.getElementById('image').parentNode.appendChild(preview);
                }
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail rounded" style="max-height: 150px;">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection