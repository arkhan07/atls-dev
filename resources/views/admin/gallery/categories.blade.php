{{-- resources/views/admin/gallery/categories.blade.php --}}
@extends('layouts.admin')
@section('title', get_phrase('Gallery Categories'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-apps me-2"></i>
                {{ get_phrase('Gallery Categories') }}
            </h4>
            <a href="{{ route('admin.gallery.index') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-arrow-left"></span>
                <span>{{ get_phrase('Back to Gallery') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Current Categories List -->
    <div class="col-md-8">
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <h5 class="mb-3">{{ get_phrase('Available Categories') }}</h5>
                
                <div class="alert alert-info" role="alert">
                    <i class="fi-rr-info me-2"></i>
                    {{ get_phrase('Gallery categories are currently managed in the application code. To add, edit, or remove categories, please edit the Gallery model or contact your developer.') }}
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ get_phrase('Category Key') }}</th>
                                <th>{{ get_phrase('Category Name') }}</th>
                                <th>{{ get_phrase('Usage Count') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1; @endphp
                            @foreach($categories as $key => $name)
                                <tr>
                                    <td>{{ $index++ }}</td>
                                    <td><code>{{ $key }}</code></td>
                                    <td>{{ $name }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ App\Models\Gallery::where('category', $key)->count() }} {{ get_phrase('items') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="col-md-4">
        <div class="ol-card">
            <div class="ol-card-body p-3">
                <h5 class="mb-3">{{ get_phrase('Category Management') }}</h5>
                
                <div class="instruction-box mb-3 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-2">{{ get_phrase('How to Add Categories') }}</h6>
                    <ol class="mb-0 ps-3">
                        <li class="mb-2">{{ get_phrase('Open the Gallery model file') }}: <code>app/Models/Gallery.php</code></li>
                        <li class="mb-2">{{ get_phrase('Find the') }} <code>getCategories()</code> {{ get_phrase('method') }}</li>
                        <li class="mb-2">{{ get_phrase('Add your new category in the array') }}</li>
                        <li>{{ get_phrase('Save the file') }}</li>
                    </ol>
                </div>

                <div class="instruction-box p-3 bg-light rounded">
                    <h6 class="fw-bold mb-2">{{ get_phrase('Example Code') }}</h6>
                    <pre class="mb-0 bg-white p-2 rounded"><code>'new-category' => 'New Category Name',</code></pre>
                </div>

                <div class="alert alert-warning mt-3" role="alert">
                    <i class="fi-rr-triangle-warning me-2"></i>
                    <small>{{ get_phrase('Make sure to backup your files before editing.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.instruction-box {
    border-left: 4px solid #0d6efd;
}

pre code {
    font-size: 12px;
    color: #e83e8c;
}

.table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>

@endsection
