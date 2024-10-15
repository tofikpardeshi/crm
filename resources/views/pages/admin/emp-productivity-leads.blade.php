@extends('main')
<!-- Start Content-->


@section('dynamic_page')
    <style>
        .ls { 
            border-bottom: 1px solid #eee;
            padding: 10px 0
        }

        .ls-1 {
            font-weight: 400;
        }

        .ls-2 {
            font-weight: 700;
            border-bottom: 1px solid #eee;
        }

        .text-bold {
            font-weight: bold !important
        }

        .text-uppercase {
            text-transform: uppercase
        }

        .table tr th,
        .table tr td {
            font-size: 11px !important
        }

        .text-2 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            min-height: 27px
        }
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

                    @php
                         $currentDate = Carbon\Carbon::now();
                        // $ExpiredLeads = DB::table('leads') 
                        // ->where('next_follow_up_date','<',$currentDate)
                        // ->first();
                        //   dd($ExpiredLeads);
                    @endphp 
                        <h4 class="page-title">Productivity Lead > {{  $empIdStatus->employee_name ?? "N/A" }}</h4> 
                    
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

         
            @php
                $UserWise = DB::table('employees')
                    ->where('user_id', Auth::user()->id)
                    ->join('locations', 'employees.employee_location', '=', 'locations.id')
                    ->select('employees.*', 'locations.location')
                    ->first();
                 
                $selected = explode(',', $UserWise->employee_location);
                $multilocationemployee = DB::table('locations')
                    ->whereIn('id', $selected)
                    ->get();
                
                //dd($multilocationemployee);
                
            @endphp
             
        

       {{-- @if (Auth::user()->roles_id == 1) --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                              

                            <div id="cardCollpase5" class="collapse pt-3 show" style="font-size:11px">

                                <div class="table-responsive"> 
                                    <table id="demo-foo-filtering" class="table table-centered table-nowrap table-hover mb-0"
                                        onmousedown='return false;' onselectstart='return false;' data-placement="top"
                                        title="Do not copy sensitive data" data-page-size="100">
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
                                                <tr>
                                                    <td> 
                                                        <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                        onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($lead->id)) }}')"> 
                                                           <i class="mdi mdi-content-copy"></i> 
                                                       </span>  

                                                            <a data-toggle="tooltip" data-placement="top"
                                                                title="Check Status"
                                                                href="{{ url('lead-status-isHistory/' . 
                                                                encrypt($lead->id)) }}"
                                                                class="action-icon">
                                                                <img style="width:20px; margin-bottom:2px"
                                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                            </a> 
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i') }}
                                                    </td>
                                                    <td class="table-user text-capitalize"
                                                        style="max-width: 180px; min-width: 180px; white-space: normal">
                                                        @if ($lead->is_featured == 1)
                                                            <a href="#"
                                                                class="text-body font-weight-semibold updateStatus"
                                                                value="{{ $lead->id }}">{{ $lead->lead_name }} <span>
                                                                    <i class="fa fa-star text-success"></i></span></a>
                                                        @endif
                                                            
                                                        @if($lead->lead_status == 1)
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

                                                         <a href="#"
                                                         class="text-body font-weight-semibold updateStatus"
                                                         value="{{ $lead->id }}">
                                                         {{ $lead->lead_name }}
                                                        </a>
                                                        <i class="fas fa-user-plus text-info" title="Follow Up Buddy" @if ($lead->co_follow_up) @else style="display:none;" @endif>
                                                        </i>
                                                            
                                                         <i class="fa solid fa-handshake text-primary" title="Channel Partner"
                                                            @if ($lead->rwa  !== null)  @else style="display:none;" @endif></i> 

                                                    </td>

                                                    @php
                                                        $LeadCount = DB::table('lead_status_histories')
                                                            ->where('lead_id', $lead->id)
                                                            ->count();
                                                            $trimNumber = trim($lead->contact_number);
                                                            if ($lead->country_code == null) {
                                                                $country_code = ['1' => ''];
                                                            } else {
                                                                $country_code = explode('+', trim($lead->country_code));
                                                            }

                                                            $trimNumberEmp = trim(isset($lead->official_phone_number));

                                                       
                                                            $country_code_emp = substr($lead->emp_country_code, 1);

                                                            // dd($country_code_emp);
                                                        // }
                                                    @endphp

                                                    <td class="text-center">
                                                        <span>{{ $LeadCount }}</span>
                                                    </td>

                                                    <td>
                                                        {{ $lead->employee_name }}
                                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code_emp }}{{ $lead->official_phone_number }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if (Auth::user()->roles_id == 1)
                                                            {{-- <a class="text-muted" href="tel:{{ $lead->contact_number }}">
                                                                {{ $lead->contact_number }}</a> --}}
                                                                <a
                                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $lead->official_phone_number }}&phone2={{ $lead->contact_number }}">
                                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="text-muted" href="tel:{{ $lead->country_code }}{{ $trimNumber }}">
                                                            {{ $lead->country_code }}{{ $trimNumber }}
                                                        </a>
                                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] }}{{ $trimNumber }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                        </a>
                                                        <a href="https://www.google.com/search?q={{ $trimNumber }}" target="_blank">
                                                            <i class="mdi mdi-google"></i>
                                                        </a>
                                                        @else
                                                        
                                                        <a
                                                        href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $lead->official_phone_number }}&phone2={{ $lead->contact_number }}">
                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                    </a>
                                                            <a class="text-muted" href="tel:{{ $lead->contact_number }}">
                                                                {{ substr_replace($lead->contact_number, '******', 0, 6) }}</a>

                                                                
                                                                <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] }}{{ $trimNumber }}"
                                                                    target="_blank">
                                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="https://www.google.com/search?q={{ $trimNumber }}" target="_blank">
                                                                    <i class="mdi mdi-google"></i>
                                                                </a>
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
                                                        {{ $lead_type_bif->lead_type_bifurcation ?? "N/A" }}
                                                    </td>
                                                    @if ($lead->budget == null)
                                                        <td> N/A</td>
                                                    @else
                                                        <td>
                                                            {{ $lead->budget  }}
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
             
        {{-- @endif --}}


    </div> <!-- container -->
@endsection

@section('scripts')
<style>
    #demo-foo-filtering_length{
        display: none;
    }
</style>
    <script>
     $('#demo-foo-filtering').dataTable( {
          lengthMenu: [
              [100,75, 50,25,  -1],
              [100,75, 50,25, 'All'],
         ],
        processing: true, 
    });
        setTimeout(function() {
            $("#flashmessage").hide();
        }, 2000);

        setTimeout(function() {
            $("#notification").hide();
        }, 2000);

        setTimeout(function() {
            $("#NoSearch").hide();
        }, 2000);


        function copy_to_clipboard(link) { 
            var input = document.createElement('input');
            input.setAttribute('value', link);
            document.body.appendChild(input);
            input.select();
            var result = document.execCommand('copy');
           
            document.body.removeChild(input);
            return result; 
        }
    </script>
@endsection

