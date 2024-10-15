@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                    Dashbard > <span class="text-capitalize">
                            {{ $emp_names->employee_name }}</span>>  {{ $location_names->location }} >
                         Leads
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

                @if (session()->has('NoSearch'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('NoSearch') }} </div>
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
                                    @can('Create')
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                            href="{{ URL::previous() }}">Back</a>
                                             
                                        <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                            href="{{ route('create-leads') }}">Add New</a>
                                    @endcan

                                </div>
                            </div><!-- end col-->
                        </div>

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
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                @php
                                    $LeadStatus = DB::table('lead_statuses')
                                        ->where('name','!=', 'Case Closed - Enquiry Only')
                                        ->where('name','!=', 'Case Closed - Not Interested')
                                        ->where('name','!=', 'Case Closed - Low Budget')
                                        ->where('name','!=', 'Case Closed - Already Booked')
                                        ->where('name','!=', 'Case Closed - For Common Pool')
                                        ->where('name','!=', 'Reallocate from Common Pool')
                                        ->where('name','!=', 'Booking Confirmed')
                                        ->get();
                                @endphp
                                <label for="" style="font-size: 11px">Lead Type</label>
                                <select id="assigned-user-filter" class="form-control">
                                    <option value="">See All</option>
                                    <option value="Hot">Hot</option>
                                    <option value="Cold">Cold</option>
                                    <option value="WIP">WIP</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px"> Lead Status</label>
                                <select id="demo-foo-filter-status" class="form-control">
                                    <option value="">See All</option>
                                    @foreach ($LeadStatus as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Today's Date follow up</label>
                                <td><input type="date" class="form-control" id="datefilterfrom"
                                        data-date-split-input="true" min="<?= date('d-m-Y') ?>"></td>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Previous Follow-up</label>
                                <input type="date" class="form-control" id="datefilterPrevious"
                                    data-date-split-input="true" max="<?= date('d-m-Y') ?>">
                            </div>
                            <div class="col-md-2 col-sm-6 col-6 mb-1 mr-0">
                                <label for="" style="font-size: 11px">Start date:</label>
                                <td><input type="text" id="min" name="min" class="form-control"></td>
                            </div>

                            <div class="col-md-2 col-sm-6 col-6 mb-1">
                                <label for="" style="font-size: 11px">End date:</label>
                                <td><input type="text" id="max" name="max" class="form-control"></td>

                            </div>



                        </div>



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


                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box" style="padding: 0; padding-top: 20px">
                                        <table id="demo-foo-filtering"
                                            class="table table-centered table-nowrap table-hover mb-0"
                                            onmousedown='return false;' onselectstart='return false;'
                                            data-placement="top" title="Do not copy sensitive data" data-page-size="100">
                                            <thead>
                                                <tr>
                                                    <th style="width: 82px;">Action</th>
                                                    <th>Creation Date</th>
                                                    <th style="max-width:160px">Customer</th>
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
                                                    <th>Last Summary</th>
                                                    {{-- <th>Contact Number</th> --}}
                                                    {{-- <th>Follow Up Date</th> --}}
                                                    {{-- <th>Project Type</th>
                                                                            <th>Assigned To</th>
                                                                            <th>Lead Status</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($emplyeeLeadData as $key => $lead)
                                                
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
                                                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled> </textarea>
                                                                                @else
                                                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled> {{ strip_tags($currentIntractionHistory->customer_interaction) }}
                                                                                    </textarea>
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Modal For Current Interaction  end--}}
                                                   
                                                    <tr id="task-{{ $key + 1 }}" class="task-list-row"
                                                        data-task-id="{{ $key + 1 }}"
                                                        data-user="{{ $lead_type_bif->lead_type_bifurcation }}"
                                                        data-status="{{ $leadStatusName->name }}"
                                                        data-milestone="{{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y') }}"
                                                        data-priority="Urgent" @if ($lead->is_featured == 1) data-mytag = "isFeatured"  @else 
                                                        data-mytag = "notFeatured" @endif>
                                                        {{-- <tr> --}}
                                                        <td>
                                                        
                                                        <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                             onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($lead->id)) }}')"> 
                                                                <i class="mdi mdi-content-copy"></i> 
                                                            </span>  

                                                            @if ($lead->lead_status == 1)
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
                                                                        class="action-icon"
                                                                        target="_blank">
                                                                        <img style="width:20px; margin-bottom:2px"
                                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                                    </a>
                                                                @endcan
                                                            @else
                                                                @can('Update')
                                                                    {{-- <a href="{{ url('edit-leads/' . encrypt($lead->id)) }}"
                                                                        class="action-icon">
                                                                        <i class="mdi mdi-square-edit-outline">
                                                                        </i></a> --}}
                                                                @endcan
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
                                                            @endif
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
                                                        <td class="table-user text-capitalize" style="white-space:normal; min-width:160px; max-width:160px;">
                                                            @if ($lead->is_featured == 1)
                                                                <a href="#"
                                                                    class="text-body font-weight-semibold updateStatus"
                                                                    value="{{ $lead->id }}" data-toggle="modal"
                                                                    data-target="#LeadIntractionModal{{ $lead->id }}">{{ $lead->lead_name }}
                                                                    <span>
                                                                        <i class="fa fa-star text-success"></i></span></a>
                                                            @else
                                                                <a href="#"
                                                                    class="text-body font-weight-semibold updateStatus"
                                                                    value="{{ $lead->id }}" data-toggle="modal"
                                                                    data-target="#LeadIntractionModal{{ $lead->id }}">
                                                                    {{ $lead->lead_name }}</a>
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

                                                            @php
                                                                $trimNumber = trim($lead->contact_number);
                                                                if ($lead->country_code ==null) {
                                                                    $country_code= array('1' =>'', );
                                                                } else {
                                                                    $country_code= explode('+',trim($lead->country_code));
                                                                }
                                                                 
                                                                
                                                            @endphp
                                                            @if (Auth::user()->roles_id == 1)
                                                                {{-- <a class="text-muted"
                                                                    href="tel:{{$country_code[1]}}{{ $trimNumber }}">{{$country_code[1]}} {{ $trimNumber }}</a> --}}

                                                                    <a class="text-muted"
                                                                    href="tel:{{$lead->country_code}}{{ $trimNumber }}">{{$lead->country_code}} {{ $trimNumber }}</a>
                                                                <a
                                                                    href="https://api.whatsapp.com/send/?phone={{$country_code[1]}}{{$trimNumber}}" target="_blank">
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
                                                                <a class="text-muted"
                                                                    href="tel:{{$country_code[1]}}{{ $trimNumber }}">{{ substr_replace($trimNumber, '******', 0, 6) }}</a>
                                                                <a
                                                                    href="https://api.whatsapp.com/send/?phone={{$country_code[1]}}{{$trimNumber}}" target="_blank">
                                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="https://www.google.com/search?q={{$country_code[1]}}{{ $trimNumber }}"
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
                                                                {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y') }}
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
                                                        <td class="text-capitalize"   style="max-width:250px; white-space: normal">

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

                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            <tfoot>
                                                <tr class="active">
                                                    <td colspan="10">
                                                        <div class="text-right">
                                                            {{-- {{ $leads->links('pagination::bootstrap-4')  }} --}}
                                                            <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tfoot>

                                        </table>
                                    </div> <!-- end card-box -->
                                </div> <!-- end col -->
                            </div>
                        </div>

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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{ url('status-update') }}">
                                @csrf

                                <input type="hidden" name="lead_id" id="lead_id">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Date</label>
                                            <input type="date" id="date" name="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">

                                            <label for="example-select">Lead Status</label>
                                            <select name="lead_status" class="selectpicker" data-style="btn-light"
                                                id="lead_status" placeholder="Select Lead Status" selected>

                                                @foreach ($LeadStatus as $LeadStatusData)
                                                    <option value="{{ $LeadStatusData->name }}">
                                                        {{ $LeadStatusData->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Next Follow Up Date</label>
                                            <input type="date" id="date" name="next_follow_up_date" class="form-control">
                                        </div>
                                    </div> 
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Follow Up Time</label>
                                            <input type="date" id="date" name="follow_up_time" class="form-control">
                                        </div>
                                    </div> 

                                    {{-- <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="simpleinput">Remarks of Caller</label>
                                            <textarea class="form-control" id="remarks_of_caller" name="remarks_of_caller" rows="3"></textarea>
                                        </div>
                                    </div> --}}
                {{-- </div>
                                <div class="modal-footer">
                                    <button name="submit" value="submit" type="submit"
                                        class="btn btn-primary waves-effect waves-light">Update Leads</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
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
    $('#projectName').select2({
        placeholder: 'Select Project Name Type',
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

    $('#demo-foo-filtering').dataTable( {
        "paging": false
        // lengthMenu: [
        //     [100,75, 50,25,  -1],
        //     [100,75, 50,25, 'All'],
        // ],
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
@endsection

