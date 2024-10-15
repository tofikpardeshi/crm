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
                            <li class="breadcrumb-item active">{{ $locationName }}</li>
                        </ol>
                    </div>

                    @php
                        $empdata =DB::table('employees')->get();
                    @endphp
                    <div class="page-title-right mr-2 form-group d-flex">
                        <select id="emp_filter" class="selectpicker"
                        data-live-search="true" data-style="btn-light">
                            <option value="all">All Employees</option>
                            @foreach ($empdata as $item)
                                <option value="{{ $item->employee_name }}">{{ $item->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h4 class="page-title">{{ $locationName }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            @foreach ($employeeData as $locationWiseLeads)
                <div class="col-md-6 col-xl-3">

                    <a href="{{ url('employee-leads/' . encrypt($locationWiseLeads['emaployee_id']) . '/' . encrypt($locationWiseLeads['location_id'])) }}"
                        >
                        @php 
                            $LocationWiseEmpBookingCount = DB::table('leads')
                            ->where('assign_employee_id',$locationWiseLeads['emaployee_id'])
                             ->where('common_pool_status',0)
                            ->where('lead_status' ,14) 
                            ->where('location_of_leads',$locationWiseLeads['location_id'])->count();
                        @endphp
                        <div class="card-box" data-location="{{ $locationWiseLeads['employee_name'] }}">

                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-sm bg-soft-info rounded">
                                        <i class="fe-aperture avatar-title font-22 text-info"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="text-dark my-1"><span
                                                data-plugin="counterup">{{ $locationWiseLeads['employee_lead_count'] }}</span>
                                        </h3>
                                        <p class="text-muted mb-1 text-truncate">Leads Status</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                             <div class="d-flex justify-content-between">
                                <p class="text-muted mb-1 text-truncate ">Booking Confirm</p>
                            <h5 class="text-dark my-1 "><span>{{ $LocationWiseEmpBookingCount ?? 'N/A' }}</span>
                            </h5>
                            </div>
                                <h6 class="text-uppercase">{{ $locationWiseLeads['employee_name'] }} <span
                                        class="float-right">{{ $locationWiseLeads['leads_count'] }}%</span></h6>
                                <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ $locationWiseLeads['leads_count'] }}%">

                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-box-->
                    </a>
                </div> <!-- end col -->
            @endforeach
        </div>
    </div>


    </div> <!-- container -->
@endsection


@section('scripts')
    <script>
        $('#emp_filter').on('change', function() {
    var selectedLocation = $(this).val();
    
    // Show or hide items based on the selected location
    if (selectedLocation === 'all') {
        // Show all items
        $('.card-box').show();
    } else {
        // Hide all items and then show only those with the selected location
        $('.card-box').hide();
        $('.card-box[data-location="' + selectedLocation + '"]').show();
    }
});
    </script>
@endsection


