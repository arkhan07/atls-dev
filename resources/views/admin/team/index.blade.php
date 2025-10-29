@extends('layouts.admin')
@section('title', get_phrase('Team Management'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-users me-2"></i>
                {{ get_phrase('Team Management') }}
            </h4>

            <a href="{{route('admin.team.create')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span> {{get_phrase('Add New Member')}} </span>
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form method="GET" action="{{ route('admin.team.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="category" class="ol-select2" data-minimum-results-for-search="Infinity" onchange="this.form.submit()">
                    <option value="">{{get_phrase('All Categories')}}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="subcategory" class="ol-select2" data-minimum-results-for-search="Infinity" onchange="this.form.submit()">
                    <option value="">{{get_phrase('All Positions')}}</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory }}" {{ request('subcategory') === $subcategory ? 'selected' : '' }}>
                            {{ $subcategory }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control ol-form-control" 
                       placeholder="{{get_phrase('Search by name or position')}}" 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn ol-btn-primary">{{get_phrase('Filter')}}</button>
            </div>
        </form>
    </div>
</div>

<!-- Team Members Table -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        @if(count($teamMembers) > 0)
        <table id="datatable" class="table nowrap w-100">
            <thead>
                <tr>
                    <th>{{get_phrase('ID')}}</th>
                    <th>{{get_phrase('Photo')}}</th>
                    <th>{{get_phrase('Name')}}</th>
                    <th>{{get_phrase('Category')}}</th>
                    <th>{{get_phrase('Position')}}</th>
                    <th>{{get_phrase('Order')}}</th>
                    <th>{{get_phrase('Status')}}</th>
                    <th>{{get_phrase('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @php $num = 1 @endphp
                @foreach ($teamMembers as $member)
                <tr>
                    <td>{{ $num++ }}</td>
                    <td>
                        @if($member->image)
                            <img class="rounded" src="{{ asset('storage/' . $member->image) }}" 
                                 alt="{{ $member->name }}" width="50px" height="50px">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fi-rr-user text-white"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $member->name }}</td>
                    <td>
                        @if(isset($member->category) && $member->category)
                            <span class="badge bg-info">{{ $member->category }}</span>
                        @else
                            <span class="badge bg-secondary">{{get_phrase('N/A')}}</span>
                        @endif
                    </td>
                    <td>{{ $member->position }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $member->order_num }}</span>
                    </td>
                    <td>
                        @if(isset($member->is_active))
                            <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-warning' }}">
                                {{ $member->is_active ? get_phrase('Active') : get_phrase('Inactive') }}
                            </span>
                        @else
                            <span class="badge bg-secondary">{{get_phrase('N/A')}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown ol-icon-dropdown">
                            <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi-rr-menu-dots-vertical"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item fs-14px" href="{{route('admin.team.edit', $member->id)}}">
                                        {{get_phrase('Edit')}}
                                    </a>
                                </li>
                                @if(isset($member->is_active))
                                    @if($member->is_active)
                                        <li>
                                            <a class="dropdown-item fs-14px" href="{{route('admin.team.status', [$member->id, 0])}}">
                                                {{get_phrase('Deactivate')}}
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item fs-14px" href="{{route('admin.team.status', [$member->id, 1])}}">
                                                {{get_phrase('Activate')}}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                <li>
                                    <a class="dropdown-item fs-14px" 
                                       onclick="delete_modal('{{route('admin.team.destroy', $member->id)}}')" 
                                       href="javascript:void(0);">
                                        {{get_phrase('Delete')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            @include('layouts.no_data_found')
        @endif
        
        <!-- Pagination -->
        @if(count($teamMembers) > 0)
        <div class="d-flex justify-content-center mt-4">
            {{ $teamMembers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection