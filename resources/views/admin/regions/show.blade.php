@extends('layouts.admin')
@section('title', get_phrase('View Region'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-map-marker me-2"></i>
                {{ get_phrase('Region Details') }}: {{ $region->name }}
            </h4>

            <div class="d-flex gap-2">
                <a href="{{route('admin.regions.index')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-arrow-left"></span>
                    <span> {{get_phrase('Back to List')}} </span>
                </a>
                <a href="{{route('admin.regions.edit', $region->id)}}" class="btn ol-btn-outline-primary d-flex align-items-center cg-10px">
                    <span class="fi-rr-edit"></span>
                    <span> {{get_phrase('Edit')}} </span>
                </a>
                <a href="/wilayah-detail/{{ $region->slug }}" target="_blank" class="btn ol-btn-outline-success d-flex align-items-center cg-10px">
                    <span class="fi-rr-external-link"></span>
                    <span> {{get_phrase('View Page')}} </span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Region Overview -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-4">
                    @if($region->icon_image)
                        <img src="{{ asset('storage/' . $region->icon_image) }}" 
                             alt="{{ $region->name }}" 
                             class="rounded me-3" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fi-rr-map-marker text-white" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-1">{{ $region->name }}</h3>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $region->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $region->is_active ? get_phrase('Active') : get_phrase('Inactive') }}
                            </span>
                            <small class="text-muted">
                                {{get_phrase('Order')}}: {{ $region->order_num }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{get_phrase('Basic Information')}}</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">{{get_phrase('Name')}}:</td>
                                <td class="fw-medium">{{ $region->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('Slug')}}:</td>
                                <td class="fw-medium">{{ $region->slug }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('WhatsApp')}}:</td>
                                <td>
                                    <a href="https://wa.me/{{ $region->whatsapp }}" target="_blank" class="text-success text-decoration-none">
                                        <i class="fi-rr-phone me-1"></i>
                                        {{ $region->whatsapp }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('Order')}}:</td>
                                <td class="fw-medium">{{ $region->order_num }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('Status')}}:</td>
                                <td>
                                    <span class="badge {{ $region->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $region->is_active ? get_phrase('Active') : get_phrase('Inactive') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>{{get_phrase('SEO Information')}}</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">{{get_phrase('Meta Title')}}:</td>
                                <td class="fw-medium">{{ $region->meta_title ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('Meta Description')}}:</td>
                                <td class="fw-medium">
                                    @if($region->meta_description)
                                        <small>{{ Str::limit($region->meta_description, 50) }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{get_phrase('Page URL')}}:</td>
                                <td>
                                    <a href="/wilayah-detail/{{ $region->slug }}" target="_blank" class="text-primary text-decoration-none">
                                        /wilayah-detail/{{ $region->slug }}
                                        <i class="fi-rr-external-link ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Quick Stats -->
                <div class="text-center mb-4">
                    <h6>{{get_phrase('Quick Stats')}}</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-primary">
                                <strong>{{ $region->created_at ? $region->created_at->format('d M Y') : '-' }}</strong>
                                <small class="d-block text-muted">{{get_phrase('Created')}}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-success">
                                <strong>{{ $region->updated_at ? $region->updated_at->format('d M Y') : '-' }}</strong>
                                <small class="d-block text-muted">{{get_phrase('Updated')}}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div>
                    <h6>{{get_phrase('Quick Actions')}}</h6>
                    <div class="d-grid gap-2">
                        <a href="{{route('admin.regions.edit', $region->id)}}" class="btn ol-btn-primary btn-sm">
                            <i class="fi-rr-edit me-1"></i> {{get_phrase('Edit Region')}}
                        </a>
                        
                        <a href="/wilayah-detail/{{ $region->slug }}" target="_blank" class="btn ol-btn-outline-primary btn-sm">
                            <i class="fi-rr-external-link me-1"></i> {{get_phrase('View Public Page')}}
                        </a>
                        
                        <form method="POST" action="{{route('admin.regions.toggle-status', $region->id)}}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-{{ $region->is_active ? 'warning' : 'success' }} btn-sm w-100">
                                <i class="fi-rr-{{ $region->is_active ? 'pause' : 'play' }} me-1"></i>
                                {{ $region->is_active ? get_phrase('Deactivate') : get_phrase('Activate') }}
                            </button>
                        </form>
                        
                        <button type="button" class="btn ol-btn-outline-secondary btn-sm" onclick="copyToClipboard('/wilayah-detail/{{ $region->slug }}')">
                            <i class="fi-rr-copy me-1"></i> {{get_phrase('Copy URL')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <h6>{{get_phrase('Page Content')}}</h6>
        @if(isset($region->page) && $region->page->content)
            <div class="content-preview border rounded p-3 bg-light">
                {!! $region->page->content !!}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fi-rr-document text-muted" style="font-size: 3rem;"></i>
                <h6 class="mt-3 text-muted">{{get_phrase('No custom content')}}</h6>
                <p class="text-muted">{{get_phrase('Default content will be displayed on the public page')}}</p>
            </div>
        @endif
    </div>
</div>

<!-- Images Gallery -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <h6>{{get_phrase('Images')}}</h6>
        <div class="row">
            <!-- Icon Image -->
            <div class="col-md-6">
                <div class="text-center">
                    <h6 class="mb-3">{{get_phrase('Region Icon')}}</h6>
                    @if($region->icon_image)
                        <div class="image-container mb-3">
                            <img src="{{ asset('storage/' . $region->icon_image) }}" 
                                 alt="{{ $region->name }} Icon" 
                                 class="img-fluid rounded border" 
                                 style="max-height: 200px; cursor: pointer;"
                                 onclick="showImageModal(this.src, '{{ $region->name }} - Icon')">
                        </div>
                        <div class="text-muted">
                            <small>{{get_phrase('File')}}: {{ basename($region->icon_image) }}</small>
                        </div>
                    @else
                        <div class="py-4">
                            <i class="fi-rr-image text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">{{get_phrase('No icon uploaded')}}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Banner Image -->
            <div class="col-md-6">
                <div class="text-center">
                    <h6 class="mb-3">{{get_phrase('Page Banner')}}</h6>
                    @if($region->banner_image)
                        <div class="image-container mb-3">
                            <img src="{{ asset('storage/' . $region->banner_image) }}" 
                                 alt="{{ $region->name }} Banner" 
                                 class="img-fluid rounded border" 
                                 style="max-height: 200px; cursor: pointer;"
                                 onclick="showImageModal(this.src, '{{ $region->name }} - Banner')">
                        </div>
                        <div class="text-muted">
                            <small>{{get_phrase('File')}}: {{ basename($region->banner_image) }}</small>
                        </div>
                    @else
                        <div class="py-4">
                            <i class="fi-rr-image text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">{{get_phrase('No banner uploaded')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <h6>{{get_phrase('Contact Information')}}</h6>
        <div class="text-center">
            <div class="mb-3">
                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 60px; height: 60px;">
                    <i class="fi-rr-phone text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
            <h5 class="mb-1">{{get_phrase('WhatsApp Contact')}}</h5>
            <p class="text-muted mb-3">{{ $region->whatsapp }}</p>
            <div class="d-grid">
                <a href="https://wa.me/{{ $region->whatsapp }}" target="_blank" class="btn btn-success">
                    <i class="fi-rr-phone me-1"></i>
                    {{get_phrase('Open WhatsApp')}}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SEO Preview -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <h6>{{get_phrase('SEO Preview')}}</h6>
        <div class="seo-preview p-3 border rounded bg-light">
            <div class="text-primary fs-5 mb-1">
                {{ $region->meta_title ?: $region->name }}
            </div>
            <div class="text-success small mb-2">
                {{ url('/') }}/wilayah-detail/{{ $region->slug }}
            </div>
            <div class="text-muted small">
                {{ $region->meta_description ?: 'Selamat datang di wilayah ' . $region->name . '. Silakan hubungi kami untuk informasi lebih lanjut.' }}
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">{{get_phrase('Image Preview')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src, title) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModalTitle').textContent = title;
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

function copyToClipboard(text) {
    const fullUrl = window.location.origin + text;
    navigator.clipboard.writeText(fullUrl).then(function() {
        // Show success message using your existing toast/notification system
        alert('{{get_phrase("URL copied to clipboard!")}}');
    }).catch(function(err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = fullUrl;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('{{get_phrase("URL copied to clipboard!")}}');
    });
}
</script>

@endsection