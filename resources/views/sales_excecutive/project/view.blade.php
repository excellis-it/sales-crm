@extends('sales_excecutive.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | View Project Details
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">#{{ $project->client_name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Project</a></li>
                            <li class="breadcrumb-item active">View Project Details</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Client Name:-</div>
                                            <div class="text">
                                                {{ $project->client_name }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title">Client Email:-</div>
                                            <div class="text">
                                                {{ $project->client_email }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Phone:-</div>
                                            <div class="text">
                                                {{ $project->client_phone }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Address:-</div>
                                            <div class="text">
                                                {{ $project->client_address }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Website:-</div>
                                            <div class="text">
                                                <a href="{{ $project->website }}" target="blank">
                                                    {{ $project->website }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Business Name:-</div>
                                            <div class="text">
                                                {{ $project->business_name }}
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    {{-- <h3 class="card-title"><u>Billing Information</u> </h3> --}}

                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Project Type:-</div>
                                            <div class="text">
                                                <span class="">{{ $project->projectTypes->name ?? '' }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Project Value:-</div>
                                            <div class="text">
                                                {{ $project->project_value }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Project Upfront:-</div>
                                            <div class="text">
                                                {{ $project->project_upfront }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Due Amount:-</div>
                                            <div class="text">
                                                {{ (int)$project->project_value - (int)$project->project_upfront }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Currency:-</div>
                                            <div class="text">
                                                {{ $project->currency }}
                                            </div>
                                        </li>

                                        <li class="">
                                            <div class="title  ">Sale Date:-</div>
                                            <div class="text">
                                                {{ date('d-m-Y', strtotime($project->sale_date)) }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Project Opener:-</div>
                                            <div class="text">
                                                {{ $project->projectOpener->name ?? '' }}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    @endsection

    @push('scripts')
    @endpush
