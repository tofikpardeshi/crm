@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <style>
        .stat-block {
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            margin: 0 15px;
            margin-top: 0px;
            padding: 4px 10px;
            background: rgba(0, 0, 0, 0.05);
        }

        .stat-block .text-dark {
            margin-left: auto
        }
        #demo-foo-filtering_length{
            display: none;
        }
       /*   #demo-foo-filtering_paginate{
            display: none;
        } */
    </style>
    <div class="container-fluid" oncontextmenu="return false;">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    {{-- <div class="page-title-right">
                        <form action="{{ route('filter-by-project') }}" method="get">
                            @csrf
                            <div class="form-group d-flex">
                                {  <label for="status-select" class="mr-1">Sort By</label> 
                                <select class="custom-select" name="sortbyLead" id="status-select">
                                    <option value="" selected="">All</option>
                                    @foreach ($projectTypes as $projectType)
                                        <option value="{{ $projectType->project_type }}">
                                            {{ $projectType->project_type }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                    value="submit">Filter</button>
                            </div>
                        </form>
                    </div> --}}

                    <div class="page-title-right">
                        <div class="form-group d-flex">
                            <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                data-toggle="modal" data-target="#exampleModal" value="submit">Filter</button>
                        </div>

                        <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @php
                                                    $locations = DB::table('locations')->get();
                                                    $Employees = DB::table('employees')
                                                        ->where('organisation_leave', 0)
                                                        ->select('employees.employee_name', 'employees.id', 'employees.user_id')
                                                        ->orderBy('employee_name', 'asc')
                                                        ->get();
                                                    $ProjectName = DB::table('projects')
                                                        ->orderBy('project_name', 'asc')
                                                        ->get();
                                                    $CustomerTypes = DB::table('buyer_sellers')
                                                        ->select('name', 'id')
                                                        ->orderBy('name', 'asc')
                                                        ->get();

                                                    // $employeeCoFollowUps = DB::table('employees')
                                                    // ->join('leads','leads.co_follow_up','=','employees.user_id')
                                                    // ->select('employees.employee_name','employees.user_id')
                                                    // ->get();

                                                    $ChannelPartners = DB::table('users')
                                                        ->where('roles_id', 10)
                                                        ->select('name', 'roles_id', 'id')
                                                        ->get();

                                                    // dd($employeeCoFollowUps);

                                                @endphp
                                                <form action="{{ route('filter-by-project') }}" method="get">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Customer Type<span
                                                                class="text-danger"></label>
                                                        <select name="customer_type" id="CustomerType" class="selectpicker"
                                                            data-style="btn-light">
                                                            <option value="" selected="">Select</option>
                                                            @foreach ($CustomerTypes as $CustomerType)
                                                                <option value="{{ $CustomerType->id }}">
                                                                    {{ $CustomerType->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Customer Requirement<span
                                                                class="text-danger"></label>
                                                        <select name="sortbyLead" id="projectType" class="selectpicker"
                                                            data-style="btn-light">
                                                            <option value="" selected="">Select</option>
                                                            @foreach ($projectTypes as $projectType)
                                                                <option value="{{ $projectType->project_type }}">
                                                                    {{ $projectType->project_type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    @if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11)
                                                        <div class="form-group mb-3">
                                                            <label for="example-email">Employee <span
                                                                    class="text-danger"></label>
                                                            <select name="employee" class="selectpicker"
                                                                data-style="btn-light" id="employee">
                                                                <option value="">Select</option>
                                                                @foreach ($Employees as $Employee)
                                                                    <option value="{{ $Employee->id }}">
                                                                        {{ $Employee->employee_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                    @endif
                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Location<span
                                                                class="text-danger"></label>
                                                        <select name="BuyingLocation" class="selectpicker"
                                                            data-style="btn-light" id="BuyingLocation">
                                                            <option value="">Select</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->id }}">
                                                                    {{ $location->location }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Project Discussed/Visited<span
                                                                class="text-danger"></label>
                                                        <select name="projectName" class="selectpicker"
                                                            data-style="btn-light" id="projectName">
                                                            <option value="">Select</option>
                                                            @foreach ($ProjectName as $Project)
                                                                <option value="{{ $Project->id }}">
                                                                    {{ $Project->project_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Follow Up Buddy<span
                                                                class="text-danger"></label>
                                                        <select name="followupbuddy" class="selectpicker"
                                                            data-style="btn-light" id="followupbuddy">
                                                            <option value="">Select</option>
                                                            @foreach ($Employees as $employeeCoFollowUp)
                                                                <option value="{{ $employeeCoFollowUp->user_id }}">
                                                                    {{ $employeeCoFollowUp->employee_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Channel Partner<span
                                                                class="text-danger"></label>
                                                        <select name="ChannelPartner" class="selectpicker"
                                                            data-style="btn-light" id="ChannelPartner">
                                                            <option value="">Select</option>
                                                            @foreach ($ChannelPartners as $ChannelPartner)
                                                                <option value="{{ $ChannelPartner->id }}">
                                                                    {{ $ChannelPartner->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>



                                                    <div class="modal-footer">
                                                        <button class="btn btn-success mx-1" style="height: 35px;"
                                                            type="submit" name="submit" value="submit">filter</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $empId = DB::table('employees')
                            ->where('user_id', Auth::user()->id)
                            ->first();

                        $LeadCountAdmin = DB::table('leads')
                            ->where('lead_status', '!=', 8)
                            ->where('lead_status', '!=', 9)
                            ->where('lead_status', '!=', 10)
                            ->where('lead_status', '!=', 11)
                            ->where('lead_status', '!=', 12)
                            ->where('lead_status', '!=', 14)
                            ->where('common_pool_status', 0)
                            ->count();

                        $LeadCountEmployee = DB::table('leads')
                            ->where('lead_status', '!=', 8)
                            ->where('lead_status', '!=', 9)
                            ->where('lead_status', '!=', 10)
                            ->where('lead_status', '!=', 11)
                            ->where('lead_status', '!=', 12)
                            ->where('lead_status', '!=', 14)
                            ->where('assign_employee_id', '=', $empId->id)
                            ->where('common_pool_status', 0)
                            ->count();
                            
                         $coFollowsUpCount = DB::table('leads')
                                    // ->where('assign_employee_id', '=', $empId->id) 
                                    ->where('common_pool_status', 0)
                                    ->where('co_follow_up', $empIdStatus->user_id)
                                    ->whereNotIn('lead_status', [8, 9, 10, 11, 12,14,16])
                                    ->count();

                        $empIdD = DB::table('employees')
                            ->where('user_id', Auth::user()->id)
                            ->where('role_id', 8)
                            ->first();
                        $LeadCountRwa = DB::table('leads')
                            ->where('lead_status', '!=', 8)
                            ->where('lead_status', '!=', 9)
                            ->where('lead_status', '!=', 10)
                            ->where('lead_status', '!=', 11)
                            ->where('lead_status', '!=', 12)
                            ->where('lead_status', '!=', 14)
                            ->where('rwa', '=', Auth::user()->id ?? null)
                            ->where('common_pool_status', 0)
                            ->count();
                    @endphp

                    {{-- <h4 class="page-title my-1">Leads</h4> --}}
                    <h4 class="page-title my-1">Leads
                        @if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11)
                            >
                            {{ $LeadCountAdmin }}
                        @elseif (Auth::user()->roles_id == 10)
                            > {{ $LeadCountRwa }}
                        @else
                            > {{ $LeadCountEmployee ? $LeadCountEmployee + $coFollowsUpCount : $LeadCountEmployee }}
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                @if (session()->has('Delete'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('Delete') }} </div>
                @endif

                @if (session()->has('message'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('message') }} </div>
                @endif


                @if (session()->has('moveCommonPool'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('moveCommonPool') }}
                        <a class="text-success" href="{{ url('common-pool') }}">(Go to common-pool)</a>
                    </div>
                @endif

                @if (session()->has('BookingConfirm'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('BookingConfirm') }}
                        @if (Auth::user()->roles_id == 1)
                            <a class="text-success" href="{{ url('booking-confirm/index') }}">(Go to Booking Confirm)</a>
                        @else
                        @endif

                    </div>
                @endif


                @if (session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{ session()->get('success') }} </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}

                                           

                                    @if (Auth::user()->roles_id != 1)
                                        @can('Employee Leads Reports')
                                            <a type="button" name="lreports"
                                                class="btn btn-info waves-effect waves-light mb-2"
                                                href="{{ route('employee-lead-export') }}">Employee Lead Reports</a>
                                        @endcan
                                    @endif
                                    @if (Auth::user()->roles_id == 1)
                                        <a type="button" class="btn btn-info waves-effect waves-light mb-2"
                                            href="{{ url('/generate-csv') }}">Download Contact</a>
                                    @endif
                                    @can('Lead Reports')
                                        <a type="button" name="lreports"
                                            class="btn btn-primary waves-effect waves-light mb-2"
                                            href="{{ route('leads-export') }}">Lead Reports</a>
                                    @endcan
                                    @can('Create')
                                        <a type="button" class="btn btn-success waves-effect waves-light mb-2 btn-darkblue"
                                            href="{{ route('create-leads') }}">Add New</a>
                                    @endcan

                                </div>
                            </div><!-- end col-->
                        </div>
                        @if (Auth::user()->roles_id == 1)
                            <h2>Admin Leads</h2>
                            <div class="switch-field">
                                <input type="radio" id="radio-one" name="switch-one"
                                    onchange="filterdata(this.value,'6')" value="1" />
                                <label for="radio-one">Yes</label>
                                <input type="radio" id="radio-two" name="switch-one"
                                    onchange="filterdata(this.value,'6')" value="0" checked />
                                <label for="radio-two">No</label>
                            </div>
                        @endif



                        <div class="row">
                            {{-- <div class="col-md-2">
                                <label for="">Search</label>
                                <input id="demo-foo-search" type="text" placeholder="Search"
                                    class="form-control form-control-sm" autocomplete="on">
                            </div> --}}

                            {{--  <div class="col-md-2">
                                <label for="">Featured Leads</label>
                                <select id="select_tag" class="form-control select2-original">
                                     <option value="">Select Anyone</option>
                                    <option value="isFeatured">Featured</option>
                                    <option value="notFeatured" selected>Not Featured</option>
                                </select>
                            </div>  --}}


                            <div class="col-md-2">
                                <label for="">Featured Lead</label>
                                <select class="form-control" onchange="filterdata(this.value,'1')">
                                    <option value="">See All</option>
                                    <option value="isFeatured">Featured</option>
                                    {{-- <option value="notFeatured">Not Featured</option> --}}
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                @php
                                    $LeadStatus = DB::table('lead_statuses')
                                        ->where('name', '!=', 'Case Closed - Enquiry Only')
                                        ->where('name', '!=', 'Case Closed - Not Interested')
                                        ->where('name', '!=', 'Case Closed - Low Budget')
                                        ->where('name', '!=', 'Case Closed - Already Booked')
                                        ->where('name', '!=', 'Case Closed - For Common Pool')
                                        ->where('name', '!=', 'Reallocate from Common Pool')
                                        ->where('name', '!=', 'Booking Confirmed')
                                        ->get();

                                    $budgets = DB::table('budget')->get();

                                    $existingProjects = DB::table('projects')
                                        ->select('project_name', 'id')
                                        ->get();
                                @endphp
                                <label for="" style="font-size: 11px">Lead Type</label>
                                <select class="form-control" onchange="filterdata(this.value,'2')">
                                    <option value="">See All</option>
                                    <option value="Hot">Hot</option>
                                    <option value="Cold">Cold</option>
                                    <option value="WIP">WIP</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px"> Lead Status</label>
                                <select class="form-control" onchange="filterdata(this.value,'3')">
                                    <option value="">See All</option>
                                    @foreach ($LeadStatus as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for=""> Existing Property </label>
                                <select id="existing-property" class="form-control" onchange="ExistingApplyFilter()">
                                    <option value="">See All</option>
                                    @foreach ($existingProjects as $existingProject)
                                        <option value="{{ $existingProject->project_name }}">
                                            {{ $existingProject->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for=""> Creation Date </label>
                                <td><input type="date" class="form-control" id="creation-date"
                                        onchange="CreateDateApplyFilter()" data-date-split-input="true"
                                        min="<?= date('d-m-Y') ?>"></td>
                            </div>


                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Today's Date follow up</label>
                                <td><input type="date" class="form-control" id="datefilterfrom"
                                        onchange="filterdata(this.value,'4')" data-date-split-input="true"
                                        min="<?= date('d-m-Y') ?>"></td>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Previous Follow-up</label>
                                <input type="date" class="form-control" id="datefilterPrevious"
                                    onchange="filterdata(this.value,'5')" data-date-split-input="true"
                                    max="<?= date('d-m-Y') ?>">
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Start date:</label>
                                <td><input type="date" name="min" class="form-control"
                                        onchange="startdatafilters(this.value)"></td>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1">
                                <label for="" style="font-size: 11px">End date:</label>
                                <td><input type="date" name="max" onchange="Enddatafilters(this.value)"
                                        class="form-control"></td>

                            </div>
                            <div class="col-md-2 ">
                                <label for="">Leads Search</label>
                                <td>
                                    <div>
                                        <input type="text" onkeyup="filterdata(this.value,'7')" class="form-control"
                                            id="searchInput">
                                    </div>
                                    <a id="clearButton" class="btn btn-default" style="display: none;"
                                        onclick="clearInput(event)">x</a>

                                </td>

                            </div>


                            <div class="col-md-2">
                                <label for="">Free Text Search</label>
                                <form action="{{ route('free-search') }}" method="get">
                                    @csrf
                                    <td><input type="text" name="free_search" class="form-control"></td>
                                </form>
                            </div>
                        </div>
                         
                        <form action="{{ url('/lead-search') }}" method="get">
                            @csrf
                            <div class="row d-flex">
                        
                                <div class="col-md-2">
                                    <label for="budget">Budgets</label>
                                    <select id="budget" name="budget" class="form-control">
                                        <option value="">See All</option>
                                        @foreach ($budgets as $Budget)
                                            <option value="{{ $Budget->budget }}">
                                                {{ $Budget->budget }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <div class="col-md-2">
                                    <label for="budget">Investor</label>
                                    <select id="investor" name="investor" class="form-control">
                                        <option value="">See All</option> 
                                        <option value="YES">Yes</option> 
                                        <option value="NO">No</option> 
                                    </select>
                                </div>
                        
                                <div class="align-self-center">
                                    <button type="submit" class="btn btn-sm btn-info py-1 mt-3">Search</button>
                                </div>

                                @if(request()->has('budget') && request()->has('investor')) 
                                <!-- Add your content here -->
                                <div class="align-self-center">
                                    <a href="{{ url('/leads') }}" class="btn btn-sm btn-danger py-1 mt-3 ml-1">Back</a> 
                                </div>
                                @endif
                        
                            </div>
                        </form>
                            



                        {{-- <div class="dataTables_length" id="demo-foo-filtering_length">
                            <label>Show 
                                <select name="demo-foo-filtering_length" aria-controls="demo-foo-filtering"
                            class="">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                          </select> entries</label>
                    </div> --}}

                        <div class="row">
                            @php
                              
                                $leadsStatus = DB::table('lead_statuses')
                                    ->whereNotIn('id', [16, 8, 9, 10, 11, 12])
                                    ->get();

                               

                                $IsStatusID = isset($empIdStatus) ? $empIdStatus->id : $empId->id;
                                
                                $currentDate = Carbon\Carbon::now();

                                $expiredLead = DB::table('leads')
                                    ->where('assign_employee_id', $IsStatusID)
                                    ->where('next_follow_up_date', '<', $currentDate)
                                    ->where('common_pool_status', 0)
                                    ->where('lead_status', '!=', 14)
                                    ->count();
                                     

                                $coFollowsUpCount = DB::table('leads')
                                    ->where('assign_employee_id', $IsStatusID)
                                    ->where('lead_status', $item->id)
                                    ->where('common_pool_status', 0)
                                   ->orWhere('co_follow_up', $empIdStatus->user_id)
                                    ->whereNotIn('lead_status', [8, 9, 10, 11, 12])
                                    ->count();

                               

                                $rwaCount = 0; 
                                $empRwaID = DB::table('leads')
                                    ->select('leads.rwa')
                                    ->where('assign_employee_id', $IsStatusID)
                                    ->where('rwa', '!=', null)
                                    ->groupBy('rwa')
                                    ->get();
                                   
                                    

                                foreach ($empRwaID as $rwa) {
                                    $rwaCount++; //
                                     
                                }

                            @endphp


                            <div class="col-md-2 col-5 col-lg-2 mt-1 stat-block">
                                <div class=" d-flex">
                                    <a href="{{ url('employee-lead-status/' . encrypt($IsStatusID)) . '/expired-leads' }}"
                                        class="mr-2" target="blank">
                                        {{ 'Expired Leads' }} </a>
                                    <span class="text-dark">
                                        {{ $expiredLead }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2 col-5 col-lg-2 mt-1 stat-block">
                                <div class=" d-flex">
                                    <a href="{{ url('employee-lead-status/' . encrypt($IsStatusID)) . '/co-follow-up' }}"
                                        class="mr-2" target="blank">
                                        {{ 'Follow Up Buddy' }} </a>
                                    <span class="text-dark">
                                        {{ $coFollowsUpCount }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2 col-5 col-lg-2 mt-1 stat-block">
                                <div class=" d-flex">
                                    <a href="{{ url('employee-lead-status/' . encrypt($IsStatusID)) . '/channel-partner' }}"
                                        class="mr-2" target="blank">
                                        {{ 'Channel Partner' }} </a>
                                    <span class="text-dark">
                                        {{ $rwaCount }}
                                    </span>
                                </div>
                            </div>

                            @foreach ($leadsStatus as $item)
                                {{-- @if (Auth::user()->roles_id == 1)
                            <div class="col-md-3 col-6 col-lg-3 my-2">
                                <div class="card-box d-flex justify-content-between">
                                    <a href="{{ url('employee-lead-status/' . encrypt($empId->id)). '/' . $item->id}}" target="blank">{{ $item->name }} </a>
                                    <span class="text-dark" >
                                        {{ 
                                        $newLeadStatusCount = DB::table('leads') 
                                        // ->where('assign_employee_id', $empId->id)
                                        ->where('lead_status', $item->id)
                                        ->count();  
                                        }}
                                    </span>
                                </div>
                            </div>  
                            @else --}}

                                <div class="col-md-2 col-5 col-lg-2 mt-1 stat-block">
                                    <div class=" d-flex">
                                        @php
                                            $IsStatusID = isset($empIdStatus) ? $empIdStatus->id : $empId->id; 
                                        @endphp
                                        <a href="{{ url('employee-lead-status/' . encrypt($IsStatusID)) . '/' . $item->id }}"
                                            class="mr-2" target="blank">{{ $item->name }} </a>
                                        <span class="text-dark">
                                            {{ $newLeadStatusCount = DB::table('leads')->where('assign_employee_id', $IsStatusID)->where('lead_status', $item->id)->where('common_pool_status', 0)->count() }}
                                        </span>
                                    </div>
                                </div>
                                {{-- @endif --}}
                            @endforeach
                        </div>


                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box" style="padding: 0; padding-top: 20px; box-shadow:0px 0px">
                                        <table id="demo-foo-filtering"
                                            class="table table-centered table-nowrap table-hover mb-0"
                                            onmousedown='return false;' onselectstart='return false;'
                                            data-placement="top" title="Do not copy sensitive data" data-page-size="100">
                                            <thead>
                                                <tr>
                                                    <th style="width: 82px;" data-sortable="false">Action</th>
                                                    <th>Creation Date</th>
                                                    <th style="max-width:160px">Customer Name</th>
                                                    <th>Hist</th>
                                                    <th>Assigned Lead</th>
                                                    <th>Mobile</th>
                                                    <th>Last Contacted</th>
                                                    <th>Follow Up Date</th>
                                                    <th>Time</th>
                                                    <th>Cust.Req</th>
                                                    <th>Customer Type</th>
                                                    <th>Existing Property</th>
                                                    <th>Type</th>
                                                    <th>Budget</th>
                                                    <th>Lead Status</th>
                                                    <th class="d-none">Customer Interaction</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list_tbody">
                                                @foreach ($leads as $key => $lead)
                                                    @php
                                                        $lead_type_bif = DB::table('lead_type_bifurcation')
                                                            ->where('id', $lead->lead_type_bifurcation_id)
                                                            ->first();

                                                        $leadStatusName = DB::table('leads')
                                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                            ->select('leads.*', 'lead_statuses.name')
                                                            ->where('leads.id', $lead->id)
                                                            ->first();

                                                        $currentIntractionHistory = DB::table('lead_status_histories')
                                                            ->where('lead_id', $lead->id)
                                                            ->latest('lead_status_histories.created_at')
                                                            ->first();

                                                        $existingProperty = $lead->existing_property;
                                                        $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                                                        $existingPropertyCount = count($existingProjects);

                                                        $existingPropertyIds = explode(',', $lead->existing_property);

                                                        $projects = App\Models\Project::whereIn('id', $existingPropertyIds)
                                                            ->select('project_name', 'id')
                                                            ->get();

                                                        $projectNames = $projects->pluck('project_name')->toArray();

                                                        $callHistory = DB::table('call_logs')
                                                            ->where('callernumber', $lead->contact_number)
                                                            ->exists();

                                                    @endphp
                                                    {{-- Modal For Current Interaction --}}

                                                    <div class="modal fade" id="LeadIntractionModal{{ $lead->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Current
                                                                        Interaction</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                @if ($currentIntractionHistory == null)
                                                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="9" disabled></textarea>
                                                                                @else
                                                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="9" disabled>{!! strip_tags($currentIntractionHistory->customer_interaction) !!}</textarea>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Modal For Current Interaction  end --}}

                                                    <tr id="task-{{ $key + 1 }}" class="task-list-row"
                                                        data-task-id="{{ $key + 1 }}">
                                                        {{-- <tr> --}}
                                                        <td>

                                                            <span class="clipboard action-icon " data-toggle="tooltip"
                                                                data-placement="top" title="Copy Lead Info"
                                                                onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($lead->id)) }}')">
                                                                <i class="mdi mdi-content-copy"></i>
                                                            </span>

                                                            {{-- @if ($lead->lead_status == 1) --}}
                                                            @can('Update')
                                                                {{-- <a href="{{ url('lead-status-isNew/' . encrypt($lead->id)) }}"
                                                                        class="action-icon">
                                                                        <i class="mdi mdi-square-edit-outline">
                                                                        </i></a> --}}
                                                            @endcan
                                                            @can('History-View')
                                                                <a data-toggle="tooltip" data-placement="top"
                                                                    title="Check Status"
                                                                    href="{{ url('lead-status-isHistory/' . encrypt($lead->id)) }}"
                                                                    class="action-icon" target="_blank">
                                                                    <img style="width:20px; margin-bottom:2px"
                                                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                </a>
                                                            @endcan
                                                            {{-- @else --}}
                                                            {{-- @can('Update') --}}
                                                            {{-- <a href="{{ url('edit-leads/' . encrypt($lead->id)) }}"
                                                                        class="action-icon">
                                                                        <i class="mdi mdi-square-edit-outline">
                                                                        </i></a> --}}
                                                            {{-- @endcan
                                                                @can('History-View')
                                                                    <a data-toggle="tooltip" data-placement="top"
                                                                        title="Check Status"
                                                                        href="{{ url('lead-status/' . encrypt($lead->id)) }}"
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>

                                                                     
                                                                @endcan
                                                            @endif --}}
                                                            {{-- <a href="{{ url('lead-delete/' . $lead->id) }}" class="action-icon">
                                                                                        <i class="mdi mdi-delete"></i></a>  --}}

                                                            {{-- <button type="button" class="btn btn-primary updateStatus"
                                                                                        data-toggle="modal" value="{{ $lead->id }}"
                                                                                        data-target="#exampleModal">
                                                                                        status
                                                                                    </button> --}}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i') }}
                                                        </td>
                                                        <td class="table-user text-capitalize"
                                                            style="white-space:normal; min-width:220px; max-width:220px;">
                                                            @php
                                                                $currentDateTime = Carbon\Carbon::now();
                                                                $givenDateTimeString = $lead->next_follow_up_date;

                                                                // Replace this with your desired date and time

                                                                $givenDateTime = new DateTime($givenDateTimeString);
                                                                $givenDateTimeNewLead = new DateTime($lead->created_at);

                                                                $currentDateTime = new DateTime();

                                                                $currentDateTime = \Carbon\Carbon::now();

                                                                // Calculate the time difference
                                                                $timeDifference = $currentDateTime->diff($givenDateTime);
                                                                $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
 
                                                            @endphp
                                                            @if ($lead->is_featured == 1)
                                                                {{-- <i class="fa solid fa-handshake text-primary"
                                                                    title="Channel Partner"></i> --}}
                                                                <a href="#"
                                                                    class="text-body font-weight-semibold updateStatus"
                                                                    value="{{ $lead->id }}" data-toggle="modal"
                                                                    data-target="#LeadIntractionModal{{ $lead->id }}">
                                                                    @if ($lead->lead_status == 1)
                                                                        <span class="badge badge-warning rounded-circle"
                                                                            title="New Lead">
                                                                            {{ $timeDifferenceNew->days }}
                                                                        </span>
                                                                    @elseif($givenDateTime > $currentDateTime)
                                                                        <i class="fa fa-circle text-success"
                                                                            title="Next Follow-Up Time "></i>
                                                                    @elseif ($givenDateTime < $currentDateTime)
                                                                        <span class="badge badge-danger rounded-circle"
                                                                            title="Next Follow-Up Time Lapsed">
                                                                            {{ $timeDifference->days }}

                                                                        </span>

                                                                </a>
                                                            @endif

                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}" data-toggle="modal"
                                                                data-target="#LeadIntractionModal{{ $lead->id }}">
                                                                {{ $lead->lead_name }}
                                                                
                                                                

                                                            </a>

                                                            <input type="text" value="{{ $lead->regular_investor }}" hidden> 
                                                            {{-- <a href="{{ url('call-history/' . encrypt($lead->id))}}" target="_blank" @if ($callHistory)@endif>
                                                                        <span> <i class="fa fa-history text-info" title="call-history"></i></i>
                                                                    </a>  --}}



                                                            <i class="fas fa-user-plus text-info" title="Follow Up Buddy"
                                                                @if ($lead->co_follow_up !== null && $lead->is_featured == 1) @else
                                                                        style="display:none;" @endif>

                                                                <i class="fa solid fa-handshake text-primary"
                                                                    title="Channel Partner"
                                                                    @if ($lead->rwa !== null && $lead->is_featured == 1) @else
                                                                        style="display:none;" @endif></i>
                                                            </i>
                                                            <span>

                                                                <span class="badge badge-pill badge-primary"
                                                                    title="{{ implode(',', $projectNames) }}"
                                                                    @if ($existingPropertyCount > 0) @else
                                                                        style="display:none;" @endif>
                                                                    {{ $existingPropertyCount }}
                                                                </span>

                                                                <i class="fa fa-star text-success"></i></span></a>
                                                        @elseif ($lead->co_follow_up != null)
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}" data-toggle="modal"
                                                                data-target="#LeadIntractionModal{{ $lead->id }}">
                                                                @if ($lead->lead_status == 1)
                                                                    <span class="badge badge-warning rounded-circle"
                                                                        title="New Lead">
                                                                        {{ $timeDifferenceNew->days }}

                                                                    </span>
                                                                @elseif($givenDateTime > $currentDateTime)
                                                                    <i class="fa fa-circle text-success"
                                                                        title="Next Follow-Up Time "></i>
                                                                @elseif ($givenDateTime < $currentDateTime)
                                                                    <span class="badge badge-danger rounded-circle"
                                                                        title="Next Follow-Up Time Lapsed">
                                                                        {{ $timeDifference->days }}
                                                                    </span>
                                                                @endif
                                                                {{ $lead->lead_name }}
                                                                @if ($callHistory)
                                                                    <a href="{{ url('call-history/' . encrypt($lead->id)) }}"
                                                                        target="_blank">
                                                                        <span> <i class="fa fa-history text-info"
                                                                                title="call-history"></i></i>
                                                                    </a>
                                                                    <span> <i class="fas fa-user-plus text-info"
                                                                            title="Follow Up Buddy"></i>
                                                                        <span>
                                                            </a>
                                                        @else
                                                            <span> <i class="fas fa-user-plus text-info"
                                                                    title="Follow Up Buddy"></i>
                                                                <span>

                                                                    <span class="badge badge-pill badge-primary"
                                                                        title="{{ implode(',', $projectNames) }}"
                                                                        @if ($existingPropertyCount > 1) @else
                                                                            style="display:none;" @endif>
                                                                        {{ $existingPropertyCount }}
                                                                    </span>
                                                                    </a>
                                                @endif
                                            @elseif ($lead->rwa != null)
                                                <a href="#" class="text-body font-weight-semibold updateStatus"
                                                    value="{{ $lead->id }}" data-toggle="modal"
                                                    data-target="#LeadIntractionModal{{ $lead->id }}">
                                                    @if ($lead->lead_status == 1)
                                                        <span class="badge badge-warning rounded-circle" title="New Lead">
                                                            {{ $timeDifferenceNew->days }}

                                                        </span>
                                                    @elseif($givenDateTime > $currentDateTime)
                                                        <i class="fa fa-circle text-success"
                                                            title="Next Follow-Up Time"></i>
                                                    @elseif ($givenDateTime < $currentDateTime)
                                                        <span class="badge badge-danger rounded-circle"
                                                            title="Next Follow-Up Time Lapsed">
                                                            {{ $timeDifference->days }}
                                                        </span>
                                                    @endif
                                                    {{ $lead->lead_name }}
                                                    <a href="{{ url('call-history/' . encrypt($lead->id)) }}"
                                                        target="_blank" @if ($callHistory) @endif>
                                                        <span> <i class="fa fa-history text-info"
                                                                title="call-history"></i></i>
                                                    </a>
                                                    <span> <span>
                                                            <i class="fa solid fa-handshake text-primary"
                                                                title="Channel Partner"></i>
                                                </a>
                                            @else
                                                <a href="#" class="text-body font-weight-semibold updateStatus"
                                                    value="{{ $lead->id }}" data-toggle="modal"
                                                    data-target="#LeadIntractionModal{{ $lead->id }}">
                                                    @if ($lead->lead_status == 1)
                                                        <span class="badge badge-warning rounded-circle" title="New Lead">
                                                            {{ $timeDifferenceNew->days }}


                                                        </span>
                                                    @elseif($givenDateTime > $currentDateTime)
                                                        <i class="fa fa-circle text-success"
                                                            title="Next Follow-Up Time "></i>
                                                    @elseif ($givenDateTime < $currentDateTime)
                                                        <span class="badge badge-danger rounded-circle"
                                                            title="Next Follow-Up Time Lapsed">
                                                            {{ $timeDifference->days }}
                                                        </span>
                                                    @endif
                                                    {{ $lead->lead_name }}
                                                </a>
                                                @if ($callHistory)
                                                    <a href="{{ url('call-history/' . encrypt($lead->id)) }}"
                                                        target="_blank">
                                                        <span> <i class="fa fa-history text-info"
                                                                title="call-history"></i></i>
                                                    </a>
                                                @endif

                                                <span class="badge badge-pill badge-primary"
                                                    title="{{ implode(',', $projectNames) }}"
                                                    @if ($existingPropertyCount > 0) @else
                                                                        style="display:none;" @endif>
                                                    {{ $existingPropertyCount }}
                                                </span>
                                                @endif

                                                </td>

                                                @php
                                                    $LeadCount = DB::table('lead_status_histories')
                                                        ->where('lead_id', $lead->id)
                                                        ->count();
                                                @endphp

                                                <td class="text-center">
                                                    <span>{{ $LeadCount }}</span>
                                                </td>

                                                <td>
                                                    @php

                                                        $trimNumberEmp = trim(isset($lead->official_phone_number));

                                                        if (isset($lead->emp_country_code) == null) {
                                                            $country_code_emp = ['1' => '']; // Initialize an empty array as a fallback
                                                        } else {
                                                            $country_code_emp = $country_code_emp = substr($lead->emp_country_code, 1);
                                                        }

                                                        $NumberEmp = DB::table('employees')
                                                        ->where('id',$lead->assign_employee_id)->first();

                                                        // if (empty($country_code_emp) || count($country_code_emp) < 2) {
                                                        //     // Handle the case where the array is empty or doesn't have at least two elements
//     // You can assign a default value or handle it according to your requirement
//     $whatsappUrl = '';
                                                        // } else {
                                                        //     $whatsappUrl = "https://api.whatsapp.com/send/?phone={$country_code_emp[1]}{$lead->official_phone_number}";
                                                        // }

                                                    @endphp

                                                    {{  $lead->employee_name  }}
                                                    <a href="https://api.whatsapp.com/send/?phone={{ ltrim($NumberEmp->emp_country_code, '+')}}{{ $NumberEmp->official_phone_number }}"
                                                        target="_blank">
                                                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                    </a>
                                                    {{-- <a href="https://api.whatsapp.com/send/?phone={{ $country_code_emp[1] }}{{ $lead->official_phone_number }}"
                                                        target="_blank">
                                                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                    </a> --}}
                                                    @if (!empty($whatsappUrl))
                                                    @else
                                                        <!-- Add alternative content or handle the case when WhatsApp URL is not available -->
                                                    @endif
                                                </td>
                                                <td>

                                                    @php
                                                    
                                                     
                                                        $trimNumber = trim($lead->contact_number);
                                                        
                                                        if ($lead->country_code == null) {
                                                            $country_code = ['1' => ''];
                                                        } else {
                                                            $country_code = explode('+', trim($lead->country_code));
                                                        }

                                                    @endphp
                                                    @if (Auth::user()->roles_id == 1)
                                                        {{-- <a class="text-muted"
                                                                    href="tel:{{$country_code[1]}}{{ $trimNumber }}">{{$country_code[1]}} {{ $trimNumber }}</a> --}}
                                                        <a
                                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $lead->official_phone_number }}&phone2={{ $lead->contact_number }}">
                                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                                            {{-- <img src="{{ asset('/images/20230919_191308_0000.png') }}" alt="" class="img-fluid"> --}}
                                                        </a>

                                                        <a class="text-muted"
                                                            href="tel:{{ $lead->country_code }}{{ $trimNumber }}">{{ $lead->country_code }}
                                                            {{ $trimNumber }}</a>
                                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] }}{{ $trimNumber }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="https://www.google.com/search?q={{ $trimNumber }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-google"></i>
                                                        </a>

                                                        @if ($lead->dnd == 1)
                                                            <i title="Do Not Disturb"
                                                                class="mdi mdi-circle text-danger"></i>
                                                        @else
                                                        @endif
                                                    @else
                                                        <a
                                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $lead->official_phone_number }}&phone2={{ $lead->contact_number }}">
                                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="text-muted"
                                                            href="tel:{{ $country_code[1] }}{{ $trimNumber }}">{{ substr_replace($trimNumber, '******', 0, 6) }}</a>
                                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] }}{{ $trimNumber }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="https://www.google.com/search?q={{ $trimNumber }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-google"></i>
                                                        </a>

                                                        @if ($lead->dnd == 1)
                                                            <i class="mdi mdi-circle text-danger"></i>
                                                        @else
                                                        @endif
                                                    @endif
                                                </td>
                                                {{-- last Connect Date --}}
                                                <td>
                                                    @if ($lead->last_contacted == null)
                                                        N/A
                                                    @else
                                                        {{ \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i') }}
                                                    @endif
                                                </td>
                                                {{-- fllow up Date --}}
                                                <td>
                                                    @if ($lead->next_follow_up_date == null)
                                                        N/A
                                                    @else
                                                        {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y') }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($lead->next_follow_up_date == null)
                                                        N/A
                                                    @else
                                                        {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i') }}
                                                    @endif
                                                </td>
                                                {{-- project type --}}
                                                <td class="text-capitalize" style="max-width:250px; white-space: normal">

                                                    {{ $lead->project_type }}
                                                </td>
                                                {{-- customer type --}}
                                                @php
                                                    $customerType = DB::table('leads')
                                                        ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                                        ->select('leads.*', 'buyer_sellers.name')
                                                        ->where('leads.id', $lead->id)
                                                        ->first(); 
                                                        
                                                @endphp
                                                @if ($customerType == null)
                                                    <td>N/A</td>
                                                @else
                                                    <td>
                                                        {{ $customerType->name }}
                                                    </td>
                                                @endif

                                                <td>
                                                    @php
                                                        $existingProject = DB::table('projects')
                                                            ->select('project_name', 'id')
                                                            ->where('id', $lead->existing_property)
                                                            ->get()
                                                            ->pluck('project_name')
                                                            ->implode(', ');
                                                    @endphp
                                                    {{ $existingProject != null ? $existingProject : 'N/A' }}

                                                    <span class="badge badge-pill badge-primary"
                                                        title="{{ implode(',', $projectNames) }}"
                                                        @if ($existingPropertyCount > 0) @else
                                                                        style="display:none;" @endif>
                                                        {{ $existingPropertyCount }}
                                                    </span>
                                                </td>


                                                @if ($lead->lead_type_bifurcation_id == null)
                                                    N/A
                                                @else
                                                    <td>
                                                        {{ $lead_type_bif->lead_type_bifurcation }}
                                                    </td>
                                                @endif

                                                @if ($lead->budget == null)
                                                    <td>N/A</td>
                                                @else
                                                    <td>
                                                        {{ $lead->budget }}
                                                    </td>
                                                @endif

                                                @if ($leadStatusName == null)
                                                    <td>N/A</td>
                                                @else
                                                    <td>
                                                        {{ $leadStatusName->name }}
                                                    </td>
                                                @endif
                                                
                                                <td class="d-none">
                                                    {{ $lead-> customer_interaction }}
                                                </td>

                                                </tr>
                                                @endforeach
                                            </tbody>

                                            {{-- <tfoot>
                                                <tr class="active">
                                                    <td colspan="10">
                                                        <div class="text-right"> --}}
                                            {{-- {{ $leads->links('pagination::bootstrap-4')  }} --}}
                                            {{-- <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tfoot> --}}

                                        </table>
                                    </div> <!-- end card-box -->
                                </div> <!-- end col -->
                            </div>
                        </div>

                        {{-- laravel default Pagination start --}}
                        @if (method_exists($leads, 'links'))
                            <div class=" mt-4" id="paginationLead"
                                style="display: flex; justify-content: space-between;">
                                {{ $leads->links('pagination.pagination') }}
                            </div>
                        @endif
                        {{-- laravel default Pagination start --}}


                        {{-- Datafiltering Pagination daynamic Start --}}
                        <div id="filterPaginationData" style="display: none">
                            <div class=" mt-4" id="paginationLead"
                                style="display: flex; justify-content: space-between;">
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                                    <a href="javascript:prevPage()" id="btn_prev" rel="next"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        << pagination</a>
                                            <span aria-current="page">
                                                <span
                                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5"><span
                                                        id="page"></span></span>
                                            </span>
                                            <a href="javascript:nextPage()" id="btn_next" rel="prev"
                                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">

                                                Next>></a>

                                </nav>
                            </div>
                        </div>

                        {{-- Datafiltering Pagination daynamic  End --}}


                        </span>

                        {{-- <tfoot>
                            <tr class="active">
                                <td colspan="6">
                                    <div class="text-right">
                                        <ul
                                            class="pagination pagination-split justify-content-end footable-pagination mt-2">  --}}
                        {{-- {{ $leads->links('pagination::bootstrap-4')  }}  --}}
                        {{-- </ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>    --}}
                        {{-- <span>{{ $leads->links() }}</span> --}}
                        {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $leads->links('pagination::bootstrap-4')  }}
                        </ul> --}}



                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection





@section('scripts')
    {{-- modal in latavel --}}
    {{-- <script>
        $(document).ready(function() {
            $(document).on('click', '.updateStatus', function() {
                var lead_id = $(this).val();
                // alert(lead_id);

                $('#lead_id').val(lead_id)
                $('#exampleModal').modal('show'); 
            })
        })
    </script> --}}
    {{-- modal in latavel End --}}
    {{-- <style>
    
        .iti {
            display: block !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #f2f5f7 !important;
            border-radius: 4px;
            border: 1px solid #ced4da !important;
            line-height: 35.9px;
            height: 35.9px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #6c757d !important;
            line-height: 35.9px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px
        }


        .iti__flag {
            display: none;
        }
    </style> --}}
    <style>
        #demo-foo-filtering_filter {
            display: none;
        }

        #demo-foo-filtering_info {
            display: none !important;
        }

        .iti {
            display: block !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #f2f5f7 !important;
            border-radius: 4px;
            border: 1px solid #ced4da !important;
            line-height: 35.9px;
            height: 35.9px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #6c757d !important;
            line-height: 35.9px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px
        }


        .iti__flag {
            display: none;
        }

        .switch-field {
            display: flex;
            margin-bottom: 36px;
            overflow: hidden;
        }

        .switch-field input {
            position: absolute !important;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            width: 1px;
            border: 0;
            overflow: hidden;
        }

        .switch-field label {
            background-color: #e4e4e4;
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            line-height: 1;
            text-align: center;
            padding: 8px 16px;
            margin-right: -1px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
            transition: all 0.1s ease-in-out;
        }

        .switch-field label:hover {
            cursor: pointer;
        }

        .switch-field input:checked+label {
            background-color: #7e57c2;
            box-shadow: none;
        }

        .switch-field label:first-of-type {
            border-radius: 4px 0 0 4px;
        }

        .switch-field label:last-of-type {
            border-radius: 0 4px 4px 0;
        }
    </style>
    <script>
        var current_page = 1;
        var records_per_page = 50;
        var doctors_slots;
        var doctors_slots1 = [];

        /// filters all filters of leads
        function filterdata(x, y) {
            // alert(y);

            var clearButton = document.getElementById("clearButton");
            if (x.length > 0) {
                clearButton.style.display = "block";
                clearButton.style.zIndex = 0;
                clearButton.style.marginLeft = "145px";
                clearButton.style.marginTop = "-36px";
            } else {
                clearButton.style.display = "none";
            }


            // ---X-- for request value & --Y-- for request type or filter type 
            if (x == "" || x == '0') {
                window.location.replace('/leads');
                exit();
            }

            $.ajax({
                type: 'POST',
                url: '/filter-leads',
                data: {
                    filter_type: y,
                    filter_value: x,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // console.log(data);

                    document.getElementById("paginationLead").style = 'display: none;';
                    document.getElementById("filterPaginationData").style = 'display: block;';
                    //destory Previce datatable
                    // var table= document.getElementById("demo-foo-filtering");
                    // var tbody = document.getElementById('demo-foo-filtering').getElementsByTagName('tbody')[0];
                    doctors_slots = data.data;
                    /// console.log(doctors_slots[0]);
                    var html = '';
                    if (doctors_slots.length > 0) {

                        //Data of all doctors array send on next function  1 for current page
                        changePage(1);

                    } else {
                        var table = document.getElementById("demo-foo-filtering");


                        var tbody = document.getElementById("list_tbody");
                        //   table.remove();
                        //$("#demo-foo-filtering tbody tr").remove(); 
                        var listing_table = document.getElementById("demo-foo-filtering");
                        if (tbody != null) {
                            table.removeChild(tbody);
                            tbody = null;
                        }
                        // tbody = createElementWithId("tbody", "list_tbody");
                        tbody = Object.assign(document.createElement('tbody'), {
                            id: "list_tbody"
                        });
                        table.appendChild(tbody);
                        //if data not found 
                        let row = tbody.insertRow();
                        let c1 = row.insertCell(0);
                        let c2 = row.insertCell(1);
                        let c3 = row.insertCell(2);
                        let c4 = row.insertCell(3);
                        let c5 = row.insertCell(4);
                        let c6 = row.insertCell(5);
                        c6.innerHTML += 'No Record Found';
                        // let row = table.insertRow() ='No Record Found';
                        // let c1 = row.innerText= 'No Record Found'

                    }

                }
            });

        };

        var startDateFilter = '';
        var endDateFilter = '';
        ///  start Date  Filters Start 
        function startdatafilters(FD) {
            // FD is fromDate
            startDateFilter = FD;
        };


        ///  start Date End Date Filters Start 
        function Enddatafilters(ED) {

            // FD is End Date
            endDateFilter = ED;
            if (startDateFilter != null && endDateFilter != null) {
                $.ajax({
                    type: 'POST',
                    url: '/filter-leadsDates',
                    data: {
                        startDate: startDateFilter,
                        endDate: endDateFilter,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                        document.getElementById("paginationLead").style = 'display: none;';
                        var table = document.getElementById("demo-foo-filtering");
                        var tbody = document.getElementById("list_tbody");

                        if (tbody != null) {
                            table.removeChild(tbody);
                            tbody = null;
                        }
                        // tbody = createElementWithId("tbody", "list_tbody");
                        tbody = Object.assign(document.createElement('tbody'), {
                            id: "list_tbody"
                        });
                        table.appendChild(tbody);

                        var doctors_slots1 = data.data;

                        var html = '';
                        if (doctors_slots1.length > 0) {


                            for (i = 0; i < doctors_slots1.length; i++) {

                                let row = tbody.insertRow(); // We are adding at the end

                                let c1 = row.insertCell(0);
                                let c2 = row.insertCell(1);
                                let c3 = row.insertCell(2);
                                let c4 = row.insertCell(3);
                                let c5 = row.insertCell(4);
                                let c6 = row.insertCell(5);
                                let c7 = row.insertCell(6);
                                let c8 = row.insertCell(7);
                                let c9 = row.insertCell(8);
                                let c10 = row.insertCell(9);
                                let c11 = row.insertCell(10);
                                let c12 = row.insertCell(11);
                                let c13 = row.insertCell(12);
                                let c14 = row.insertCell(13);
                                let c15 = row.insertCell(14);


                                // Add data to c1 and c2

                                if (doctors_slots1[i]['preference'] == 1) {
                                    c1.innerHTML =
                                        `<span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/`+doctors_slots1[i].id +`.') }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                                            </span>
                                                            @can('Update')
                                                                   
                                                                @endcan
                                                                @can('History-View')
                                                                    <a data-toggle="tooltip" data-placement="top"
                                                                        title="Check Status"
                                                                        href="{{ url('lead-status-isHistory/`+doctors_slots1[i].id +`.') }}"
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>
                                                                @endcan `

                                } else {
                                    c1.innerHTML =
                                        ` 
                                    <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/`+doctors_slots1[i].id +`.') }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                                            </span>@can('Update')
                                                                    
                                                                @endcan
                                                                @can('History-View')
                                                                    <a data-toggle="tooltip" data-placement="top"
                                                                        title="Check Status"
                                                                        href="{{ url('lead-status/`+doctors_slots1[i].id +`.') }}"
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>
                                                                @endcan`
                                }

                                c2.innerText = doctors_slots1[i]['date']
                                c3.innerText = doctors_slots1[i]['lead_name']
                                if (doctors_slots1[i]['is_featured'] == 1) {
                                    c3.innerHTML =
                                    `<a href="#" class="text-body font-weight-semibold updateStatus" value="` +
                                    doctors_slots1[i]['id'] + `">` + (typeof doctors_slots1[i]['lead_name'] !== 'undefined' ? doctors_slots1[i]['lead_name'] : '') + `
                                        <span><i class="fa fa-star text-success"></i></span></a>
                                        <a href="{{ isset($lead->id) ? url('call-history/' . encrypt($lead->id)) : '#' }}" target="_blank">
                                            <span> 
                                                <i class="fa fa-history text-info" title="call-history"></i>
                                            </span>
                                        </a>`;

                                } else {
                                    c3.innerHTML = ` <a href="#"
                                                                    class="text-body font-weight-semibold updateStatus"
                                                                    value="` + doctors_slots1[i]['id'] + `">` +
                                        doctors_slots1[i]['lead_name'] + `</a>`
                                }

                                c4.innerText = doctors_slots1[i]['mode_of_lead']
                                c5.innerHTML = (typeof doctors_slots[i]['employee_name'] !== 'undefined' ? doctors_slots[i]['employee_name'] : '') +
                                ` <a href="https://api.whatsapp.com/send/?phone={{ isset($country_code_emp) ? $country_code_emp[1] : '#' }}{{ isset($lead->official_phone_number) ? $lead->official_phone_number : '' }}" target="_blank">
                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                </a>`;

                                




                                //Don't
                                if (doctors_slots1[i]['dnd'] == 1) {
                                    c6.innerHTML = `<a class="text-muted"
                                                    href="tel: +"` + doctors_slots1[i]['country_code'] + '' +
                                        doctors_slots1[i]['contact_number'] + `>` + doctors_slots1[i][
                                            'country_code'
                                        ] + ' ' + doctors_slots1[i]['contact_number'] + ` </a>
                                                <a
                                                    href="https://api.whatsapp.com/send/?phone=` + doctors_slots1[i][
                                            'country_code'
                                        ] + '' + doctors_slots1[i]['contact_number'] + `" target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                <a href="https://www.google.com/search?q=` + doctors_slots1[i][
                                            'contact_number'
                                        ] + `"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i>
                                                </a>
                                                <i title="Do Not Disturb"
                                                                        class="mdi mdi-circle text-danger"></i>`
                                } else {
                                    c6.innerHTML = `<a class="text-muted"
                                                    href="tel: +"` + doctors_slots1[i]['country_code'] + '' +
                                        doctors_slots1[i]['contact_number'] + `>` + doctors_slots1[i][
                                            'country_code'
                                        ] + ' ' + doctors_slots1[i]['contact_number'] + ` </a>
                                                <a
                                                    href="https://api.whatsapp.com/send/?phone=` + doctors_slots1[i][
                                            'country_code'
                                        ] + '' + doctors_slots1[i]['contact_number'] + `" target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                <a href="https://www.google.com/search?q=` + doctors_slots1[i][
                                            'contact_number'
                                        ] + `"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i>
                                                </a>`

                                }


                                // c6.innerText = doctors_slots1[i]['country_code'].trim() +' '+ doctors_slots1[i]['contact_number'].trim()
                                c7.innerText = doctors_slots1[i]['last_contacted']
                                c8.innerText = doctors_slots1[i]['next_follow_up_date']
                                c9.innerText = doctors_slots1[i]['office_site_visit_date']
                                c10.innerText = doctors_slots1[i]['project_type']
                                c11.innerText = doctors_slots1[i]['buyer_seller']
                                c12.innerText = doctors_slots1[i]['lead_type_bifurcation_id']
                                c13.innerText = doctors_slots1[i]['budget']
                                c14.innerText = doctors_slots1[i]['lead_status']



                            }
                        } else {
                            var table = document.getElementById("demo-foo-filtering");


                            var tbody = document.getElementById("list_tbody");
                            //   table.remove();
                            //$("#demo-foo-filtering tbody tr").remove(); 
                            var listing_table = document.getElementById("demo-foo-filtering");
                            if (tbody != null) {
                                table.removeChild(tbody);
                                tbody = null;
                            }
                            // tbody = createElementWithId("tbody", "list_tbody");
                            tbody = Object.assign(document.createElement('tbody'), {
                                id: "list_tbody"
                            });
                            table.appendChild(tbody);
                            //if data not found 
                            let row = tbody.insertRow();
                            let c1 = row.insertCell(0);
                            let c2 = row.insertCell(1);
                            let c3 = row.insertCell(2);
                            let c4 = row.insertCell(3);
                            let c5 = row.insertCell(4);
                            let c6 = row.insertCell(5);
                            c6.innerHTML += 'No Record Found';

                        }




                    }
                });
            }


        }


        //end Start date to End Date Filter End

        function prevPage() {
            if (current_page > 1) {
                current_page--;
                changePage(current_page);
            }
        }

        function nextPage() {
            if (current_page < numPages()) {
                current_page++;
                changePage(current_page);
            }
        }

        function changePage(page) {
            var btn_next = document.getElementById("btn_next");
            var btn_prev = document.getElementById("btn_prev");
            var listing_table = document.getElementById("demo-foo-filtering");
            var page_span = document.getElementById("page");

            var table = document.getElementById("demo-foo-filtering");


            var tbody = document.getElementById("list_tbody");
            //   table.remove();
            //$("#demo-foo-filtering tbody tr").remove(); 

            if (tbody != null) {
                table.removeChild(tbody);
                tbody = null;
            }
            // tbody = createElementWithId("tbody", "list_tbody");
            tbody = Object.assign(document.createElement('tbody'), {
                id: "list_tbody"
            });
            table.appendChild(tbody);
            // Validate page
            if (page < 1) page = 1;
            if (page > numPages()) page = numPages();

            // listing_table.innerHTML = "";

            // console.log(doctors_slots[1]['lead_name']);
            for (var i = (page - 1) * records_per_page; i < (page * records_per_page); i++) {


                // listing_table.innerHTML += doctors_slots[i].adName + "<br>";
                // for (i = 0; i < doctors_slots.length; i++) {
                if (i >= doctors_slots.length) {
                    break;

                }

                //  var tbodyRef = document.getElementById('demo-foo-filtering').getElementsByTagName('tbody')[0];
                let row = tbody.insertRow(); // We are adding at the end


                let c1 = row.insertCell(0);
                let c2 = row.insertCell(1);
                let c3 = row.insertCell(2);
                let c4 = row.insertCell(3);
                let c5 = row.insertCell(4);
                let c6 = row.insertCell(5);
                let c7 = row.insertCell(6);
                let c8 = row.insertCell(7);
                let c9 = row.insertCell(8);
                let c10 = row.insertCell(9);
                let c11 = row.insertCell(10);
                let c12 = row.insertCell(11);
                let c13 = row.insertCell(12);
                let c14 = row.insertCell(13);
                let c15 = row.insertCell(14);




                // Add data to c1 and c2
                //alert(doctors_slots[i]['preference']);
                if (doctors_slots[i]['preference'] == 1) {
                    c1.innerHTML =
                        `<span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/`+doctors_slots[i].id +`.') }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                                            </span>
                                                            @can('Update')
                                                                   
                                                                @endcan
                                                                @can('History-View')
                                                                    <a data-toggle="tooltip" data-placement="top"
                                                                        title="Check Status"
                                                                        href="{{ url('lead-status-isHistory/`+doctors_slots[i].id +`.') }}"
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>
                                                                @endcan `

                } else {
                    c1.innerHTML =
                        ` 
                                    <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/`+doctors_slots[i].id +`.') }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                                            </span>@can('Update')
                                                                    
                                                                @endcan
                                                                @can('History-View')
                                                                    <a data-toggle="tooltip" data-placement="top"
                                                                        title="Check Status"
                                                                        href="{{ url('lead-status/`+doctors_slots[i].id +`.') }}"
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>
                                                                @endcan`
                }

                c2.innerText = doctors_slots[i]['date']
                c3.innerText = doctors_slots[i]['lead_name']
                if (doctors_slots[i]['is_featured'] == 1) {
                    c3.innerHTML =
                    `<a href="#" data-toggle="modal"
                    data-target="#LeadIntractionModal{{ isset($lead->id) ? $lead->id : '' }}"
                    class="text-body font-weight-semibold updateStatus" value="{{ isset($lead->id) ? $lead->id : '' }}">` +
                        doctors_slots[i]['lead_name'] +
                        `
                                                                    <span>
                                                                        <i class="fa fa-star text-success"></i></span>  </a> `

                    if (doctors_slots[i]['projects_visited_names'] > 0) {
                        c3.innerHTML += `<span class="badge badge-pill badge-primary" 
                                                        title="${doctors_slots[i]['follow_up_status']}">
                                                        ${doctors_slots[i]['projects_visited_names']}</span>`;
                    }


                    if (doctors_slots[i]['is_featured'] == 1 && doctors_slots[i]['co_follow_up'] !== null) {
                        c3.innerHTML += ` <i class="fas fa-user-plus text-info" title="Follow Up Buddy" >`;
                    }

                    if (doctors_slots[i]['is_featured'] == 1 && doctors_slots[i]['rwa'] !== null) {
                        c3.innerHTML += `<i class="fa solid fa-handshake text-primary"title="Channel Partner" > `;
                    }

                } else {

                    c3.innerHTML = `<a href="#" value="{{ isset($lead->id) ? $lead->id : '' }}" data-toggle="modal"
                    data-target="#LeadIntractionModal{{ isset($lead->id) ? $lead->id : '' }}"
                    class="text-body font-weight-semibold updateStatus"
                    >` + (doctors_slots[i]['lead_name'] || '') + `</a>`;


                    if (doctors_slots[i]['co_follow_up'] !== null) {
                        c3.innerHTML += ` <i class="fas fa-user-plus text-info" title="Follow Up Buddy" >`;
                    }

                    if (doctors_slots[i]['rwa'] !== null) {
                        c3.innerHTML += `<i class="fa solid fa-handshake text-primary"title="Channel Partner" > `;
                    }

                    // if (doctors_slots[i]['rwa'] !== null && doctors_slots[i]['co_follow_up'] !== null) {
                    //     c3.innerHTML += `<i class="fa solid fa-handshake text-primary"title="Channel Partner" > `;
                    //     c3.innerHTML += ` <i class="fas fa-user-plus text-info" title="Follow Up Buddy" >`;
                    // } 




                    if (doctors_slots[i]['projects_visited_names'] > 0) {
                        c3.innerHTML += ` <span class="badge badge-pill badge-primary" 
                                        title="${doctors_slots[i]['follow_up_status']}">
                                        ${doctors_slots[i]['projects_visited_names']}</span>`;
                    }
                    // Add your new if condition here
                    if (doctors_slots[i]['lead_status'] == 'New') {
                        c3.innerHTML +=
                            ` <span class="badge badge-warning rounded-circle" title="New Lead">${doctors_slots[i]['relationship_name']}</span>`;
                    }



                    if (doctors_slots[i]['personal_details_if_any'] > doctors_slots[i]['office_site_visit_date']) {
                        c3.innerHTML += ` <i class="fa fa-circle text-success"
                                                                      title="Next Follow-Up Time " ></i>`;
                    }

                    if (doctors_slots[i]['personal_details_if_any'] < doctors_slots[i]['office_site_visit_date']) {
                        c3.innerHTML += ` <span class="badge badge-danger rounded-circle" title="Next Follow-Up Time Lapsed">
                            ${doctors_slots[i]['relationship_name']}
                            </span>`
                    }




                }

                c4.innerText = doctors_slots[i]['mode_of_lead']
                c5.innerHTML = (doctors_slots[i]['employee_name'] || '') +
                    ` <a href="{{ isset($lead->official_phone_number) ? 'https://api.whatsapp.com/send/?phone=' . $country_code_emp[1] . $lead->official_phone_number : '#' }}" target="_blank">
                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                    </a>`;






                //Don't
                if (doctors_slots[i]['dnd'] == 1) {
                    c6.innerHTML = `<a class="text-muted"
                                                    href="tel: +"` + doctors_slots[i]['country_code'] + '' +
                        doctors_slots[i]['contact_number'] + `>` + '+' + doctors_slots[i]['country_code'] + ' ' +
                        doctors_slots[i]['contact_number'] + ` </a>
                                                <a
                                                    href="https://api.whatsapp.com/send/?phone=` + doctors_slots[i][
                            'country_code'
                        ] + '' + doctors_slots[i]['contact_number'] + `" target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                <a href="https://www.google.com/search?q=` + doctors_slots[i][
                            'contact_number'
                        ] + `"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i>
                                                    <i class="mdi mdi-google"></i>
                                                </a>
                                                <i title="Do Not Disturb"
                                                                        class="mdi mdi-circle text-danger"></i>`
                } else {
                    c6.innerHTML =
                        `
                                    <a href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1=` +
                        doctors_slots[i]['official_phone_number'] + `&phone2=` + doctors_slots[i]['contact_number'] + `">
                                                                        <i class="fa fa-phone" aria-hidden="true"></i> 
                                                                    </a> 
                                    <a class="text-muted"
                                                    href="tel: +"` + doctors_slots[i]['country_code'] + '' +
                        doctors_slots[i]['contact_number'] + `>` + '+' + doctors_slots[i]['country_code'] + ' ' +
                        doctors_slots[i]['contact_number'] + ` </a>
                                                <a
                                                    href="https://api.whatsapp.com/send/?phone=` + doctors_slots[i][
                            'country_code'
                        ] + '' + doctors_slots[i]['contact_number'] + `" target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                
                                                <a href="https://www.google.com/search?q=` + doctors_slots[i][
                            'contact_number'
                        ] + `"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i> 
                                                </a>`



                }


                // c6.innerText = doctors_slots[i]['country_code'].trim() +' '+ doctors_slots[i]['contact_number'].trim()
                c7.innerText = doctors_slots[i]['last_contacted']
                c8.innerText = doctors_slots[i]['next_follow_up_date']
                c9.innerText = doctors_slots[i]['office_site_visit_date']
                c10.innerText = doctors_slots[i]['project_type']
                c11.innerText = doctors_slots[i]['buyer_seller']



                if (doctors_slots[i]['follow_up_status'] !== null && doctors_slots[i]['follow_up_status'] !== "") {
                    c12.innerHTML += doctors_slots[i]['follow_up_status'];
                } else if (doctors_slots[i]['existing_property'] !== null && doctors_slots[i]['existing_property'] !== "") {
                    c12.innerHTML += doctors_slots[i]['existing_property'];

                    c12.innerHTML += `<span class="badge badge-pill badge-primary" 
                                                         title="${doctors_slots[i]['follow_up_status']}">
                                                         ${doctors_slots[i]['projects_visited_names']}</span>`;

                } else {
                    c12.innerHTML += 'N/A';
                }


                if (doctors_slots[i]['follow_up_status'] !== null && doctors_slots[i]['follow_up_status'] !== "" &&
                    doctors_slots[i]['projects_visited_names'] > 0) {
                    c12.innerHTML += `<span class="badge badge-pill badge-primary" 
                                                         title="${doctors_slots[i]['follow_up_status']}">
                                                         ${doctors_slots[i]['projects_visited_names']}</span>`;
                }






                c13.innerText = doctors_slots[i]['lead_type_bifurcation_id']
                c14.innerText = doctors_slots[i]['budget']
                c15.innerText = doctors_slots[i]['lead_status']



                // }
            }
            page_span.innerHTML = page;

            if (page == 1) {
                btn_prev.style.visibility = "hidden";
            } else {
                btn_prev.style.visibility = "visible";
            }

            if (page == numPages()) {
                btn_next.style.visibility = "hidden";
            } else {
                btn_next.style.visibility = "visible";
            }
        }

        function numPages() {
            return Math.ceil(doctors_slots.length / records_per_page);
        }
    </script>
    <script>
        $('#projectType').select2({
            //placeholder: 'Select Project Type',
            // selectOnClose: true  
        });
        $('#CustomerType').select2({
            //placeholder: 'Select Project Type',
            // selectOnClose: true  
        });

        $('#BuyingLocation').select2({
            //placeholder: 'Select Buying Location Type',
            // selectOnClose: true  
        });
        $('#employee').select2({
            //placeholder: 'Select Employee Type',
            // selectOnClose: true  
        });
        $('#projectName').select2({
            //placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#followupbuddy').select2({
            // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#ChannelPartner').select2({
            // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#existing-property').select2({
            // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#budget').select2({
            // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        






        var
            filters = {
                user: null,
                status: null,
                milestone: null,
                priority: null,
                tags: null
            };

        function updateFilters() {
            $('.task-list-row').hide().filter(function() {
                var
                    self = $(this),
                    result = true; // not guilty until proven guilty

                Object.keys(filters).forEach(function(filter) {
                    if (filters[filter] && (filters[filter] != 'None') && (filters[filter] != 'Any')) {
                        result = result && filters[filter] === self.data(filter);
                    }
                });

                return result;
            }).show();
        }

        function changeFilter(filterName) {
            filters[filterName] = this.value;
            updateFilters();
        }

        // Assigned User Dropdown Filter
        $('#assigned-user-filter').on('change', function() {
            changeFilter.call(this, 'user');
        });

        // Task Status Dropdown Filter
        $('#status-filter').on('change', function() {
            changeFilter.call(this, 'status');
        });
    </script>
    
    <script>
    $(document).ready(function() {
        // Check if the user role is 1 (from Blade)
        @if(Auth::user()->roles_id == 1)
            let tableId = '#demo-foo-filtering';
        @else
            let tableId = ''; // Assign an empty string or another table ID as needed
        @endif

        // Initialize DataTable if tableId is set
        if (tableId !== '') {
            $(tableId).DataTable({
                dom: 'Bfrtip', // Include buttons
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-primary' // Apply Bootstrap primary button class
                    }
                ],
            });
        }
    });
