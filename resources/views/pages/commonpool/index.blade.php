@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
         
            <div class="col-12">
                
                
                <div class="col-12 d-flex justify-content-between">
               
                <div class="page-title-box">
                    {{-- <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Common Pool</li>
                        </ol>
                    </div> --}}
                    @php
                         $CommonPullCount = DB::table('leads')
                         ->where('common_pool_status',1)
                         ->whereNotIn('lead_status',[1,2,3,4,5,6,7])
                         ->count();
                    @endphp
                    
                    <h4 class="page-title">Common Pool > {{ $CommonPullCount }}</h4>
                     @if (session()->has('errorFilter'))
                        <div class="alert alert-danger mt-3 text-center" id="NoDataFound">
                            {{ session()->get('errorFilter') }} </div>
                    @endif
                </div>

                <div class="page-title-right">
                         
                    <div class="modal fade" id="exampleModal"  role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Fillter</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            @php
                                           

                                                 $locations = DB::table('locations')->get();
                                                    $Employees = DB::table('employees')
                                                    ->select('employees.employee_name','employees.id')
                                                    ->orderBy('employee_name','asc')
                                                    ->where('organisation_leave',0)
                                                    ->get(); 

                                                    $ProjectName = DB::table('projects')
                                                    ->orderBy('project_name','asc')->get();
                                                    $CustomerTypes = DB::table('buyer_sellers')
                                                    ->select('name','id')
                                                    ->orderBy('name','asc')->get(); 
                                                    $employeeCoFollowUps = DB::table('employees')
                                                    ->select('employees.employee_name','employees.user_id')
                                                    ->orderBy('employee_name','asc')
                                                    ->get();   
                                                    $ChannelPartners= DB::table('users')
                                                    ->where('roles_id',10)
                                                    ->select('name','roles_id','id')
                                                    ->get();
                                            @endphp
                                            <form action="{{ route('filter-lead-by-commonpool') }}" method="post">
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

                                                @if (Auth::user()->roles_id == 1)
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
                                                    <label for="example-email">Buying Location<span
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
                                                        @foreach ($employeeCoFollowUps as $employeeCoFollowUp)
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
                
                <button class="btn btn-success  my-3"  type="submit" name="submit"
                    data-toggle="modal" data-target="#exampleModal" value="submit">Filter</button>
            </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">

		 

                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible text-center">
                        <h5>{{ Session::get('error') }}</h5>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible text-center">
                        <h5>{{ Session::get('success') }}</h5>
                    </div>
                @endif


                
                <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-end">
                        @can('Common Pool Reports') 
                        <a href="{{url('common-pool-excel')}}"  class="btn btn-info waves-effect waves-light  ">
                            Common Pool Reports 
                        </a>  
                         @endcan
                    </div>
                        @if (session()->has('message'))
                        <div class="alert alert-danger text-center">
                            {{ session()->get('message') }} </div>
                    @endif
                    <div class="card-body">
                        <form action="{{ url('common-pool-filter') }}" method="GET">
                        <div class="row">
                          
                                {{-- <div class="col-md-2">
                                    <label for="">Search</label>
                                    <input id="demo-foo-search" type="text" placeholder="Search"
                                        class="form-control form-control-sm" autocomplete="on">
                                </div> --}}

                                @php
                                $LeadStatus = DB::table('lead_statuses')
                                ->where('id','!=', 1)
                                ->where('id','!=', 2)
                                ->where('id','!=', 3)
                                ->where('id','!=', 4)
                                ->where('id','!=', 5)
                                ->where('id','!=', 6)
                                ->where('id','!=', 7)
                                ->where('id','!=', 14)
                                ->where('id','!=', 15)
                                ->where('id','!=', 17)
                                ->get();

                                $existingProjects = DB::table('projects')->select('project_name', 'id')->orderBy('project_name', 'ASC')->get();

                                $budgets = DB::table('budget')->get();

                            @endphp
                            
                               <div class="col-md-3 col-sm-6 col-6 mb-1">
                                   
                                    <label for=""> Employee</label> 
                                    <select id="filter-user" class="form-control" 
                                    {{-- onchange="applyFilterUser()" --}}
                                    name="EmpName"
                                    >
                                        <option value="">See All</option>
                                        @foreach ($Employees as $employee)
                                        
                                        <option value="{{ $employee->employee_name }}">
                                            {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for=""> Lead Status</label>
                                    <select id="filter-status" class="form-control" 
                                    {{-- onchange="applyFilter()" --}}
                                    name="LeadStatus"
                                    >
                                        <option value="">See All</option>
                                        @foreach ($LeadStatus as $item)
                                            <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for=""> Existing Property </label>
                                    <select id="existing-property" class="selectpicker" 
                                    {{-- onchange="ExistingApplyFilter()" --}}
                                    name="exitsProperty"
                                    >
                                        <option value="">See All</option>
                                        @foreach ($existingProjects as $existingProject)
                                            <option value="{{ $existingProject->id }}">{{ $existingProject->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Date follow up</label>
                                    <td><input type="date" class="form-control" id="datefilterfrom"
                                            data-date-split-input="true" min="<?= date('d-m-Y') ?>"
                                            {{-- onchange="FollowUpDateApplyFilter()" --}}
                                            name="dateFollowUp"
                                            ></td>
                                </div>
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Creation Date</label>
                                    <td><input type="date" class="form-control" id="creation-date"
                                            data-date-split-input="true" min="<?= date('d-m-Y') ?>"
                                            name="creationDate"
                                            {{-- onchange="CreateDateApplyFilter()" --}}
                                            >
                                        </td>

                                        
                                </div>

                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Date follow up</label>
                                    <td><input type="date" class="form-control" id="datefilterfrom"
                                            data-date-split-input="true" min="<?= date('d-m-Y') ?>"
                                            {{-- onchange="FollowUpDateApplyFilter()" --}}
                                            name="dateFollowUp"
                                            ></td>
                                </div>
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Creation Date</label>
                                    <td><input type="date" class="form-control" id="creation-date"
                                            data-date-split-input="true" min="<?= date('d-m-Y') ?>"
                                            name="creationDate"
                                            {{-- onchange="CreateDateApplyFilter()" --}}
                                            >
                                        </td> 
                                </div>

                                 
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
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
                        
                                <div class="col-md-3 col-sm-6 col-6 mb-1">
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
                                  
                            </form>

                            @if(request()->filled('EmpName') || request()->filled('LeadStatus') || request()->filled('exitsProperty') || request()->filled('dateFollowUp') || request()->filled('creationDate') || request()->filled('budget') || request()->filled('investor'))
                            <a href="{{ url('common-pool') }}" class="btn btn-sm btn-danger py-1 mt-3">Back</a>
                             @endif

                            <div class="col-md-3 col-sm-6 col-6 mb-1">
                            </div>

                            <div class="col-md-3 col-sm-6 col-6 mb-1">
                            </div>
    
                            <div class="col-md-3 col-sm-6 col-6 mb-1">
                                {{-- <label for="">Previous Follow-up</label>
                                <input type="date" class="form-control" id="datefilterPrevious"
                                    data-date-split-input="true" max="<?= //date('d-m-Y') ?>"> --}}
                                    <label for="">Free Search</label>
                                    <form action="{{ route('free-search') }}" method="get">
                                        @csrf
                                        <td><input type="text" name="free_search"  class="form-control"></td> 
                                    </form>
                            </div> 
                               
    
                            
                            <div class="col-md-3 col-sm-6 col-6 mb-1">
                                <form method="post" action="{{ url('assign-common-pool') }}">
                                    @csrf
                                    <input type="hidden" id="demo" name="cp">
                                    <div class="form-group ">
                                        <label>Assign Employee</label>
                                        <select name="common_pool"
                                        class="selectpicker"
                                         id="common_pool"
                                         data-style="btn-light" >
                                            <option value="" selected></option>
                                            @foreach ($Employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="col-md-3 my-2 align-self-center">
                                <button name="submit" value="submit" type="submit"
                                    class="btn btn-primary waves-effect waves-light delete_all"
                                    data-url="{{ url('assign-common-pool') }}">
                                    Assign Employee</button>
                            </div>
                            </form>


                            {{-- <div class="page-title-right">
                                <div class="form-group d-flex">
                                    <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                        data-toggle="modal" data-target="#exampleModal" value="submit">Filter</button>
                                </div>
            
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Fillter</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @php
                                                            $locations = DB::table('locations')->get();
                                                            $Employees = DB::table('employees')->get();
                                                            $ProjectName = DB::table('projects')->get();
                                                        @endphp
                                                        <form action="{{ route('filter-by-project') }}" method="post">
                                                            @csrf
                                                            <div class="form-group mb-3">
                                                                <label for="example-email">Project Type <span
                                                                        class="text-danger"></label>
                                                                <select name="sortbyLead" id="projectType" class="selectpicker"
                                                                    data-style="btn-light">
                                                                    <option value="" selected="">All</option>
                                                                    @foreach ($ProjectName as $projectType)
                                                                        <option value="{{ $projectType->project_type }}">
                                                                            {{ $projectType->project_type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
            
                                                            @if (Auth::user()->roles_id == 1)
                                                            <div class="form-group mb-3">
                                                                <label for="example-email">Employee <span
                                                                        class="text-danger"></label>
                                                                <select name="employee" class="selectpicker" data-style="btn-light"
                                                                    id="employee">
                                                                    <option value="">Employee</option>
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
                                                                <label for="example-email">Buying Location<span
                                                                        class="text-danger"></label>
                                                                <select name="BuyingLocation" class="selectpicker"
                                                                    data-style="btn-light" id="BuyingLocation">
                                                                    <option value="">Buying Location</option>
                                                                    @foreach ($locations as $location)
                                                                        <option value="{{ $location->id }}">{{ $location->location }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="example-email">Project Name<span
                                                                        class="text-danger"></label>
                                                                <select name="projectName" class="selectpicker"
                                                                    data-style="btn-light" id="projectName">
                                                                    <option value="">Project Name</option>
                                                                    @foreach ($ProjectName as $Project)
                                                                        <option value="{{ $Project->id }}">{{ $Project->project_name }}</option>
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
                            </div> --}}
                            
                           
                        </div>

                        


                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-centered table-nowrap table-hover mb-0" data-placement="top">
                                <thead>
                                    <tr>
                                        <th style="width: 82px;" data-sortable="false">
                                            <input type="checkbox" name="test[]" id="master">
                                        </th>
                                          {{-- <th>Action</th>
                                        <th>Creation Date</th>
                                        <th>Customer</th>
                                        <th>History Count</th>
                                        <th>Assigned Lead</th>
                                        <th>Mobile</th>
                                        <th>Last Contacted</th>
                                        <th>Follow Up Date</th>
                                        <th>Follow Up Time</th>
                                        <th>Project Type</th>
                                        <th>Customer Type</th>
                                        <th>Lead Type</th>
                                        <th>Budget</th>
                                        <th>Last Summary</th> --}}
                                        <th style="width: 82px;">Action</th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($CommonPolls as $key => $CommonPoll)
                                     @php
                                     
                                                        $lead_type_bif = DB::table('lead_type_bifurcation')
                                                            ->where('id', $CommonPoll->lead_type_bifurcation_id)
                                                            ->first();
                                                        
                                                        $leadStatusName = DB::table('leads')
                                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                            ->select('leads.*', 'lead_statuses.name')
                                                            ->where('leads.id', $CommonPoll->id)
                                                            ->first();
                                                            
                                                            $currentIntractionHistory = DB::table('lead_status_histories')
                                                            ->where('lead_id', $CommonPoll->id)
                                                            ->latest('lead_status_histories.created_at')
                                                            ->first();

                                                            $currentDateTime = Carbon\Carbon::now();
                                                            $givenDateTimeString = $CommonPoll->next_follow_up_date;

                                                                  // Replace this with your desired date and time

                                                            $givenDateTime = new DateTime($givenDateTimeString);
                                                            $givenDateTimeNewLead = new DateTime($CommonPoll->created_at);

                                                            $currentDateTime = new DateTime();

                                                            $currentDateTime = \Carbon\Carbon::now(); 


                                                            // Calculate the time difference
                                                            $timeDifference = $currentDateTime->diff($givenDateTime);
                                                            $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);


                                                            $existingProperty = $CommonPoll->existing_property;
                                                            $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                                                            $existingPropertyCount = count($existingProjects);

                                                            $existingPropertyIds = explode(',', $CommonPoll->existing_property);

                                                            $projects = App\Models\Project::whereIn('id', $existingPropertyIds)
                                                                ->select('project_name', 'id')
                                                                ->get();

                                                            $projectNames = $projects->pluck('project_name')->toArray();

                                                           
                                    @endphp
                                        <!-- Modal -->
                                        
                                         <div class="modal fade" id="LeadIntractionModal{{ $CommonPoll->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Current Interaction</h5>
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
                                                    
                                                    
                                        {{-- <div class="modal fade" id="exampleModal-{{ $CommonPoll->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <form method="post"
                                                                    action="{{ url('employee-assing-comoon-poll') }}">
                                                                    @csrf


                                                                    <input type="hidden" name="commonpooID"
                                                                        value="{{ $CommonPoll->id }}">

                                                                    <div class="form-group mb-3">

                                                                        <label>Assign Employee</label>
                                                                        <select name="common_pool" class="form-control"
                                                                            id="common_pool" data-style="btn-light">

                                                                            @foreach ($employees as $employee)
                                                                                <option value="{{ $employee->id }}">
                                                                                    {{ $employee->employee_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button name="submit" value="submit" type="submit"
                                                                            class="btn btn-primary waves-effect waves-light">
                                                                            Assign Employee</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div> --}}
                                        <tr id="task-{{ $key + 1 }}" class="task-list-row"
                                        data-task-id="{{ $key + 1 }}"
                                        {{-- data-user="{{  $CommonPoll->employee_name }}"
                                        data-status="{{ $leadStatusName->name }}" --}}
                                        {{-- data-milestone="{{ \Carbon\Carbon::parse($CommonPoll->next_follow_up_date)->format('d-M-Y') }}" --}}
                                        {{-- data-priority="Urgent" data-tags="Tag 2" --}}
                                        >
                                            
                                            <td class="table-user">
                                                {{-- <a href="{{ url('employee-assing-comoon-poll/' . $CommonPoll->id) }}"
                                                    class="action-icon" data-toggle="modal"
                                                    value="{{ $CommonPoll->id }}"
                                                    data-target="#exampleModal-{{ $CommonPoll->id }}"> --}}
                                                <input type="checkbox" name="test[]" class="sub_chk"
                                                    value="{{ $CommonPoll->id }}" data-id="{{ $CommonPoll->id }}" 
                                                    >
                                                {{-- </a> --}}

                                            </td>
                                            <td> 
                                            <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($CommonPoll->id)) }}')"> 
                                                   <i class="mdi mdi-content-copy"></i> 
                                               </span> 
                                                <a data-toggle="tooltip" data-placement="top"
                                                title="Check Status"
                                                href="{{ url('lead-status-isHistory/' . encrypt($CommonPoll->id)) }}"
                                                class="action-icon" target="blank">
                                                
                                                <img style="width:20px; margin-bottom:2px"
                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                            </a>
                                            </td>

                                            <td>
                                                
                                                {{ \Carbon\Carbon::parse($CommonPoll->date)->format('d-M-Y') }}
                                            </td>

                                            <td style="min-width: 180px; max-width: 180px; white-space: normal">
                                                
                                                <a href="#" class="text-body font-weight-semibold updateStatus"
                                                    value="{{ $CommonPoll->lead_name }}"  data-toggle="modal"
                                                                    data-target="#LeadIntractionModal{{ $CommonPoll->id }}">

                                                                    @if($CommonPoll->lead_status == 1)
                                                                    <span class="badge badge-warning rounded-circle"
                                                                    title="New Load"> 
                                                                   {{ $timeDifferenceNew->days }}
                                                                   
                                                                   </span>   
                                                                   @elseif($givenDateTime > $currentDateTime)
                                                                   <i class="fa fa-circle text-success"
                                                                   title="Next Follow-Up Time " ></i>
                                                                   @elseif ($givenDateTime < $currentDateTime)
                                                                   <span class="badge badge-danger rounded-circle"
                                                                  title="Next Follow-Up Time Lapsed"> 
                                                                   {{ $timeDifference->days }}
                                                                   
                                                               </span>  

                                                               @endif
                                                   
                                                    {{ $CommonPoll->lead_name }}</a>

                                                    <input type="text" value="{{ $CommonPoll->regular_investor }}" hidden> 

                                                    <i class="fas fa-user-plus text-info" title="Follow Up Buddy" @if ($CommonPoll->co_follow_up  !== null && $CommonPoll->is_featured == 1)
                                                        @else
                                                        style="display:none;" @endif>

                                                        <i class="fa solid fa-handshake text-primary"
                                                                            title="Channel Partner"
                                                                            @if ($CommonPoll->rwa  !== null && $CommonPoll->is_featured == 1)
                                                        @else
                                                        style="display:none;" @endif></i> 
                                                    </i>

                                                    <i class="fa solid fa-handshake text-primary"
                                                                            title="Channel Partner"
                                                                            @if ($CommonPoll->rwa != null)
                                                        @else
                                                        style="display:none;" @endif></i> 

                                                        <i class="fas fa-user-plus text-info" title="Follow Up Buddy" @if ($CommonPoll->co_follow_up !== null)
                                                            @else
                                                            style="display:none;" @endif>
                                                    <span>
                                                        
                                                        <span class="badge badge-pill badge-primary"
                                                        title="{{ implode(',', $projectNames) }}"
                                                        @if ($existingPropertyCount  > 1)
                                                        @else
                                                        style="display:none;"
                                                        @endif> 
                                                            {{ $existingPropertyCount }}
                                                        </span> 

                                                        @if ($CommonPoll->is_featured == 1)
                                                        <i class="fa fa-star text-success"></i>
                                                        @endif
                                                        
                                                    </span>  
                                                    </a>
                                            </td>

                                            <td>
                                                @php
                                                    $LeadCount = DB::table('lead_status_histories')
                                                        ->where('lead_id', $CommonPoll->id)
                                                        ->count();
                                                
                                                        if (isset($CommonPoll->emp_country_code) == null) {
                                                            $country_code_emp = ['1' => '']; // Initialize an empty array as a fallback
                                                        } else {
                                                            $country_code_emp = $country_code_emp = substr($CommonPoll->emp_country_code, 1);
                                                        }

                                                        $NumberEmp = DB::table('employees')
                                                        ->where('id',$CommonPoll->assign_employee_id)->first();

                                                        // dd($NumberEmp->emp_country_code)
                                                         
                                                @endphp
                                                <span>{{ $LeadCount }}</span>
                                            </td>
                                            <td>
                                                
                                                {{ $CommonPoll->employee_name  }}

                                                <a href="https://api.whatsapp.com/send/?phone={{ ltrim($NumberEmp->emp_country_code, '+')}}{{ $NumberEmp->official_phone_number }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                {{-- @if($country_code_emp)
                                                <a href="https://api.whatsapp.com/send/?phone={{ $country_code_emp[1] }}{{ $CommonPoll->official_phone_number }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a> --}}
                                                {{-- @else
                                                <a href="https://api.whatsapp.com/send/?phone={{ $country_code_emp }}{{ $CommonPoll->official_phone_number }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a> --}}
                                                {{-- @endif  --}}
                                            </td>
                                            <td>
                                                
                                                @php
                                                     $trimNumber = trim($CommonPoll->contact_number);
                                                       if ($CommonPoll->country_code ==null) {
                                                        $country_code = array('1' => '', );
                                                       } else {
                                                        $country_code= explode('+',trim($CommonPoll->country_code));
                                                       }
                                                                
                                                       
                                                     $lastContected = \Carbon\Carbon::parse($CommonPoll->last_contacted)->format('d-M-Y');

                                                    //  dd($lastContected);

                                                @endphp
                                                <a href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{$CommonPoll->official_phone_number}}&phone2={{ $CommonPoll->contact_number  }}">
                                                    <i class="fa fa-phone" aria-hidden="true"></i> 
                                                    {{-- <img src="{{ asset('/images/20230919_191308_0000.png') }}" alt="" class="img-fluid"> --}}
                                                </a>
                                                 <a class="text-muted"
                                                 href="tel: {{$CommonPoll->country_code}}{{ $trimNumber }}"> {{$CommonPoll->country_code}}
                                                    @if (Auth::user()->roles_id != 1)
                                                    {{ substr_replace($trimNumber, '******', 0, 6)}}

                                                    @else
                                                    {{ $trimNumber}}
                                                    @endif
                                                </a>
                                                  @if (isset($country_code[1]))
		                                     <a
		                                         href="https://api.whatsapp.com/send/?phone={{$country_code[1]}}{{$trimNumber}}" target="_blank">
		                                         <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
		                                     </a>
                                                @endif 
                                             <a href="https://www.google.com/search?q={{ $trimNumber }}"
                                                 target="_blank">
                                                 <i class="mdi mdi-google"></i>
                                             </a>

                                             @if ($CommonPoll->dnd == 1)
                                                 <i title="Do Not Disturb"
                                                     class="mdi mdi-circle text-danger"></i>
                                             @else
                                             @endif


                                               
                                            </td> 
                                            <td> 
                                                @if ($CommonPoll->next_follow_up_date == null)
                                                {{ "Not Need" }}
                                             @else
                                                    {{ \Carbon\Carbon::parse($CommonPoll->last_contacted)
                                                    ->format('d-M-Y')}}  
                                                     {{-- {{ $CommonPoll->last_contacted  }}    --}}
                                                @endif
                                               
                                            </td>
                                            <td>
                                                @if ($CommonPoll->next_follow_up_date == null)
                                                    {{ "Not Need" }}
                                                @else
                                                {{\Carbon\Carbon::parse($CommonPoll->next_follow_up_date)
                                                ->format('d-M-Y H:i:s')  }}
                                                 
                                                @endif
                                                
                                            </td>
                                            <td>
                                                @if ($CommonPoll->next_follow_up_date == null)
                                                    {{ "Not Need" }}
                                                @else
                                                {{\Carbon\Carbon::parse($CommonPoll->next_follow_up_date)
                                                ->format('H:i')  }}
                                                 
                                                @endif
                                                
                                            </td>

                                            <td style="max-width: 250px; white-space: normal">
                                                {{ $CommonPoll->project_type }}
                                            </td>

                                            <td>
                                                @php
                                                    $customerType = DB::table('leads')
                                                        ->join('buyer_sellers', 'buyer_sellers.id', '=','leads.buyer_seller')
                                                        ->select('leads.*', 'buyer_sellers.name')
                                                        ->where('leads.id', $CommonPoll->id)
                                                        ->first();
                                                @endphp
                                                {{ $customerType ? $customerType->name : "N/A"}}
                                            </td>
                                            <td> 
                                                @php
                                                        $existingProject = DB::table('projects')
                                                        ->select('project_name', 'id')
                                                         ->where('id', $CommonPoll->existing_property)
                                                         ->get()
                                                         ->pluck('project_name')
                                                         ->implode(', '); 
                                                @endphp 
                                                {{ $existingProject != null ? $existingProject : "N/A" }} 
                                            </td>

                                            <td>
                                                @php
                                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                        ->where('id', $CommonPoll->lead_type_bifurcation_id)
                                                        ->first();
                                                @endphp
                                                {{ $lead_type_bif ? $lead_type_bif->lead_type_bifurcation : 'N/A' }}

                                            </td>
                                            <td>
                                                @if ($CommonPoll->budget ==null)
                                                    N/A
                                                @else
                                                {{ $CommonPoll->budget }}
                                                @endif
                                               
                                            </td>
                                            @php
                                                $leadStatusName = DB::table('leads')
                                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                    ->select('leads.*', 'lead_statuses.name')
                                                    ->where('leads.id', $CommonPoll->id)
                                                    ->first();
                                            @endphp
                                            <td>
                                                {{ $leadStatusName->name }}
                                            </td>
                                             
                                        </tr>
                                    @endforeach

                                    {{-- <tfoot>
                                            <tr class="active">
                                                <td colspan="10">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tfoot> --}}
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- @if (isset($currentUrl) != url('/common-pool') ||  isset($currentUrl) != url('/common-pool-filter'))  --}}
                            <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2" id="isPaginate">
                                {{ $CommonPolls->links('pagination::bootstrap-4') }}
                            </ul>  
                          {{-- @endif    --}}
                         
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
@section('scripts')
<style>
    .iti {
        display: block !important;
    }
    /* #datatable-buttons_paginate{
        display: none;
    } */

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
    #demo-foo-filtering_length{
        display: none;
    }
    #demo-foo-filtering_info{
        display: none;
    }
    #demo-foo-filtering_paginate{
        display: none;
    }
     .buttons-copy{
        display: none;
    }
    .buttons-print{
        display: none;
    }
</style>
 
    <script>
         $('#assigned-user-filter').select2({
            // selectOnClose: true,
            placeholder: "Select"
        
        });

        $('#common_pool').select2({
            // selectOnClose: true,
            placeholder: "Select"
        
        });
        $(document).ready(function() {
             var allVals = [];
            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });

            $('.sub_chk').on('click', function(e) {
                if ($(this).is(':checked', true)) {

                    // $("#master").prop('checked', false);
                } else {
                    $("#master").prop('checked', false);
                }
            });



            $('.delete_all').on('click', function(e) { 
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                    // alert 
                });
                
                if (allVals.length <= 0  ) { 
                  //  alert("Please select row.");
                } else {
                    var check = true;
                    if (check == true) {
                        var join_selected_values = allVals.join(",");

                        // alert(join_selected_values);
                        
                        demo.value = join_selected_values;

                        // $.ajax({
                        //     url: "{{ route('assign-common-pool') }}",
                        //     type: 'POST',
                        //     headers: {
                        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        //     },
                        //     data: 'ids=' + join_selected_values,  
                        // });
                        // $.each(allVals, function(index, value) {
                        //     $('table tr').filter("[data-row-id='" + value + "']").remove();
                        // });
                    }
                }
            });

        }); 
 
 
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

        

   
</script>

 
<script>
	
	
 


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
        // var status_filter = document.getElementById('status_filter'); 
        // status_filter.value = ""; 
         changeFilter.call(this, 'user'); 

    });

    // Task Status Dropdown Filter
    $('#status_filter').on('change', function() {
        changeFilter.call(this, 'status');
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
    
</script>

<script> 
    function applyFilter() {
        var leadStatus = $('#filter-status').val(); 
        var table = $('#datatable-buttons').DataTable();  
        // Apply the filter to the "Lead Status" column
        table.column(15).search(leadStatus).draw();
        
       if(leadStatus !== "")
       {
            var isPaginate = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    }   

    function ExistingApplyFilter() {
        var existingProperty = $('#existing-property').val(); 
        var table1 = $('#datatable-buttons').DataTable();  
        // Apply the filter to the "Lead Status" column
        table1.column(12).search(existingProperty).draw();
        
       if(existingProperty !== "")
       {
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    } 
</script>
<script>
    function applyFilterUser() {
        var leadStatusUser = $('#filter-user').val(); 
        var table = $('#datatable-buttons').DataTable();
      
       
        // Apply the filter to the "Lead Status" column
        table.column(5).search(leadStatusUser).draw();
        if(leadStatusUser !== "")
        {   
            var isPaginate = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate.css('display', 'none'); 
        } else
        {
            window.location.replace('/common-pool'); 
        }
    }  
</script>
<script>
   function FollowUpDateApplyFilter() {
        var FollowUPFilter = $('#datefilterfrom').val(); 
        const FollowUpformattedDate = moment(FollowUPFilter, 'YYYY-MM-DD').format('D-MMM-YYYY'); 
        var table1 = $('#datatable-buttons').DataTable();   
        table1.column(7).search(FollowUpformattedDate).draw(); 
       if(FollowUpformattedDate !== "")
       {
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/leads'); 
       }
    } 

</script>

<script>
 function CreateDateApplyFilter() {
        var CreationDateFilter = $('#creation-date').val(); 
        const formattedDate = moment(CreationDateFilter, 'YYYY-MM-DD').format('D-MMM-YYYY'); 
        var table1 = $('#datatable-buttons').DataTable();   
        table1.column(2).search(formattedDate).draw(); 
       if(formattedDate !== "Invalid date")
       {
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    } 
</script>

<script>
 $(document).ready(function() {
    var dataTable = $('#datatable-buttons').DataTable();

    // Check if DataTable is already initialized
    if (dataTable) {
        dataTable.destroy(); // Destroy the existing DataTable instance
    }

    $('#datatable-buttons').DataTable({
        "language": {
            "search": "Lead Search",
        }, 
        "paging": false, // Disable pagination

    });
    
});

   
</script>



@endsection
