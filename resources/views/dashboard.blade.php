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
        <div class="row">
            <div class="col-12">
                {{-- @if (session()->has('locked'))
                    <div class="alert alert-danger">
                        {{ session()->get('locked') }} </div>
                @endif --}}
                <div class="page-title-box">
                    @if (session()->has('success'))
                        <div class="alert alert-success text-center mt-3" id="notification">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="page-title-right">
                        <form action="{{ route('dashboard') }}" method="get">
                            {{-- @csrf --}}
                            <div class="form-group d-flex">
                                {{-- <label for="example-select">filter Leads</label> --}}
                                @php
                                    // $filters = array('Weekly','Monthly','Quarterly','Yearly','Life time');
                                    //   print_r($filter);
                                    
                                    //   exit;
                                @endphp
                                <select name="filter_data" class="selectpicker" data-style="btn-light">
                                    @foreach ($dashboard_filters as $item)
                                        @if ($item->filters == $filter)
                                            <option value="{{ $item->filters }}" selected>{{ $item->filters }}</option>
                                        @else
                                            <option value="{{ $item->filters }}">{{ $item->filters }}</option>
                                        @endif
                                    @endforeach
                                    {{-- <option value="Weekly">Weekly</option>  
                                         <option value="Monthly">Monthly</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Life time">Life time</option> --}}
                                </select>
                                <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                    value="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="page-title-right mr-2">
                        <select id="location_filter" class="selectpicker" data-style="btn-light">
                            <option value="all">All Locations</option>
                            @foreach ($location_data as $item)
                                <option value="{{ $item['location_id'] }}">{{ $item['location'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h4 class="page-title">Location Dashboard</h4>
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

                @foreach ($location_data as $item)
                    <div class="col-md-6 col-xl-4">
                        {{-- @foreach ($item as $items) --}}
                        <a href="{{ url('locationwise', encrypt($item['loaction_id'])) }}">
                            <div class="card-box" data-location="{{ $item['location_id'] }}">
                                <div class="row" style="background: rgba(126,87,194,0.2); border-radius: 6px; margin-top: -12px; margin-bottom: 10px">
                                    <div class="col-9">
                                         <div class="mt-0">
                                    <h6 class="text-uppercase mb-0">
                                        <span class="text-2">{{ $item['location'] }} </span>
                                        </h6>
                                        <div class="row">
                                            <div class="col-3"><span>{{ $item['leads_count'] }}%</span></div>
                                            <div class="col-9" style="padding-left: 0; padding-top: 5px">
                                                 <div class="progress progress-sm m-0">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            aria-valuenow="{{ $item['leads_count'] }}" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: {{ $item['leads_count'] }}%">

                                                        </div>
                                                    </div>
                                            </div>
                                            
                                        </div>
                                   
                                </div>
                                    </div>
                                    <div class="col-3" style="padding-left:0">
                                        <div class="text-right">
                                            <h3 class="text-dark my-1"><span
                                                    ><b>{{ $item['location_of_leads'] }}</b></span></h3>
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
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 1)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Pending</span></div>
                                    <div class="col-3 text-right">
                                           <span  class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 2)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Customer Called to Enquire</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2" >
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 3)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Call Not Answered</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 4)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Next Follow-up</span></div>
                                    <div class="col-3 text-right">
                                            <span  class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 5)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Site Visit Conducted</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2" >
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 6)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Unable to Connect</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2" >
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 7)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Enquiry Only</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 8)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Not Interested</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 9)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Low Budget</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 10)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Already Booked</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 11)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 12)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Reallocate from Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 13)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>        
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Confirmed</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 14)->where('updated_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Cancelled</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 15)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Employee Left - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                            <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 16)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>    
                                    </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Visit / Meeting Planned</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                                {{ App\Models\Lead::where('location_of_leads', $item['location_id'])->where('lead_status', 17)->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <br>
                                <h6 class="text-muted text-uppercase">Leads Type</h6>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">COLD</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                                {{ DB::table('leads')->where('location_of_leads', $item['location_id'])->where('lead_type_bifurcation_id', '2')->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">HOT</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                                 {{ DB::table('leads')->where('location_of_leads', $item['location_id'])->where('lead_type_bifurcation_id', '1')->where('created_at', '>=', $date)->count() }}
                                            </span>
                                    </div>                    

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">WIP</span></div>
                                    <div class="col-3 text-right">
                                           <span class="ls-2">
                                                 {{ DB::table('leads')->where('location_of_leads', $item['location_id'])->where('lead_type_bifurcation_id', '3')->where('created_at', '>=', $date)->count() }}
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
                            @php
                            $LeadCount = DB::table('leads')
                                ->where('assign_employee_id', $UserWise->id)
                                ->where('common_pool_status', '!=', 1)
                                ->where('lead_status', '!=', 14)
                                ->where('lead_status', '!=', 16)
                                ->where('lead_status', '!=', 8)
                                ->where('lead_status', '!=', 9)
                                ->where('lead_status', '!=', 10)
                                ->where('lead_status', '!=', 11)
                                ->where('lead_status', '!=', 12)
                                ->where('location_of_leads', $item->id)
                                ->count();
                        @endphp
                        {{-- @foreach ($item as $items) --}}
                        <a href="{{ url('employee-leads-show/' . encrypt($UserWise->id) . '/' . encrypt($item->id)) }}">
                            <div class="card-box">
                                <div class="row"
                                    style="background: rgba(126,87,194,0.2); border-radius: 6px; margin-top: -12px; margin-bottom: 10px">
                                    <div class="col-9">
                                        <div class="mt-0">
                                            <h6 class="text-uppercase mb-0">
                                                <span class="text-2">{{ $item->location }} </span>
                                            </h6>
                                            <div class="row">
                                                <div class="col-3"><span>{{ $LeadCount }}%</span></div>
                                                <div class="col-9" style="padding-left: 0; padding-top: 5px">
                                                    <div class="progress progress-sm m-0">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            aria-valuenow="{{ $LeadCount }}" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: {{ $LeadCount }}%">

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-3" style="padding-left:0">
                                        <div class="text-right">
                                            <h3 class="text-dark my-1">
                                                <span><b>{{ $LeadCount }}</b></span></h3>
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
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 1)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Pending</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 2)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Customer Called to Enquire</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 3)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Call Not Answered</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 4)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Next Follow-up</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 5)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Site Visit Conducted</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 6)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Unable to Connect</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 7)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Enquiry Only</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 8)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Not Interested</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 9)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Low Budget</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 10)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - Already Booked</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 11)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Case Closed - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 12)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Reallocate from Common Pool</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 13)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Confirmed</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 14)->where('updated_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Booking Cancelled</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 15)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Employee Left - For Common Pool</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 16)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">Visit / Meeting Planned</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ App\Models\Lead::where('location_of_leads', $item->id)->where('lead_status', 17)->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>

                                </div>
                                <br>
                                <h6 class="text-muted text-uppercase">Leads Type</h6>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">COLD</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ DB::table('leads')->where('location_of_leads', $item->id)->where('lead_type_bifurcation_id', '2')->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">HOT</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ DB::table('leads')->where('location_of_leads', $item->id)->where('lead_type_bifurcation_id', '1')->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>

                                </div>
                                <div class="row text-justify text-dark ls">
                                    <div class="col-9"><span class="ls-1">WIP</span></div>
                                    <div class="col-3 text-right">
                                        <span class="ls-2">
                                            {{ DB::table('leads')->where('location_of_leads', $item->id)->where('lead_type_bifurcation_id', '3')->where('created_at', '>=', $date)->count() }}
                                        </span>
                                    </div>

                                </div>
 
                            </div>
                        </a>
                    </div> <!-- end col -->
                @endforeach
            </div>
        @endif

        @if (Auth::user()->roles_id == 1)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload">
                                    {{-- <i class="mdi mdi-refresh"></i> --}}
                                </a>
                                <a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false"
                                    aria-controls="cardCollpase5">
                                    <i class="mdi mdi-minus"></i>
                                </a>
                                <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Recent Leads</h4>

                            <div id="cardCollpase5" class="collapse pt-3 show" style="font-size:11px">

                                <div class="table-responsive">
                                    @if (session()->has('Delete'))
                                        <div class="alert alert-danger text-center">
                                            {{ session()->get('Delete') }} </div>
                                    @endif

                                    @if (session()->has('NoSearch'))
                                        <div class="alert alert-danger text-center">
                                            {{ session()->get('NoSearch') }} </div>
                                    @endif
                                    @if (session()->has('success'))
                                        <div class="alert alert-success text-center">
                                            {{ session()->get('success') }} </div>
                                    @endif
                                    <table id="demo-foo-addrow" class="table table-centered table-nowrap table-hover mb-0"
                                    onmousedown='return false;' onselectstart='return false;' data-placement="top"
                                    title="Do not copy sensitive data"
                                    data-page-size="50">
                                        <thead>
                                            <tr>
                                                <th style="width: 82px;">Action</th>
                                                <th>Creation Date</th>
                                                <th>Customer</th>
                                                <th>History Count</th>
                                                <th>Assigned Lead</th>
                                                <th>Mobile</th>
                                                <th>Last Contacted</th>
                                                <th>Follow Up Date</th>
                                                <th>Project Type</th>
                                                <th>Customer Type</th>
                                                <th>Lead Type</th>
                                                <th>Budget</th>
                                                <th>Last Summary</th>
                                                {{-- <th>Contact Number</th> --}}
                                                {{-- <th>Follow Up Date</th> --}}
                                                {{-- <th>Project Type</th>
                                        <th>Assigned To</th>
                                        <th>Lead Status</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leads as $lead)
                                                <tr>
                                                    <td>
                                                        @if ($lead->lead_status == 1)
                                                            {{-- <a href="{{ url('lead-status-isNew/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <i class="mdi mdi-square-edit-outline">
                                                                </i></a> --}}


                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="Check Status"
                                                                href="{{ url('lead-status-isHistory/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <img style="width:20px; margin-bottom:2px"
                                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                            </a>
                                                        @else
                                                            {{-- <a href="{{ url('edit-leads/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <i class="mdi mdi-square-edit-outline">
                                                                </i></a> --}}

                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="Check Status"
                                                                href="{{ url('lead-status/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <img style="width:20px; margin-bottom:2px"
                                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                            </a>
                                                        @endif
                                                        {{-- <a href="{{ url('lead-delete/' . $lead->id) }}" class="action-icon">
                                                    <i class="mdi mdi-delete"></i></a> --}}

                                                        {{-- <button type="button" class="btn btn-primary updateStatus"
                                                    data-toggle="modal" value="{{ $lead->id }}"
                                                    data-target="#exampleModal">
                                                    status
                                                </button> --}}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i') }}
                                                    </td>
                                                    <td class="table-user text-capitalize" style="max-width: 180px; min-width: 180px; white-space: normal">
                                                        @if ($lead->is_featured == 1)
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}">{{ $lead->lead_name }} <span>
                                                                    <i class="fa fa-star text-success"></i></span></a>
                                                        @else
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}">{{ $lead->lead_name }}</a>
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
                                                        {{ $lead->employee_name }}
                                                    </td>
                                                    <td>
                                                        @if (Auth::user()->roles_id == 1)
                                                            <a class="text-muted" href="tel:{{ $lead->contact_number }}">
                                                                {{ $lead->contact_number }}</a>
                                                        @else
                                                            <a class="text-muted" href="tel:{{ $lead->contact_number }}">
                                                                {{ substr_replace($lead->contact_number, '******', 0, 6) }}</a>
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
                                                            {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y H:i') }}
                                                        @endif
                                                    </td>
                                                    @if ($lead->project_type == null)
                                                        N/A
                                                    @else
                                                        <td class="text-capitalize">
                                                            {{ $lead->project_type }}
                                                        </td>
                                                    @endif

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
                                                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                                ->first();
                                                        @endphp
                                                        {{ $lead_type_bif->lead_type_bifurcation }}
                                                    </td>
                                                    @if ($lead->budget == null)
                                                        <td> N/A</td>
                                                    @else
                                                        <td>
                                                            {{ $lead->budget }}
                                                        </td>
                                                    @endif

                                                    @php
                                                        $leadStatusName = DB::table('leads')
                                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                            ->select('leads.*', 'lead_statuses.name')
                                                            ->where('leads.id', $lead->id)
                                                            ->first();
                                                    @endphp
                                                    @if ($leadStatusName->name == null)
                                                        <td> N/A</td>
                                                    @else
                                                        <td>
                                                            {{ $leadStatusName->name }}
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div> <!-- collapsed end -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        @else
            @php
                $UserLeads = DB::table('leads')
                    ->where('assign_employee_id', $UserWise->id)
                    ->where('common_pool_status', '!=' ,1)
                    ->where('lead_status', '!=' ,14)
                    ->where('lead_status', '!=' ,16) 
                    ->where('lead_status', '!=' ,8) 
                    ->where('lead_status', '!=' ,9) 
                    ->where('lead_status', '!=' ,10) 
                    ->where('lead_status', '!=' ,11) 
                    ->where('lead_status', '!=' ,12) 
                    // ->where('lead_status', '!=' ,14) 
                    ->join('employees', 'employees.id', 'leads.assign_employee_id')
                    ->select('leads.*', 'employees.employee_name')
                    ->latest()
                    ->paginate(10);
            @endphp

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                <a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false"
                                    aria-controls="cardCollpase5"><i class="mdi mdi-minus"></i></a>
                                <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Recent Leads</h4>

                            <div id="cardCollpase5" class="collapse pt-3 show">

                                <div class="table-responsive">
                                    @if (session()->has('Delete'))
                                        <div class="alert alert-danger text-center">
                                            {{ session()->get('Delete') }} </div>
                                    @endif

                                    @if (session()->has('NoSearch'))
                                        <div class="alert alert-danger text-center">
                                            {{ session()->get('NoSearch') }} </div>
                                    @endif
                                    @if (session()->has('success'))
                                        <div class="alert alert-success text-center">
                                            {{ session()->get('success') }} </div>
                                    @endif
                                    <table id="demo-foo-addrow" class="table table-centered table-nowrap table-hover mb-0"
                                    onmousedown='return false;' onselectstart='return false;' data-placement="top"
                                    title="Do not copy sensitive data"
                                    data-page-size="50">
                                        <thead>
                                            <tr>
                                                <th style="width: 82px;">Action</th>
                                                <th>Creation Date</th>
                                                <th>Customer</th>
                                                <th>History Count</th>
                                                <th>Assigned Lead</th>
                                                <th>Mobile</th>
                                                <th>Last Contacted</th>
                                                <th>Follow Up Date</th>
                                                <th>Project Type</th>
                                                <th>Customer Type</th>
                                                <th>Lead Type</th>
                                                <th>Budget</th>
                                                <th>Last Summary</th>
                                                {{-- <th>Contact Number</th> --}}
                                                {{-- <th>Follow Up Date</th> --}}
                                                {{-- <th>Project Type</th>
                                        <th>Assigned To</th>
                                        <th>Lead Status</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($UserLeads as $lead)
                                                <tr>
                                                    <td>
                                                        @if ($lead->lead_status == 1)
                                                            <a href="{{ url('lead-status-isNew/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <i class="mdi mdi-square-edit-outline">
                                                                </i></a>


                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="Check Status"
                                                                href="{{ url('lead-status-isHistory/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <img style="width:20px; margin-bottom:2px"
                                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                            </a>
                                                        @else
                                                            <a href="{{ url('edit-leads/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <i class="mdi mdi-square-edit-outline">
                                                                </i></a>

                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="Check Status"
                                                                href="{{ url('lead-status/' . encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <img style="width:20px; margin-bottom:2px"
                                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                            </a>
                                                        @endif
                                                        {{-- <a href="{{ url('lead-delete/' . $lead->id) }}" class="action-icon">
                                                    <i class="mdi mdi-delete"></i></a> --}}

                                                        {{-- <button type="button" class="btn btn-primary updateStatus"
                                                    data-toggle="modal" value="{{ $lead->id }}"
                                                    data-target="#exampleModal">
                                                    status
                                                </button> --}}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i') }}
                                                    </td>
                                                    <td class="table-user text-capitalize">
                                                        @if ($lead->is_featured == 1)
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}">{{ $lead->lead_name }} <span>
                                                                    <i class="fa fa-star text-success"></i></span></a>
                                                        @else
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}">{{ $lead->lead_name }}</a>
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
                                                        {{ $lead->employee_name }}
                                                    </td>
                                                    <td>
                                                        <a class="text-muted" href="tel:{{ $lead->contact_number }}">
                                                            {{ substr_replace($lead->contact_number, '******', 0, 5) }}
                                                        </a>
                                                        
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
                                                            {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y H:i') }}
                                                        @endif
                                                    </td>
                                                    {{-- project type --}}
                                                    @if ($lead->project_type == null)
                                                        <td class="text-capitalize">N/A</td>
                                                    @else
                                                        <td class="text-capitalize">
                                                            {{ $lead->project_type }}
                                                        </td>
                                                    @endif

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
                                                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                                ->first();
                                                        @endphp
                                                        {{ $lead_type_bif->lead_type_bifurcation }}
                                                    </td>

                                                    @if ($lead->budget == null)
                                                        <td>N/A</td>
                                                    @else
                                                        <td>
                                                            {{ $lead->budget }}
                                                        </td>
                                                    @endif


                                                    @php
                                                        $leadStatusName = DB::table('leads')
                                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                            ->select('leads.*', 'lead_statuses.name')
                                                            ->where('leads.id', $lead->id)
                                                            ->first();
                                                    @endphp
                                                    <td>
                                                        {{ $leadStatusName->name }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div> <!-- collapsed end -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
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
        
    </script>


    <script>
        $('#location_filter').on('change', function() {
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