</script>

    <script>
        
        // start to end date filters
        var minDate, maxDate;
        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {

                var min = minDate.val();
                var max = maxDate.val();
                var date = new Date(data[1]);
                // console.log(date);
                if (
                    (min === null && max === null) ||
                    (min === null && date <= max) ||
                    (min <= date && max === null) ||
                    (min <= date && date <= max)
                ) {
                    return true;
                }
                return false;
            }
        );

        $(document).ready(function() {



            // Create date inputs
            minDate = new DateTime($('#min'), {
                format: 'D-M-Y'
            });

            maxDate = new DateTime($('#max'), {
                format: 'D-M-Y'
            });

            // DataTables initialisation
            var table = $('#demo-foo-filtering').DataTable();

            // Refilter the table
            $('#min, #max').on('change', function() {
                table.draw();
            });
        });

        // start to end date filters end 
    </script>

    <script>
        $('#datefilterfrom').change(function() {

            var date = moment(this.value).format('DD-MM-Y');
            //alert(date);
            var filter, table, tr, td, i;
            filter = date.toUpperCase();
            table = document.getElementById("demo-foo-filtering");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[7];
                // console.log(td);
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        //console.log("lll");
                        tr[i].style.display = "";
                    } else {
                        //console.log("rrr");
                        tr[i].style.display = "none";
                    }
                }

            }

        });



        $('#datefilterPrevious').change(function() {

            var date = moment(this.value).format('DD-MM-Y');
            //alert(date);
            var filter, table, tr, td, i;
            filter = date.toUpperCase();
            table = document.getElementById("demo-foo-filtering");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[7];
                // console.log(td);
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        //console.log("lll");
                        tr[i].style.display = "";
                    } else {
                        //console.log("rrr");
                        tr[i].style.display = "none";
                    }
                }

            }

        });


        function copy_to_clipboard(link) {
            var input = document.createElement('input');
            input.setAttribute('value', link);
            document.body.appendChild(input);
            input.select();
            var result = document.execCommand('copy');
            // alert(result);
            document.body.removeChild(input);
            return result;
        }

        // isFeaturedLead  
        // var mytable = $('#demo-foo-filtering').DataTable();
        //     $('#select_tag').on('change', function() {
        //         mytable.draw();
        //     });

        //     $.fn.dataTable.ext.search.push(
        //         function(settings, searchData, index, rowData, counter) {
        //             var row = mytable.row(index).node();
        //             var filterValue = $(row).data('mytag');
        //             var e = document.getElementById("select_tag");
        //             var filter = e.options[e.selectedIndex].value;
        //               if (filterValue == filter)
        //                   return true;

        //               return false;

        //         }
        //     );  
        // isFeaturedLead
    </script>

    <script>
        function clearInput(event) {
            var searchInput = document.getElementById("searchInput");
            searchInput.value = ""; // Clear the input field
            document.getElementById("clearButton").style.display = "none"; // Hide the clear button
            window.location.replace('/leads');
        }


        function ExistingApplyFilter() {
            var existingProperty = $('#existing-property').val();
            var table1 = $('#demo-foo-filtering').DataTable();
            // Apply the filter to the "Lead Status" column
            table1.column(11).search(existingProperty).draw();

            if (existingProperty !== "") {
                var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
                isPaginate1.css('display', 'none');
            } else {
                window.location.replace('/leads');
            }
        }


        function CreateDateApplyFilter() {
            var CreationDateFilter = $('#creation-date').val();
            const formattedDate = moment(CreationDateFilter, 'YYYY-MM-DD').format('D-MMM-YYYY');

            var table1 = $('#demo-foo-filtering').DataTable();
            // Apply the filter to the "Lead Status" column
            table1.column(1).search(formattedDate).draw();

            if (formattedDate !== "Invalid date") {
                var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
                isPaginate1.css('display', 'none');
            } else {
                window.location.replace('/leads');
            }
        }
    </script>
@endsection
