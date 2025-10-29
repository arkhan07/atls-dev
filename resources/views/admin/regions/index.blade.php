@extends('layouts.admin')
@section('title', get_phrase('Regional Management'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-map-marker me-2"></i>
                {{ get_phrase('Regional Management') }}
            </h4>

            <a href="{{route('admin.regions.create')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span> {{get_phrase('Add New Region')}} </span>
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        <form method="GET" action="{{ route('admin.regions.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="ol-select2" data-minimum-results-for-search="Infinity" onchange="this.form.submit()">
                    <option value="">{{get_phrase('All Status')}}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ get_phrase('Active') }}
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ get_phrase('Inactive') }}
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="ol-select2" data-minimum-results-for-search="Infinity" onchange="this.form.submit()">
                    <option value="order_num" {{ request('sort') === 'order_num' ? 'selected' : '' }}>
                        {{ get_phrase('Order Number') }}
                    </option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                        {{ get_phrase('Name') }}
                    </option>
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>
                        {{ get_phrase('Created Date') }}
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control ol-form-control" 
                       placeholder="{{get_phrase('Search regions...')}}" 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn ol-btn-primary">{{get_phrase('Filter')}}</button>
            </div>
        </form>
    </div>
</div>

<!-- Regions Table -->
<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        @if(count($regions) > 0)
        <table id="datatable" class="table nowrap w-100">
            <thead>
                <tr>
                    <th>{{get_phrase('Order')}}</th>
                    <th>{{get_phrase('Icon')}}</th>
                    <th>{{get_phrase('Name')}}</th>
                    <th>{{get_phrase('WhatsApp')}}</th>
                    <th>{{get_phrase('Status')}}</th>
                    <th>{{get_phrase('Created')}}</th>
                    <th>{{get_phrase('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($regions as $region)
                <tr>
                    <td>
                        <span class="badge bg-primary">{{ $region->order_num }}</span>
                    </td>
                    <td>
                        @if($region->icon_image)
                            <img class="rounded" src="{{ asset('storage/' . $region->icon_image) }}" 
                                 alt="{{ $region->name }}" width="50px" height="50px">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fi-rr-map-marker text-white"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div>
                            <strong>{{ $region->name }}</strong>
                            <br>
                            <small class="text-muted">/wilayah-detail/{{ $region->slug }}</small>
                        </div>
                    </td>
                    <td>
                        @if($region->whatsapp)
                            <a href="https://wa.me/{{ $region->whatsapp }}" target="_blank" class="text-success text-decoration-none">
                                <i class="fi-rr-phone me-1"></i>
                                {{ $region->whatsapp }}
                            </a>
                        @else
                            <span class="badge bg-secondary">{{get_phrase('N/A')}}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $region->is_active ? 'bg-success' : 'bg-warning' }}">
                            {{ $region->is_active ? get_phrase('Active') : get_phrase('Inactive') }}
                        </span>
                    </td>
                    <td>
                        @if($region->created_at)
                            @if(is_string($region->created_at))
                                {{ date('d M Y', strtotime($region->created_at)) }}
                            @else
                                {{ $region->created_at->format('d M Y') }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="dropdown ol-icon-dropdown">
                            <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi-rr-menu-dots-vertical"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item fs-14px" href="{{route('admin.regions.show', $region->id)}}">
                                        {{get_phrase('View')}}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fs-14px" href="{{route('admin.regions.edit', $region->id)}}">
                                        {{get_phrase('Edit')}}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item fs-14px" href="/wilayah-detail/{{ $region->slug }}" target="_blank">
                                        {{get_phrase('Preview')}}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{route('admin.regions.toggle-status', $region->id)}}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item fs-14px">
                                            {{ $region->is_active ? get_phrase('Deactivate') : get_phrase('Activate') }}
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <a class="dropdown-item fs-14px" 
                                       onclick="delete_modal('{{route('admin.regions.destroy', $region->id)}}')" 
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
        @if(count($regions) > 0)
        <div class="d-flex justify-content-center mt-4">
            {{ $regions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection