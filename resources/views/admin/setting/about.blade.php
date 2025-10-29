@extends('layouts.admin')
@section('title', get_phrase('About'))
@section('admin_layout')
    @php
        $curl_enabled = function_exists('curl_version');
    @endphp

    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('About This Application') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-7">
            <div class="ol-card p-4">
                <p class="title text-14px mb-3">{{ get_phrase('About this application') }}</p>
                <div class="ol-card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <div class="table-responsives">
                                        <table class="table eTable">
                                            <div class="chart-widget-list">
                                                <p class="border-bottom mb-2 pb-2 text-13px d-flex align-items-center">
                                                    <i class="fi-rr-hand-back-point-right me-3"></i>
                                                    {{ get_phrase('Software version') }}
                                                    <span class="ms-auto float-end ml-5">{{ get_settings('version') }}</span>
                                                </p>
                                                <p class="border-bottom mb-2 pb-2 text-13px d-flex align-items-center">
                                                    <i class="fi-rr-hand-back-point-right me-3"></i>
                                                    {{ get_phrase('Laravel version') }}
                                                    <span class="ms-auto float-end ml-5">{{ get_phrase('11.10.0') }}</span>
                                                </p>
       
                                                <p class="border-bottom mb-2 pb-2 text-13px d-flex align-items-center">
                                                    <i class="fi-rr-hand-back-point-right me-3"></i>
                                                    {{ get_phrase('Php version') }}
                                                    <span class="ms-auto float-end">{{ phpversion() }}</span>
                                                </p>
                                                <p class="border-bottom mb-2 pb-2 text-13px d-flex align-items-center">
                                                    <i class="fi-rr-hand-back-point-right me-3"></i>
                                                    {{ get_phrase('Curl enable') }}
                                                    <span class="ms-auto float-end">
                                                        @if ($curl_enabled)
                                                            <span class="eBadge ebg-soft-success">{{ get_phrase('enabled') }}</span>
                                                        @else
                                                            <span class="eBadge ebg-soft-danger">{{ get_phrase('disabled') }}</span>
                                                        @endif
                                                    </span>
                                                </p>


                                                <p class="border-bottom mb-2 pb-2 text-13px d-flex align-items-center">
                                                    <i class="fi-rr-hand-back-point-right me-3"></i>
                                                    {{ get_phrase('Get customer support') }}
                                                    <span class="ms-auto float-end"><a class="about-sc-one" href="http://t.me/jagosoftware" target="_blank"> <i class="bi bi-telegram"></i>
                                                            {{ get_phrase('Customer support') }}
                                                            <i class="fi-rr-navigation"></i>
                                                        </a> </span>
                                                </p>

                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
