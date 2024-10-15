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
                    <h4 class="page-title">Leads</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                {{-- <form class="form-inline" >
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" id="inputPassword2"
                                            placeholder="Search..." value="{{ old('search') }}">
                                    </div>
                                </form> --}}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                        href="{{ url('leads') }}">Back</a>
                                </div>
                            </div><!-- end col-->
                        </div>

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
                            <table class="table table-centered table-nowrap table-hover mb-0">
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

                                                <a href="{{ url('edit-leads/' . encrypt($lead->id)) }}" class="action-icon">
                                                    <i class="mdi mdi-square-edit-outline">
                                                    </i></a>

                                                <a data-toggle="tooltip" data-placement="top" title="Check Status"
                                                    href="{{ url('lead-status/' . encrypt($lead->id)) }}" class="action-icon">
                                                    <img style="width:20px; margin-bottom:2px"
                                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                </a>
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
                                                    <a href="#" class="text-body font-weight-semibold updateStatus"
                                                        value="{{ $lead->id }}">{{ $lead->lead_name }} <span>
                                                            <i class="fa fa-star text-success"></i></span></a>
                                                @else
                                                    <a href="#" class="text-body font-weight-semibold updateStatus"
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
                                                @php
                                                    $emp = DB::table('employees')
                                                    ->where('id',$lead->assign_employee_id)
                                                    ->first()
                                                @endphp
                                                {{ $emp->employee_name }}
                                            </td>
                                            <td>
                                                @if (Auth::user()->roles_id == 1)
                                                <a class="text-muted"
                                                href="tel:{{ $lead->contact_number }}">
                                            {{ $lead->contact_number }}</a>
                                                @else
                                                <a class="text-muted"
                                                href="tel:{{ $lead->contact_number }}">
                                                {{ substr_replace($lead->contact_number,"******",0,6)  }}</a>
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
                                            {{-- project type --}}
                                            <td class="text-capitalize">
                                                {{ $lead->project_type }}
                                            </td>
                                            {{-- customer type --}}
                                            @php
                                                $customerType = DB::table('leads')
                                                    ->join('buyer_sellers', 'buyer_sellers.id', '=','leads.buyer_seller')
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

                                            @if ($lead->budget ==  null)
                                            <td>N/A</td>
                                            @else
                                            <td>
                                                {{ $lead->budget }}
                                            </td>
                                            @endif
                                            

                                            <td>
                                                @php
                                                $leadStatusName = DB::table('leads')
                                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                                    ->select('leads.*', 'lead_statuses.name')
                                                    ->where('leads.id', $lead->id)
                                                    ->first();
                                            @endphp
                                                {{ $leadStatusName->name }} 
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        {{-- <span>{{ $leads->links() }}</span> --}}
                        <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{-- {{ $leads->links('pagination::bootstrap-4'); }} --}}
                        </ul>

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
@endsection
