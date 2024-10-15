@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Homents</a></li>
                            <li class="breadcrumb-item active">Productivity</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Today Productivity</h4>
                    @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }} </div>
                        @endif
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            @foreach ($employeeData as $empDeshboard)

            
                <div class="col-md-6 col-xl-3"> 
                     <a href="{{ url('employee-productivity-status/' . encrypt($empDeshboard['emaployee_id'])) }}"
                        >  
                        <div class="card-box">

                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-sm bg-soft-info rounded">
                                        <i class="fe-aperture avatar-title font-22 text-info"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="text-dark my-1"><span
                                                data-plugin="counterup">{{ $empDeshboard['leadCount'] }}</span>
                                        </h3>
                                        <p class="text-muted mb-1 text-truncate">Total Leads</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"> 
                            {{-- <div class="d-flex justify-content-between">
                                <p class="text-muted mb-1 text-truncate ">Booking Confirm</p>
                            <h5 class="text-dark my-1 "><span>{{ $LocationWiseEmpBookingCount ?? 'N/A' }}</span>
                            </h5>
                            </div> --}}
                            
                                <h6 class="text-uppercase">{{ $empDeshboard['emp_name'] }} 
                                    {{-- <span class="float-right">{{ $locationWiseLeads['leads_count'] }}%</span> --}}
                                </h6>
                                {{-- <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ $locationWiseLeads['leads_count'] }}%">

                                    </div>
                                </div> --}}
                            </div>
                        </div> <!-- end card-box-->
                    </a>  
                </div> <!-- end col -->
            @endforeach
        </div>
    </div>


    </div> <!-- container -->
@endsection
