@extends('layouts.app')

@section('title', $region->meta_title ?: $region->name)
@section('meta_description', $region->meta_description)

@section('content')
<div class="container">
    @if($region->banner_image)
        <div class="region-banner mb-4">
            <img src="{{ $region->banner_image_url }}" 
                 alt="{{ $region->name }}" 
                 class="img-fluid w-100">
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            @if($region->page && $region->page->content)
                {!! $region->page->content !!}
            @else
                <h1>{{ $region->name }}</h1>
                <p>Selamat datang di wilayah {{ $region->name }}.</p>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($region->icon_image)
                        <img src="{{ $region->icon_image_url }}" 
                             alt="{{ $region->name }}" 
                             class="img-fluid mb-3"
                             style="max-height: 100px;">
                    @endif
                    
                    <h5>{{ $region->name }}</h5>
                    
                    <a href="{{ $region->whatsapp_link }}" 
                       class="btn btn-success btn-lg mt-3"
                       target="_blank">
                        <i class="bi bi-whatsapp"></i> Hubungi WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection