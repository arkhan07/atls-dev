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
            <div class="d-flex gap-2">
                <button class="btn ol-btn-primary d-flex align-items-center cg-10px" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add Category') }}</span>
                </button>
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
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="5%">{{ get_phrase('Icon') }}</th>
                            <th width="25%">{{ get_phrase('Name') }}</th>
                            <th width="15%">{{ get_phrase('Slug') }}</th>
                            <th width="25%">{{ get_phrase('Description') }}</th>
                            <th width="10%">{{ get_phrase('Order') }}</th>
                            <th width="10%">{{ get_phrase('Status') }}</th>
                            <th width="10%">{{ get_phrase('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} fs-5"></i>
                                    @else
                                        <i class="fi-rr-folder fs-5 text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ App\Models\Gallery::where('category', $category->slug)->count() }} {{ get_phrase('items') }}
                                    </small>
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <small>{{ Str::limit($category->description ?? '-', 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                                </td>
                                <td>
                                    @if($category->status == 'active')
                                        <span class="badge bg-success">{{ get_phrase('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ get_phrase('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown ol-icon-dropdown">
                                        <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="fi-rr-menu-dots-vertical"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item fs-14px" href="javascript:void(0);" 
                                                   onclick="editCategory({{ json_encode($category) }})">
                                                    <i class="fi-rr-edit me-2"></i>{{ get_phrase('Edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item fs-14px text-danger" 
                                                   onclick="deleteCategory('{{ route('admin.gallery.categories.destroy', $category->id) }}')" 
                                                   href="javascript:void(0);">
                                                    <i class="fi-rr-trash me-2"></i>{{ get_phrase('Delete') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        @else
            @include('layouts.no_data_found')
        @endif
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">{{ get_phrase('Add New Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Slug') }} <small class="text-muted">({{ get_phrase('optional - auto generated') }})</small></label>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Icon Class') }} <small class="text-muted">(e.g., fi-rr-home)</small></label>
                        <input type="text" name="icon" class="form-control" placeholder="fi-rr-folder">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ get_phrase('Sort Order') }}</label>
                                <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ get_phrase('Status') }}</label>
                                <select name="status" class="form-select" required>
                                    <option value="active">{{ get_phrase('Active') }}</option>
                                    <option value="inactive">{{ get_phrase('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ get_phrase('Save Category') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">{{ get_phrase('Edit Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Slug') }}</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Description') }}</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Icon Class') }}</label>
                        <input type="text" name="icon" id="edit_icon" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ get_phrase('Sort Order') }}</label>
                                <input type="number" name="sort_order" id="edit_sort_order" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ get_phrase('Status') }}</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="active">{{ get_phrase('Active') }}</option>
                                    <option value="inactive">{{ get_phrase('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ get_phrase('Update Category') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
function editCategory(category) {
    document.getElementById('editCategoryForm').action = '{{ url("admin/gallery/categories") }}/' + category.id;
    document.getElementById('edit_name').value = category.name;
    document.getElementById('edit_slug').value = category.slug;
    document.getElementById('edit_description').value = category.description || '';
    document.getElementById('edit_icon').value = category.icon || '';
    document.getElementById('edit_sort_order').value = category.sort_order;
    document.getElementById('edit_status').value = category.status;
    
    var editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    editModal.show();
}

function deleteCategory(url) {
    if (confirm('{{ get_phrase("Are you sure you want to delete this category?") }}')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

@endsection
