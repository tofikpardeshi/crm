@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
         <div class="row">
            <div class="col-12 d-flex justify-content-between">
                <div class="page-title-box">
                    <div class="page-title-right">
                         
                    </div>
                    <h4 class="page-title"> Booking Confirm </h4>
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
                                                ->where('organisation_leave','!=',1)
                                                ->orderBy('employee_name', 'ASC')->get(); 
                                                $ProjectName = DB::table('projects')->get();
                                            @endphp
                                            <form action="{{ route('filter-lead-by-booking-confirm') }}" method="post">
                                                @csrf
                                                <div class="form-group mb-3">
                                                    <label for="example-email">Project Type <span
                                                            class="text-danger"></label>
                                                    <select name="sortbyLead" id="projectType" class="selectpicker"
                                                        data-style="btn-light">
                                                        <option value="" selected="">All</option>
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
                                                            <option value="{{ $location->id }}">
                                                                {{ $location->location }}</option>
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
                                                            <option value="{{ $Project->id }}">
                                                                {{ $Project->project_name }}</option>
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
        <!-- end page title -->


        <div class="row">
        
            <div class="col-lg-12">
             
                <div class="card ">
                <div class="container-fluid ">
                 @can('Developer Reports')
                        <div class="d-flex justify-content-end">
                            <a href="{{url('deal-confirm-excel')}}"  class="btn btn-info waves-effect waves-light mt-3">
                                Deal Confirm Reports 
                            </a>
                        </div>  
                    @endcan 
                <div class="row">
                        @if(Auth::user()->roles_id  == 1)
                        <div class="col-md-4 col-sm-6 col-12 mb-1 mt-2">
                            @php
                                $LeadStatus = DB::table('lead_statuses')
                                ->where('name','!=', 'New')
                                ->where('name','!=', 'Pending')
                                ->where('name','!=', 'Customer Called to Enquire')
                                ->where('name','!=', 'Call Not Answered')
                                ->where('name','!=', 'Next Follow-up')
                                ->where('name','!=', 'Site Visit Conducted')
                                ->where('name','!=', 'Unable to Connect')
                                ->where('name','!=', 'Deal Confirmed')
                                ->where('name','!=', 'Booking Cancelled')
                                ->get();
                            @endphp
                            <label for="">Assign Employee66</label> 
                                <select id="demo-foo-filter-status" class="selectpicker" onchange="applyFilter()">
                                <option value="">See All</option>
                                    @foreach ($Employees as $employee)
                                    <option value="{{ $employee->employee_name }}">
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                                </select>
                        </div>
                        @endif
                        {{-- <div class="col-md-3">
                            <label for=""> Lead Status</label>
                            <select id="status_filter" class="form-control">
                                <option value="">See All</option>
                                @foreach ($LeadStatus as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-4 col-sm-6 col-12 mb-1  mt-2">
                            <label for="">Today's Date follow up</label>
                            <td><input type="date" class="form-control" id="datefilterfrom"
                                    data-date-split-input="true" min="<?= date('d-m-Y') ?>"></td>
                        </div>
            
                        <div class="col-md-4 col-sm-6 col-12 mb-1  mt-2">
                            <label for="">Previous Follow-up</label>
                            <input type="date" class="form-control" id="datefilterPrevious"
                                data-date-split-input="true" max="<?= date('d-m-Y') ?>">
                        </div>
                    </div>
                    </div>
                    <div class="offset-md-8 col-md-4">
                            <label for="">Free Search</label>
                            <form action="{{ route('free-search') }}" method="get">
                                @csrf
                                <td><input type="text" name="free_search"  class="form-control"></td> 
                            </form>
                            
                        </div>
                    <div class="card-body"> 
                        <div class="table-responsive"> 
                            @if (session()->has('success'))
                            <div class="alert alert-success text-center">
                                {{ session()->get('success') }} </div>
                        @endif
                            <table id="demo-foo-filtering" class="table table-centered  table-nowrap table-hover mb-0" data-placement="top" data-page-size="100">
                                <thead>
                                    <tr>
                                        <th>Action</th>
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
                                        <th>Type</th>
                                        <th>Budget</th>
                                        <th>Lead Status</th> 
                                        <th>Booking Date</th>
                                        <th>Booking Project</th>
                                        <th>Booking Amount</th>

                                       
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach ($LeadBookingConfirm as $key => $BookingConfirm)
                                    @php
                                                        $lead_type_bif = DB::table('lead_type_bifurcation')
                                                            ->where('id', $BookingConfirm->lead_type_bifurcation_id)
                                                            ->first();
                                                        
                                                        $leadStatusName = DB::table('leads')
                                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                            ->select('leads.*', 'lead_statuses.name')
                                                            ->where('leads.id', $BookingConfirm->id)
                                                            ->first();
                                    @endphp
                                    @php
                                     $employees_name =DB::table('employees')->where('id',$BookingConfirm->assign_employee_id)->first();
                                 @endphp
                                    <tr id="task-{{ $key + 1 }}" class="task-list-row"
                                    data-task-id="{{ $key + 1 }}"
                                    data-user="{{  $employees_name->employee_name }}"
                                    data-status="{{ $leadStatusName->name }}"
                                    data-milestone="{{ \Carbon\Carbon::parse($BookingConfirm->next_follow_up_date)->format('d-M-Y') }}" data-priority="Urgent" data-tags="Tag 2"> 
                                        <td>
                                        <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($BookingConfirm->id)) }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                           {{-- <a href="{{ url('booking-details/'. encrypt($BookingConfirm->id)) }}">
                                               <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>--}}
                                            {{-- <a href="{{ url('booking-details/'.$BookingConfirm->id) }}">
                                               <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a> --}}
                                            <a data-toggle="tooltip" data-placement="top"
                                            title="Check Status"
                                            href="{{ url('lead-status/' . encrypt($BookingConfirm->id)) }}"
                                            class="action-icon">
                                            <img style="width:20px; margin-bottom:2px"
                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                        </a>
                                       </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($BookingConfirm->date)->format('d-M-Y H:i') }}
                                        </td>
                                        <td class="table-user text-capitalize" style="min-width: 120px; max-width: 120px; white-space: normal">
                                            @if ($BookingConfirm->is_featured == 1)
                                                <a href="#"
                                                    class="text-body font-weight-semibold updateStatus"
                                                    value="{{ $BookingConfirm->id }}">{{ $BookingConfirm->lead_name }}
                                                    <span>
                                                        <i class="fa fa-star text-success"></i></span></a>
                                            @else
                                                <a href="#"
                                                    class="text-body font-weight-semibold updateStatus"
                                                    value="{{ $BookingConfirm->id }}">{{ $BookingConfirm->lead_name }}</a>
                                            @endif

                                        </td>

                                        @php
                                            $LeadCount = DB::table('lead_status_histories')
                                                ->where('lead_id', $BookingConfirm->id)
                                                ->count();
                                        @endphp

                                        <td class="text-center">
                                            <span>{{ $LeadCount }}</span>
                                        </td>

                                        <td>
                                            @php
                                                $employees_name =DB::table('employees')->where('id',$BookingConfirm->assign_employee_id)->first();
                                            @endphp
                                            {{ $employees_name->employee_name }}
                                        </td>
                                        <td>

                                           @php
                                                $trimNumber = trim($BookingConfirm->contact_number);
                                                if ($BookingConfirm->country_code ==null) {
                                                    $country_code = array('1' => '', );
                                                } else {
                                                    $country_code= explode('+',trim($BookingConfirm->country_code));
                                                }
                                               
                                                                
                                            @endphp
                                            @if (Auth::user()->roles_id == 1)
                                                <a class="text-muted"
                                                    href="tel:{{$BookingConfirm->country_code}}{{ $trimNumber }}">{{$BookingConfirm->country_code}}{{ $trimNumber }}</a>
                                                <a
                                                    href="whatsapp://send?abid=phonenumber&text={{$country_code[1]}}{{ $trimNumber }}">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                <a href="https://www.google.com/search?q={{ $trimNumber }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i>
                                                </a>
                                                
                                                @if ($BookingConfirm->dnd == 1)
                                                <i class="mdi mdi-circle text-danger"></i>
                                                @else 

                                                @endif
                                                 
                                            @else
                                                <a class="text-muted"
                                                    href="tel:{{$BookingConfirm->country_code}}{{ $trimNumber }}">{{ substr_replace($trimNumber, '******', 0, 6) }}</a>
                                                <a
                                                    href="whatsapp://send?abid=phonenumber&text={{$country_code[1]}}{{ $trimNumber }}">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                <a href="https://www.google.com/search?q={{ $trimNumber }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-google"></i>
                                                </a>

                                                @if ($BookingConfirm->dnd == 1)
                                                <i class="mdi mdi-circle text-danger"></i>
                                                @else 
                                                
                                                @endif
                                            @endif
                                        </td>
                                        {{-- last Connect Date --}}
                                        <td>
                                            @if ($BookingConfirm->last_contacted == null)
                                                N/A
                                            @else
                                                {{ \Carbon\Carbon::parse($BookingConfirm->last_contacted)->format('d-M-Y H:i') }}
                                            @endif
                                        </td>
                                        {{-- fllow up Date --}}
                                        <td>
                                            @if ($BookingConfirm->next_follow_up_date == null)
                                                N/A
                                            @else
                                            {{ \Carbon\Carbon::parse($BookingConfirm->next_follow_up_date)
                                                    ->format('d-m-Y') }}
                                                {{-- {{ \Carbon\Carbon::parse($BookingConfirm->next_follow_up_date)->format('d-M-Y H:i') }} --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($BookingConfirm->next_follow_up_date == null)
                                                N/A
                                            @else
                                            {{ \Carbon\Carbon::parse($BookingConfirm->next_follow_up_date)
                                                    ->format('H:i') }}
                                                {{-- {{ \Carbon\Carbon::parse($BookingConfirm->next_follow_up_date)->format('d-M-Y H:i') }} --}}
                                            @endif
                                        </td>
                                        {{-- project type --}}
                                        <td class="text-capitalize">

                                            {{ $BookingConfirm->project_type }}
                                        </td>
                                        {{-- customer type --}}
                                        @php
                                            $customerType = DB::table('leads')
                                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                                ->select('leads.*', 'buyer_sellers.name')
                                                ->where('leads.id', $BookingConfirm->id)
                                                ->first();
                                        @endphp
                                        @if ($customerType == null)
                                            <td>N/A</td>
                                        @else
                                            <td>
                                                {{ $customerType->name }}
                                            </td>
                                        @endif


                                        @if ($BookingConfirm->lead_type_bifurcation_id == null)
                                            <td>
                                                N/A
                                            </td>
                                        @else
                                            <td>
                                                @php
                                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                        ->where('id', $BookingConfirm->lead_type_bifurcation_id)
                                                        ->first();
                                                @endphp
                                                {{ $lead_type_bif->lead_type_bifurcation }}
                                            </td>
                                        @endif

                                        @if ($BookingConfirm->budget == null)
                                            <td>N/A</td>
                                        @else
                                            <td>
                                                {{ $BookingConfirm->budget }}
                                            </td>
                                        @endif
                                        @php
                                        //   dd($BookingConfirm->booking_status);
                                            // $leadStatusName = DB::table('booking_confirms')
                                            //     ->join('lead_statuses', 'booking_confirms.booking_status', '=', 'lead_statuses.id')
                                            //     ->select('booking_confirms.*', 'lead_statuses.name')
                                            //     ->where('booking_confirms.lead_id', $BookingConfirm->lead_id)
                                            //     ->first();

                                                
                                                if($BookingConfirm->booking_status == 14)
                                                {
                                                     "Deal Confirmed"; 
                                                } 
                                             
                                        @endphp
                                        @if ($leadStatusName == null)
                                            <td>N/A</td>
                                        @else
                                            <td>
                                                {{ $leadStatusName->name }}
                                            </td>
                                        @endif

                                        {{-- <td>{{ $BookingConfirm->lead_name }}</td> --}}
                                         <td>{{ $BookingConfirm->booking_date }}</td> 
                                         @php

                                            $selects = explode(',',$BookingConfirm->bj);
                                            $proejctArray = array();
                                            foreach ($selects as $select) {
                                                $booking_name = DB::table('projects')->where('id',$select)->first(); 
                                                 $proejctArray[] = $booking_name->project_name ?? "";
                                            } 

                                            $test = '';

                                            @endphp  

                                            

                                                <td>
                                                    @foreach ($proejctArray as $item)
                                                     {{ $test.$item }} @php $test = ', ';@endphp
                                                    @endforeach
                                                </td>  
                                        
                                        <td>{{ $BookingConfirm->booking_confirm_amaunt }}</td>  
                                       
                                    </tr> 
                                    @endforeach 
                                </tbody>

                                <tfoot>
                                    <tr class="active">
                                        <td colspan="10">
                                            <div class="text-right">
                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                            </div>
                                        </td>
                                    </tr>
                                    </tfoot>
                            </table>

                        </div> 
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
</style>
<script>
 $('#assigned-user-filter').select2({
            // selectOnClose: true,
            placeholder: "Select"
        
        });
$('#projectType').select2({
            placeholder: 'Select Project Type',
            // selectOnClose: true  
        });
        $('#BuyingLocation').select2({
            placeholder: 'Select Buying Location Type',
            // selectOnClose: true  
        });
        $('#employee').select2({
            placeholder: 'Select Employee Type',
            // selectOnClose: true  
        });
        $('#demo-foo-filter-status').select2({
            //placeholder: 'Select Employee Type',
            // selectOnClose: true  
        });
        
        $('#projectName').select2({
            placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });
    setTimeout(function() {
            $("#success").hide();
        }, 2000);
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



    $('#demo-foo-filtering').dataTable( {
           "paging": false
        // lengthMenu: [
        //     [100,75, 50,25,  -1],
        //     [100,75, 50,25, 'All'],
        // ],
        //processing: true, 
    });



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
    
</script>
<script>
     function applyFilter() {
        var leadStatus = $('#demo-foo-filter-status').val(); 
        
        var table = $('#demo-foo-filtering').DataTable();  
        
        // Apply the filter to the "Lead Status" column
        table.column(4).search(leadStatus).draw();
        
       if(leadStatus !== "")
       {
            var isPaginate = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate.css('display', 'none'); 
       } 
       else
       {
      		 table.column('All').search(leadStatus).draw();
            // window.location.replace('/booking-confirm/index'); 
       }
    }   
</script>
@endsection


