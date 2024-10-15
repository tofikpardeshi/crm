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
                            <li class="breadcrumb-item active">Employees</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Employees</h4>
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
                                {{--  <form class="form-inline" method="get" action="{{ url('search-employee') }}">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" name="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form>  --}}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                            
                                           <!--  @if (Auth::user()->roles_id == 1)
                                            
                                            @else
                                                
                                            @endif -->
                                    @can('Create')
                                    
                                     @if (Auth::user()->roles_id == 1)  
                                         <a type="button" class="btn btn-primary waves-effect waves-light mb-2"
                                            href="{{ route('employee-export') }}">Employee Reports</a>  
                                          @else
                                                
                                            @endif  
                                    
                                   {{-- <a type="button" class="btn btn-primary waves-effect waves-light mb-2"
                                            href="{{ route('employee-export') }}">Employee Reports</a>  --}}
                                            
                                        <a type="button" class="btn btn-success waves-effect waves-light mb-2 btn-darkblue"
                                            href="{{ route('create-employee-view') }}">Add New</a> 
                                    @endcan

                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                             
                            <table id="demo-foo-filtering" class="table table-centered table-nowrap table-hover mb-0" 
                            data-page-size="100">
                                <thead>
                                    <tr>
                                        <th style="width: 82px;">Action</th>
                                        <th>Employee ID</th>
                                        <th>Nick Name</th>
                                        <th>Role</th>
                                        <th>Official Phone Number</th>
                                        <th>Department</th>
                                        <th  style="max-width: 350px; white-space: normal">Location</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        @if (Auth::user()->id != $employee->user_id)
                                            <tr>
                                                <td>
                                                      @can('Update')
                                                        {{-- <a href="{{ url('edit-employee/' . $employee->id) }}"
                                                            class="action-icon">
                                                            <i class="mdi mdi-square-edit-outline">
                                                            </i></a> --}}
                                                            <a data-toggle="tooltip" data-placement="top" title="Check Status"
                                                            href="{{ url('employees-detaile/' . encrypt($employee->id)) }}"
                                                            class="action-icon">
                                                            <img style="width:20px; margin-bottom:2px"
                                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                                        </a>
                                                    @endcan  
                                                    
                                                </td>
                                                <td>
                                                    {{ $employee->employeeID }}
                                                </td>
                                                <td class="table-user">

                                                    @if (File::exists($employee->emplayees_photo))
                                                        <img src="{{ $employee->emplayees_photo }}" alt="table-user"
                                                            class="mr-2 rounded-circle" controls preload="none" />
                                                    @else
                                                        <img src="{{ url('') }}/assets/images/users/no.png"
                                                            alt="table-user" class="mr-2 rounded-circle" controls
                                                            preload="none" />
                                                    @endif

                                                    <a href="javascript:void(0);"
                                                        class="text-body font-weight-semibold">{{ $employee->employee_name }}</a>
                                                </td>
                                                <td>
                                                    {{ $employee->name }}
                                                </td>
                                                @if ($employee->official_phone_number == null)
                                                    <td>N/A</td>
                                                @else
                                                    <td>
                                                        <a class="text-muted"
                                                            href="tel:{{ $employee->official_phone_number }}">{{ $employee->official_phone_number }}</a>
<a  href="https://api.whatsapp.com/send/?phone={{'91'}}{{$employee->official_phone_number }}" target="_blank">
                                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                            </a>
                                                    </td>
                                                @endif

                                                @php
                                                    $empDepartment = DB::table('departments')
                                                        ->where('id', $employee->department)
                                                        ->first();
                                                @endphp

                                                @if ($empDepartment == null)
                                                    <td>N/A</td>
                                                @else
                                                    <td> {{ $empDepartment->department_name }}</td>
                                                @endif

                                                @php
                                                    $selected = explode(',', $employee->employee_location);
                                                    
                                                    // $empLocation = DB::table('locations')
                                                    //     ->where('id', $employee->employee_location)
                                                    //     ->first();
                                                @endphp
                                                <td style="max-width: 350px; min-width: 350px; white-space: normal">
                                                    @foreach ($selected as $item)
                                                        @php
                                                            $name = DB::table('locations')
                                                                ->where('id', $item)
                                                                ->first();
                                                                //  dd($name->location);
                                                            
                                                        @endphp
                                                        {{ $name->location ?? null }}
                                                    @endforeach
                                                </td>

                                                @php
                                                    $today = Carbon\Carbon::now()->format('Y-m-d');
                                                    $leavingDate = \Carbon\Carbon::parse($employee->leaving_date)->format('Y-m-d');
                                                    $userLoginStatus = DB::table('users')
                                                        ->where('id', $employee->user_id)
                                                        ->first();
                                                    // dd($userLoginStatus->login_status);
                                                @endphp
                                               
                                                    <td>
                                                        @if ($userLoginStatus->login_status == 0 &&$employee->leaving_date == null)
                                                        <div class="text-danger">
                                                            {{ 'Inactive' }}
                                                        </div>
                                                    @elseif($employee->leaving_date == null)
                                                    <div class="text-success">
                                                        {{ 'Active' }}
                                                    </div>
                                                    @else
                                                        @if ($today <= $leavingDate && $userLoginStatus->login_status == 1)
                                                        <div class="text-success">
                                                            <div class="text-success">
                                                                {{ 'Active' }}
                                                            </div>
                                                        </div>
                                                            
                                                        @elseif(!$today <= $leavingDate)
                                                            <div class="text-danger">
                                                                {{ 'Inactive' }}
                                                            </div>
                                                        @elseif($userLoginStatus->login_status == 1)
                                                        <div class="text-success">
                                                            {{ 'Active' }}
                                                        </div>
                                                        @else
                                                            <div class="text-danger">
                                                                {{ 'Inactive' }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif

                                                <td>

                                                    {{ \Carbon\Carbon::parse($employee->created_at)->format('j-F-Y H:i') }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $employees->links('pagination::bootstrap-4'); }}
                        </ul> --}}

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection


@section('scripts')

<style>
    #demo-foo-filtering_info{
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

    
</script>

@endsection
