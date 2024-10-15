@extends('main')
<!-- Start Content-->


@section('dynamic_page')
<style>
    .ls{border-bottom: 1px solid #eee; padding: 10px 0}
    .ls-1 {font-weight:400; }
    .ls-2 {font-weight: 700; border-bottom: 1px solid #eee;}
    .text-bold {font-weight:bold !important}
    .text-uppercase {text-transform: uppercase}
    .table tr th, .table tr td {font-size: 11px !important}
    .text-2 { display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis; max-width: 100%; min-height: 27px}
</style>
    <div class="container-fluid">
        <!-- start page title -->
        <!-- start page title -->
        @if (session()->has('success'))
        <div class="alert alert-success text-center mt-3" id="notification">
            {{ session()->get('success') }}
        </div>
    @endif 
    @if (session()->has('message'))
        <div class="alert alert-danger text-center mt-3" id="notification">
            {{ session()->get('message') }}
        </div>
    @endif 
    	 
    	
    		
    	 
        <div class="row">
             <div class="col-12  col-lg-4"> 
                    <h4 class="page-title">Location > {{ $emp->employee_name }}</h4>
            </div> 
            <div class="col-12  col-lg-4">
            </div>
            <div class="col-12 col-lg-4" style="margin-top: 10px;
    margin-bottom: 10px;">
                    <form action="{{ url('is-location-filter/'.encrypt($emp->id)) }}" method="get">
                         @csrf 
                        <div class="d-flex" > 
                            @php $LocationFilter = DB::table('locations')->get(); @endphp
                            <select name="locationFilter" class="selectpicker" 
                            data-style="btn-light" id="locationFilter" required>
                            <option value="" selected>Select</option>
                                @foreach ($LocationFilter as $item) 
                                        <option value="{{ $item->id }}">{{ $item->location }}</option> 
                                @endforeach 
                            </select>
                            <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                value="submit">Filter</button>
                        </div>
                    </form> 
                    </div>
                </div>   
            </div>
          
         
        <!-- end page title -->
        @if (Session::has('Note'))
            <div id="flashmessage" class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ Session::get('Note') }}</strong>
            </div>
        @endif

        @if (session()->has('NoSearch'))
            <div class="alert alert-danger text-center" id="NoSearch">
                {{ session()->get('NoSearch') }} </div>
        @endif

        @if (Auth::user()->roles_id == 1)
            <div class="row">

                @foreach ($locationWise as $item)
                    <div class="col-md-6 col-xl-4">
                        {{-- @foreach ($item as $items) --}}
                        <a href="{{ url('employee-leads/' . encrypt($item->eid) . '/' . encrypt($item->id)) }}">
                            <div class="card-box">
                                <div class="row" style="background: rgba(126,87,194,0.2); border-radius: 6px; margin-top: -12px; margin-bottom: 10px">
                                    <div class="col-9">
                                         <div class="mt-0">
                                    <h6 class="text-uppercase mb-0">
                                        <span class="text-2"> {{ $item->location }} </span>
                                        </h6>
                                        <div class="row">
                                            @php
                                                
                                            $LeadCount = DB::table('leads')
                                            ->where('assign_employee_id', $item->eid )
                                            ->where('common_pool_status', '!=' ,1)
                                            ->where('lead_status', '!=' ,14) 
                                            ->where('lead_status', '!=' ,16) 
                                            ->where('lead_status', '!=' ,8) 
                                            ->where('lead_status', '!=' ,9) 
                                            ->where('lead_status', '!=' ,10) 
                                            ->where('lead_status', '!=' ,11) 
                                            ->where('lead_status', '!=' ,12) 
                                            ->where('location_of_leads', $item->id)
                                            ->count(); 

                                            $EmployeeLeadcount = DB::table('leads')->where('location_of_leads',$item->id)->count();

                                             
                                            if ($LeadCount > 0) {
                                                $progessBar = $LeadCount / $EmployeeLeadcount * 100; 
                                            } else {
                                                $progessBar = 0;
                                            } 
                                             
                                            @endphp
                                            <div class="col-3"><span>{{ $LeadCount }}%</span></div>
                                            <div class="col-9" style="padding-left: 0; padding-top: 5px">
                                                 <div class="progress progress-sm m-0">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            aria-valuenow="{{ $LeadCount }}" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: {{ $LeadCount }}%">

                                                        </div>
                                                    </div>
                                            </div>
                                            
                                        </div>
                                   
                                </div>
                                    </div>
                                    <div class="col-3" style="padding-left:0">
                                        <div class="text-right">
                                            <h3 class="text-dark my-1"><span
                                                    ><b>{{ $LeadCount }}</b></span></h3>
                                            <h6>Total Leads
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-bold text-uppercase">Leads Status </h6>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">New</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item->id)
                                                ->where('lead_status', 1)
                                                ->where('assign_employee_id',$item->eid)
                                               // ->where('created_at', '>=', $date)
                                               ->count()  }}
                                            </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Pending</span></div>
                                    <div class="col-3 text-right">
                                           <span  class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)
                                            ->where('lead_status', 2)
                                            ->where('assign_employee_id',$item->eid)
                                           //  ->where('created_at', '>=', $date)
                                            ->count()
                                             }}   
                                            </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Customer Called to Enquire</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2" >
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 3)
                                            ->where('assign_employee_id',$item->eid)
                                           //  ->where('created_at', '>=', $date)
                                            ->count() 
                                            }}  
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Call Not Answered</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 4)
                                               ->where('assign_employee_id',$item->eid)
                                            //    ->where('created_at', '>=', $date)
                                               ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Next Follow-up</span></div>
                                    <div class="col-3 text-right">
                                            <span  class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 5)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Site Visit Conducted</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2" >
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 7)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Unable to Connect</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2" >
                                            {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 7)
                                            ->where('assign_employee_id',$item->eid)
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Enquiry Only</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)
                                                ->where('lead_status', 8)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Not Interested</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)
                                                ->where('lead_status', 9)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Low Budget</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)
                                                ->where('lead_status', 10)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Already Booked</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)
                                                ->where('lead_status', 11)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 12)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Reallocate from Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 13)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>        
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Confirmed</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)
                                                ->where('lead_status', 14)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('updated_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Cancelled</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 15)
                                            ->where('assign_employee_id',$item->eid)
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Employee Left - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 16)
                                                ->where('assign_employee_id',$item->eid)
                                                // ->where('created_at', '>=', $date)
                                                ->count() }}
                                            </span>
                                    </div>    
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Visit / Meeting Planned</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads',$item->id)->where('lead_status', 17)
                                            ->where('assign_employee_id',$item->eid)
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <br>
                                <h6 class="text-muted text-uppercase">Leads Type</h6>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">COLD</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                            {{ DB::table('leads')->where('location_of_leads',$item->id)->where('lead_type_bifurcation_id', '2')
                                            ->where('assign_employee_id',$item->eid)
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">HOT</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                            {{ DB::table('leads')->where('location_of_leads',$item->id)->where('lead_type_bifurcation_id', '1')
                                            ->where('assign_employee_id',$item->eid)
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">WIP</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                            {{ DB::table('leads')
                                            ->where('location_of_leads',$item->id)
                                            ->where('assign_employee_id',$item->eid)
                                            ->where('lead_type_bifurcation_id', '3')
                                            // ->where('created_at', '>=', $date)
                                            ->count() }}
                                            </span>
                                    </div>                    

                                </div>
                               

                               
                            </div>
                        </a>
                        <!-- end card-box-->
                        {{-- @endforeach --}}
                    </div> <!-- end col -->
                @endforeach

            </div>
            <!-- end row -->
        @else
            @php
                $UserWise = DB::table('employees')
                    ->where('user_id', Auth::user()->id)
                    ->join('locations', 'employees.employee_location', '=', 'locations.id')
                    ->select('employees.*', 'locations.location')
                    ->first();
                
                // dd($UserWise);
                
                $selected = explode(',', $UserWise->employee_location);
                $multilocationemployee = DB::table('locations')
                    ->whereIn('id', $selected)
                    ->get();
                
                //dd($multilocationemployee);
                
            @endphp
            <div class="row">
                @foreach ($multilocationemployee as $item)
                    <div class="col-md-6 col-xl-4">
                        {{-- @foreach ($item as $items) --}}
                        <a href="{{ url('employee-leads-show/' . encrypt($UserWise->id) . '/' . encrypt($item->id)) }}">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-6">
                                        <h3 class="text-uppercase">{{ $item->location }}</h3>
                                    </div>
                                    <div class="col-6">
                                        @php
                                            $LeadCount = DB::table('leads')
                                                ->where('assign_employee_id', $UserWise->id)
                                                ->where('common_pool_status', '!=' ,1)
                                                ->where('lead_status', '!=' ,14) 
                                                ->where('lead_status', '!=' ,16) 
                                                ->where('lead_status', '!=' ,8) 
                                                ->where('lead_status', '!=' ,9) 
                                                ->where('lead_status', '!=' ,10) 
                                                ->where('lead_status', '!=' ,11) 
                                                ->where('lead_status', '!=' ,12) 
                                                ->where('location_of_leads', $item->id)
                                                ->count();
                                        @endphp
                                        <div class="text-right">
                                            <h3 class="text-dark my-1"><span
                                                    data-plugin="counterup">{{ $LeadCount }}</span></h3>
                                            <h6>Total Leads
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <h6>Leads Status </h6>
                                <div class="row row-cols-3 text-justify text-dark" style="font-size:11px;">

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 1)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>NEW</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 2)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>PENDING</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 3)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Customer Called to Enquire</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 4)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Call Not Answered</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 5)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Next Follow-up</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 6)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Site Visit Conducted</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 7)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Unable to Connect</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 8)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Case Closed - Enquiry Only</span>
                                    </div>
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 9)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Case Closed - Not Interested</span>
                                    </div>

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 10)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Case Closed - Low Budget</span>
                                    </div>


                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 11)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Case Closed - Already Booked</span>
                                    </div>


                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 12)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Case Closed - For Common Pool</span>
                                    </div>


                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 13)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Reallocate from Common Pool</span>
                                    </div>

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 14)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Booking Confirmed</span>
                                    </div>


                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 15)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Booking Cancelled</span>
                                    </div>

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ App\Models\Lead::where('assign_employee_id', $UserWise->id)->where('lead_status', 17)->where('updated_at', '>=', $date)->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Visit/Meeting Planned</span>
                                    </div> 
                                </div>

                                <h6>Leads Type</h6>
                                <div class="row row-cols-3 text-dark" style="font-size:11px;">
                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ DB::table('leads')->where('assign_employee_id', $UserWise->id)
                                                ->where('lead_type_bifurcation_id', 2)
                                                ->where('common_pool_status', '!=' ,1)
                                                ->where('lead_status', '!=' ,16)
                                                ->where('lead_status', '!=' ,14)  
                                                ->where('lead_status', '!=' ,8) 
                                                ->where('lead_status', '!=' ,9) 
                                                ->where('lead_status', '!=' ,10) 
                                                ->where('lead_status', '!=' ,11) 
                                                ->where('lead_status', '!=' ,12) 
                                                ->where('created_at', '>=', $date)
                                                ->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Cold</span>
                                    </div>

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ DB::table('leads')->where('assign_employee_id', $UserWise->id)->where('lead_type_bifurcation_id', 1)
                                                ->where('created_at', '>=', $date)
                                                ->where('common_pool_status', '!=' ,1)
                                                ->where('lead_status', '!=' ,16) 
                                                ->where('lead_status', '!=' ,8) 
                                                ->where('lead_status', '!=' ,9) 
                                                ->where('lead_status', '!=' ,10) 
                                                ->where('lead_status', '!=' ,11) 
                                                ->where('lead_status', '!=' ,12) 
                                                ->where('lead_status', '!=' ,14) 
                                                ->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>Hot</span>
                                    </div>

                                    <div class="col my-1">
                                        <h5 class="text-dark my-1">
                                            <span data-plugin="counterup">
                                                {{ DB::table('leads')->where('assign_employee_id', $UserWise->id)->where('lead_type_bifurcation_id', 3)
                                                ->where('created_at', '>=', $date)
                                                ->where('common_pool_status', '!=' ,1)
                                                ->where('lead_status', '!=' ,16) 
                                                ->where('lead_status', '!=' ,14) 
                                                ->where('lead_status', '!=' ,8) 
                                                ->where('lead_status', '!=' ,9) 
                                                ->where('lead_status', '!=' ,10) 
                                                ->where('lead_status', '!=' ,11) 
                                                ->where('lead_status', '!=' ,12) 
                                                ->where('location_of_leads', $item->id)->count() }}
                                            </span>
                                        </h5>
                                        <span>WIP</span>
                                    </div>

                                </div>
                            </div>
                        </a>
                        <!-- end card-box-->
                        {{-- @endforeach --}}
                    </div> <!-- end col -->
                @endforeach
            </div>
        @endif 
    </div> <!-- container -->
@endsection

@section('scripts')
    <script>
        setTimeout(function() {
            $("#flashmessage").hide();
        }, 2000);

        setTimeout(function() {
            $("#notification").hide();
        }, 2000);

        setTimeout(function() {
            $("#NoSearch").hide();
        }, 2000);
        
         $('#locationFilter').select2({
            // placeholder: "Select Designation",
            // minimumResultsForSearch: Infinity
        });
        
    </script>
@endsection

