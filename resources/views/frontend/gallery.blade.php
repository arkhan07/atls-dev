@extends('layouts.frontend')
@section('title', get_phrase('Gallery'))

@section('content')

<!-- Page Header -->
<section class="page-header-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">{{ get_phrase('Gallery') }}</h1>
                <p class="lead mb-0">{{ get_phrase('Explore our collection of images') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section py-5">
    <div class="container">
        
        <!-- Filter & Search -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="gallery-filters-wrapper d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <!-- Category Filters -->
                    <div class="category-filters">
                        <button class="btn btn-filter {{ !request('category') ? 'active' : '' }}" 
                                onclick="window.location.href='{{ route('frontend.gallery') }}'">
                            {{ get_phrase('All') }}
                        </button>
                        @foreach($categories as $key => $category)
                            <button class="btn btn-filter {{ request('category') == $key ? 'active' : '' }}" 
                                    onclick="window.location.href='{{ route('frontend.gallery', ['category' => $key]) }}'">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                    
                    <!-- Search Form -->
                    <div class="gallery-search">
                        <form method="GET" action="{{ route('frontend.gallery') }}" class="d-flex">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="{{ get_phrase('Search gallery...') }}" 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="fi-rr-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery by Category -->
        @if(!request('search') && !request('category'))
            @foreach($galleryByCategory as $categoryKey => $items)
                @if($items->count() > 0)
                <div class="category-section mb-5">
                    <h3 class="category-title mb-4">
                        <i class="fi-rr-images me-2"></i>
                        {{ $categories[$categoryKey] ?? ucfirst($categoryKey) }}
                        <span class="badge bg-primary">{{ $items->count() }}</span>
                    </h3>
                    
                    <div class="row g-4">
                        @foreach($items as $gallery)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="gallery-item" onclick="openGalleryModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}', '{{ $gallery->description }}')">
                                <div class="gallery-image-wrapper">
                                    @if($gallery->image && file_exists(public_path('uploads/gallery/thumbnails/' . $gallery->image)))
                                        <img src="{{ asset('uploads/gallery/thumbnails/' . $gallery->image) }}" 
                                             alt="{{ $gallery->title }}" 
                                             class="gallery-image">
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fi-rr-picture"></i>
                                        </div>
                                    @endif
                                    <div class="gallery-overlay">
                                        <div class="overlay-content">
                                            <i class="fi-rr-eye fs-2"></i>
                                            <p class="mb-0 mt-2">{{ get_phrase('View Image') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="gallery-info">
                                    <h5 class="gallery-title">{{ $gallery->title }}</h5>
                                    @if($gallery->description)
                                        <p class="gallery-description">{{ Str::limit($gallery->description, 60) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        @else
            <!-- Filtered/Search Results -->
            <div class="row g-4">
                @forelse($galleries as $gallery)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-item" onclick="openGalleryModal('{{ asset('uploads/gallery/' . $gallery->image) }}', '{{ $gallery->title }}', '{{ $gallery->description }}')">
                        <div class="gallery-image-wrapper">
                            @if($gallery->image && file_exists(public_path('uploads/gallery/thumbnails/' . $gallery->image)))
                                <img src="{{ asset('uploads/gallery/thumbnails/' . $gallery->image) }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="gallery-image">
                            @else
                                <div class="no-image-placeholder">
                                    <i class="fi-rr-picture"></i>
                                </div>
                            @endif
                            <div class="gallery-overlay">
                                <div class="overlay-content">
                                    <i class="fi-rr-eye fs-2"></i>
                                    <p class="mb-0 mt-2">{{ get_phrase('View Image') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="gallery-info">
                            <h5 class="gallery-title">{{ $gallery->title }}</h5>
                            @if($gallery->category)
                                <span class="badge bg-info mb-2">{{ $categories[$gallery->category] ?? $gallery->category }}</span>
                            @endif
                            @if($gallery->description)
                                <p class="gallery-description">{{ Str::limit($gallery->description, 60) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-lg-12">
                    <div class="alert alert-info text-center">
                        <i class="fi-rr-info me-2"></i>
                        {{ get_phrase('No gallery items found') }}
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($galleries->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $galleries->withQueryString()->links() }}
            </div>
            @endif
        @endif
        
    </div>
</section>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="galleryModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="galleryModalImg" src="" class="img-fluid w-100" alt="">
                <div class="p-4">
                    <p id="galleryModalDescription" class="text-muted mb-0"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header-section {
    position: relative;
}

/* Gallery Filters */
.gallery-filters-wrapper {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.btn-filter {
    background: #f8f9fa;
    border: 2px solid transparent;
    color: #6c757d;
    padding: 8px 20px;
    margin: 5px;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-filter:hover,
.btn-filter.active {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.gallery-search input {
    min-width: 250px;
    border-radius: 25px;
    padding: 10px 20px;
}

.gallery-search button {
    border-radius: 25px;
    padding: 10px 20px;
}

/* Category Section */
.category-section {
    animation: fadeInUp 0.5s ease;
}

.category-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    padding-bottom: 15px;
    border-bottom: 3px solid #667eea;
    display: inline-block;
}

/* Gallery Items */
.gallery-item {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
}

.gallery-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.gallery-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.no-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.no-image-placeholder i {
    font-size: 3rem;
    color: #95a5a6;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.overlay-content {
    color: white;
    text-align: center;
}

.gallery-info {
    padding: 20px;
}

.gallery-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.gallery-description {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Modal */
#galleryModal .modal-content {
    border-radius: 20px;
    overflow: hidden;
    border: none;
}

#galleryModal img {
    max-height: 70vh;
    object-fit: contain;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-filters-wrapper {
        flex-direction: column;
    }
    
    .category-filters {
        width: 100%;
        text-align: center;
    }
    
    .gallery-search {
        width: 100%;
    }
    
    .gallery-search input {
        width: 100%;
        min-width: auto;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
    
    .gallery-image-wrapper {
        height: 200px;
    }
}
</style>

<script>
function openGalleryModal(imageSrc, title, description) {
    document.getElementById('galleryModalImg').src = imageSrc;
    document.getElementById('galleryModalTitle').textContent = title;
    document.getElementById('galleryModalDescription').textContent = description || '';
    new bootstrap.Modal(document.getElementById('galleryModal')).show();
}
</script>

@endsection
