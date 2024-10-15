@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="row ">
        <div class="col-lg-12">
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger text-center mt-4">
                    @foreach ($errors->all() as $error)
                        @if ($error == 'The file field is required.')
                            {{ $error }}
                        @elseif($error == 'There was an error on row 2. The email_id has already been taken.')
                            {{ $error }}
                        @else
                            {{-- {{ $error }}  --}}
                            {{ 'Invalid Format ' }}

                            {{-- <a href="{{ asset('public/storage/sample/Homents Client Tracking Sheet.xlsx') }}" download>
                            <button class="btn btn-info" >sample excel</button>
                        </a>  --}}
                        @break
                    @endif
                @endforeach
        @endif

        @if (Session::has('excel'))
            <div class="alert alert-success alert-dismissible text-center">
                <h5>{{ Session::get('excel') }}</h5>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

    </div>
</div>
<div class="container-fluid">



    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Homents</a></li>
                        <li class="breadcrumb-item active">Bulk Data</li>
                    </ol>
                </div>
                <h4 class="page-title">Bulk Data</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <p>
					    To delete all records with <span class="text-danger"><strong>YES</strong></span> Bulk Upload Status press 
					    Yes, else press 
					    <span class="text-info"><strong>No</strong></span>.
					</p>


                                        <div class="modal-footer">
                                            <button name="submit" value="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light"
                                                id="delete-selected-users-btn">Yes</button>

                                            <button data-dismiss="modal" aria-label="Close"
                                                class="btn btn-primary waves-effect waves-light">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                         <p>To delete all records with  <span class="text-info">
                                            <strong>No</strong></span> Bulk Upload Status Press Yes, else press No.</p>

                                        <div class="modal-footer">
                                            <button name="submit" value="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light"
                                                id="delete-selected-users-btn-no">Yes</button>

                                            <button data-dismiss="modal" aria-label="Close"
                                                class="btn btn-primary waves-effect waves-light">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                @php
                    $leadStatus = DB::table('lead_statuses')->get();
                    $IsProject = DB::table('projects')->get();
                    $IsLocation = DB::table('locations')->get();
                    $CustomerTypes = DB::table('buyer_sellers')->get();
                    $CustomerRequirements = DB::table('project_types')->get();
                    $LeadType = DB::table('lead_type_bifurcation')->get();
                    $NumberofUnits = DB::table('number_of_units')->get();
                    $SourceTypes = DB::table('source_types')->get();
                @endphp


                <div class="row mt-2  mr-2 d-flex justify-content-end">  
                        <div class="form-group">
                            @can('Bulk Reports')
                                <a href="{{ url('bulk-reports-download') }}" class="btn btn-info waves-effect waves-light  ">
                                    Bulk Reports
                                </a>
                            @endcan
                        </div> 
                </div>

                <div class=" d-lg-flex ">
 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Customer Type</strong></label>
                            <select name="customer_type" class="selectpicker" id="customer_type">
                                <option value="" selected>View Code</option>
                                @foreach ($CustomerTypes as $Type)
                                    <option value="2">{{ $Type->id . '-' . $Type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Customer Requirement</strong></label>
                            <select name="customer_req" class="selectpicker" id="customer_req">
                                <option value="" selected>View Code</option>
                                @foreach ($CustomerRequirements as $CustomerReq)
                                    <option value="2">{{ $CustomerReq->id . '-' . $CustomerReq->project_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Location</strong></label>
                            <select name="Location" class="selectpicker" id="Location">
                                <option value="" selected>View Code</option>
                                @foreach ($IsLocation as $Location)
                                    <option value="2">{{ $Location->id . '-' . $Location->location }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Source</strong></label>
                            <select name="source" class="selectpicker" id="source">
                                <option value="" selected>View Code</option>
                                @foreach ($SourceTypes as $SourceType)
                                    <option value="2">{{ $SourceType->id . '-' . $SourceType->source_types }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="d-lg-flex">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Status</strong></label>
                            <select name="status" class="selectpicker" id="status">
                                <option value="" selected>View Code</option>
                                @foreach ($leadStatus as $Status)
                                    <option value="2">{{ $Status->id . '-' . $Status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Lead Type</strong></label>
                            <select name="lead_type" class="selectpicker" id="lead_type">
                                <option value="" selected>View Code</option>
                                @foreach ($LeadType as $LeadTypeCode)
                                    <option value="2">
                                        {{ $LeadTypeCode->id . '-' . $LeadTypeCode->lead_type_bifurcation }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Number of Units</strong></label>
                            <select name="number_of_units" class="selectpicker" id="number_of_units">
                                <option value="" selected>View Code</option>
                                @foreach ($NumberofUnits as $NumberOfUnitsCode)
                                    <option value="2">
                                        {{ $NumberOfUnitsCode->id . '-' . $NumberOfUnitsCode->number_of_units . ' unit' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-select"><strong>Project/Existing Property</strong></label>
                            <select name="Project" class="selectpicker" id="Project">
                                <option value="" selected>View Code</option>
                                @foreach ($IsProject as $Project)
                                    <option value="2">{{ $Project->id . '-' . $Project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>




                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-8 col-12">
                            <div class="row  d-flex">

                                <div class="col-md-12 col-12">
                                    <a href="{{ asset('public/storage/sample/Sample Sheet.xlsx') }}" download>
                                        <button class="btn btn-info my-1">Sample Sheet</button>
                                    </a>

                                    <button class="btn btn-danger my-1" data-toggle="modal"
                                        data-target="#exampleModal1">Bulk Delete with Yes Status</button>

                                    <button class="btn btn-danger my-1" data-toggle="modal"
                                        data-target="#exampleModal2">Bulk Delete with No Status</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="text-sm-right">
                                <form action="lead-upload" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="demo" name="cp">
                                    <div class="form-gorup">
                                        <input type="file" name="file">
                                        <button type="submit" name="submit" value="submit"
                                            class="btn btn-success waves-effect waves-light mb-2"
                                            href="{{ route('lead-upload') }}">Import</button>
                                    </div>
                                </form>
                            </div>




                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        @if (session()->has('Delete'))
                            <div class="alert alert-danger text-center">
                                {{ session()->get('Delete') }} </div>
                        @endif
                        @if (session()->has('success'))
                            <div class="alert alert-success text-center">
                                {{ session()->get('success') }} </div>
                        @endif

                        <table class="table table-centered table-nowrap table-hover mb-0" id="demo-foo-filtering"
                            data-page-size="50">
                            <thead>
                                <tr>
                                    <th data-sortable="false" >
                                        <input type="checkbox" name="test[]" id="master">
                                    </th> <!-- Add an empty header cell for the checkbox column -->
                                    <th data-sortable="false"> Action </th>
                                    <th>Delete</th>
                                    <th>Date</th>
                                    <th>Bulk Status</th>
                                    <th>Contact Number</th>
                                    <th>Customer Name</th>
                                    <th>Alt Contact Number 1</th>
                                    <th>Alt Contact Name</th>
                                    <th>Alt Contact Number 2</th>
                                    <th>Customer Email</th>
                                    <th>Customer Type</th>
                                    <th>Customer Requirement</th>
                                    <th>Buying Location</th>
                                    <th>Source</th>
                                    <th>Lead Status</th>
                                    <th>Lead Type</th>
                                    <th>Number of Units</th>
                                    <th>Existing Property</th>
                                    <th>Customer Interaction</th>
                                    <th>Customer Profile</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($LeadSheets as $LeadSheet)
                                    <div class="modal fade" id="exampleModal-{{ $LeadSheet->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <form method="post"
                                                                action="{{ url('bulk-upload-delete') }}">
                                                                @csrf

                                                                <input type="hidden" name="updatelocationID"
                                                                    value="{{ $LeadSheet->id }}">


                                                                {{-- <p>{{ 'Take Action On ' . $LeadSheet->lead_name . '(' . $LeadSheet->contact_number . ')' }}
                                                                </p> --}}

                                                                <p>{{ "Would you like to delete the selected data-Click Yes or No" }}
                                                                </p>

                                                                <div class="modal-footer">
                                                                    <button name="submit" value="submit"
                                                                        type="submit"
                                                                        class="btn btn-primary waves-effect waves-light">
                                                                      Yes</button>
                                                            </form>

                                                            <form method="post"
                                                                action="{{ url('bulk-delete-with-no-merge-data/') }}">
                                                                <input type="hidden" name="updatelocationID"
                                                                    value="{{ $LeadSheet->id }}">
                                                                @csrf
                                                                <button name="submit" value="submit" type="submit"
                                                                    class="btn btn-primary waves-effect waves-light">No</button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                    </div>

                    <div class="modal fade" id="exampleModalNo-{{ $LeadSheet->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post"
                                                action="{{ url('bulk-delete-with-no-merge-data') }}">
                                                @csrf
                                                <input type="hidden" name="updatelocationID"
                                                    value="{{ $LeadSheet->id }}">


                                                {{-- <p>{{ 'Take Action On ' . $LeadSheet->lead_name . '(' . $LeadSheet->contact_number . ')' }}
                                                </p> --}}

                                                <p>{{ 'Would You Like To Delete The Selected Data'}}</p> 

                                                

                                                <div class="modal-footer">
                                                    <button name="submit" value="submit" type="submit"
                                                        class="btn btn-primary waves-effect waves-light">Yes</button>

                                                    <button data-dismiss="modal" aria-label="Close"
                                                        class="btn btn-primary waves-effect waves-light">No</button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <tr>
                        @php
                            $existingProject = explode(',', $LeadSheet->existing_property); 
                            
                            $exiProject = App\Models\Project::whereIn('id', $existingProject)
                                ->select('project_name', 'id')
                                ->get();
                            
                            $customerType = DB::table('lead_sheets')
                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'lead_sheets.customer_type')
                                ->select('lead_sheets.*', 'buyer_sellers.name')
                                ->where('lead_sheets.customer_type', $LeadSheet->customer_type)
                                ->first();
                            
                            $leadStatusName = DB::table('leads')
                                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                ->select('leads.*', 'lead_statuses.name')
                                ->where('leads.lead_status', $LeadSheet->lead_status)
                                ->first();
                            
                            $leadIs = DB::table('leads')
                                ->where('contact_number', $LeadSheet->contact_number)
                                ->first();
                            
                            $location = App\Models\Location::where('id', $LeadSheet->location_of_leads)
                                ->select('location')
                                ->first();
                        @endphp
                        <td class="table-user">

                            <input type="checkbox" name="test[]" class="sub_chk" value="{{ $LeadSheet->id }}"
                                data-id="{{ $LeadSheet->id }}" id="user-checkbox:checked">


                        </td>

                        <td>
                            @if ($LeadSheet->is_exist == 1)
                                @php
                                    $isLeadID = $leadIs ? $leadIs->id : null;
                                @endphp
                                <a class="btn" href="{{ url('lead-status/' . encrypt($isLeadID)) }}"
                                    target="_blank">
                                    {{-- <i class="fas fa-circle text-success"></i> --}}
                                    <img style="width:20px; margin-bottom:2px"
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                </a>
                            @else
                            @endif
                        </td>

                        <td>
                            @if ($LeadSheet->is_exist == 1)
                                <a class="action-icon" data-toggle="modal"
                                    data-target="#exampleModal-{{ $LeadSheet->id }}">
                                    <i class="mdi mdi-delete"></i></a>
                            @else
                                <a class="action-icon" data-toggle="modal"
                                    data-target="#exampleModalNo-{{ $LeadSheet->id }}">
                                    <i class="mdi mdi-delete"></i></a>
                            @endif

                        </td>
                        <td>{{ $LeadSheet->date ?? 'N/A' }}</td>
                        <td>
                            {!! $LeadSheet->is_exist == 1
                                ? '<span class="text-danger font-weight-bold">Yes</span>'
                                : '<span class="text-info font-weight-bold">No</span>' !!}
                        </td>
                        <td>
                            {{ $LeadSheet->country_code_bu . $LeadSheet->contact_number ?? 'N/A' }}
                        </td>

                        <td class="table-user">
                            {{-- <img src="{{ url('') }}/assets/images/users/user-4.jpg" alt="table-user"
                                            class="mr-2 rounded-circle"> --}}
                            <a href="#" class="text-body font-weight-semibold"
                                value="{{ $LeadSheet->id }}">{{ $LeadSheet->lead_name ?? 'N/A' }}</a>
                        </td>

                        <td>
                            {{ $LeadSheet->alt_contact_number_1 ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->alt_contact_name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $LeadSheet->alt_contact_number_2 ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->customer_email ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $customerType->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $LeadSheet->project_type ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $location->location ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->source ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $leadStatusName->name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->lead_type_bifurcation ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->number_of_units . ' unit' ?? 'N/A' }}
                        </td>

                        <td>
                            @foreach ($exiProject as $ProjectName)
                                {{ $ProjectName->project_name ?? 'N/A' }}
                            @endforeach
                        </td>

                        <td class="text-wrap">
                            {{ $LeadSheet->customer_interaction ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $LeadSheet->customer_profile ?? 'N/A' }}
                        </td>



                    </tr>
                    @endforeach
                    </tbody>
                    </table>

                    <form method="post" action="{{ url('/checkbox-pool/move-to-pool') }}">
                        @csrf
                        <input type="hidden" id="demo1" name="cp1">
                        <div class="col-md-12 my-2 d-flex justify-content-end">
                            <button name="submit" value="submit" type="submit"
                                class="btn btn-primary waves-effect waves-light delete_all"
                                data-url="{{ url('/checkbox-pool/move-to-pool') }}">Move To Common Pool</button>
                        </div>
                    </form>
                </div>

                 <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $LeadSheets->links('pagination::bootstrap-4') }}
                    </ul>  

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->


</div>
<!-- end row -->
</div>
</div> <!-- container -->
@endsection

@section('scripts')
<style>
    #demo-foo-filtering_length {
        display: none;
    }
     #demo-foo-filtering_paginate{
        display: none !important;
     }
</style>

<!-- Add this script at the bottom of your HTML file, just before the closing </body> tag -->
<script>
    // Get the master checkbox element
    const masterCheckbox = document.getElementById("masterCheckbox");

    // Get all the checkboxes in the table body
    const checkboxes = document.querySelectorAll("tbody input[type='checkbox']");

    // Add an event listener to the master checkbox
    masterCheckbox.addEventListener("change", function() {
        // Set the state of all checkboxes to the state of the master checkbox
        checkboxes.forEach(checkbox => {
            checkbox.checked = masterCheckbox.checked;
        });
    });

    // Add event listeners to individual checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            // If any individual checkbox is unchecked, uncheck the master checkbox
            if (!this.checked) {
                masterCheckbox.checked = false;
            } else {
                // Check if all individual checkboxes are checked, then check the master checkbox
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                masterCheckbox.checked = allChecked;
            }
        });
    });

    $('#demo-foo-filtering').dataTable({
        lengthMenu: [
            [100, 75, 50, 25, -1],
            [100, 75, 50, 25, 'All'],
        ],
        // processing: true, 
    });
</script>


<script>
    $('#assigned-user-filter').select2({
        // selectOnClose: true,
        placeholder: "Select"

    });

    $('#common_pool').select2({
        // selectOnClose: true,
        placeholder: "Select"

    });

    $('#demo-foo-filtering').dataTable({
        lengthMenu: [
            [100, 75, 50, 25, -1],
            [100, 75, 50, 25, 'All'],
        ],
        processing: true,
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

            });

            if (allVals.length <= 0) {

            } else {
                var check = true;
                if (check == true) {
                    var join_selected_values = allVals.join(",");



                    demo1.value = join_selected_values;

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

    $('#source').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });
    $('#Project').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#Location').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#customer_type').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#customer_req').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#lead_type').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#number_of_units').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });

    $('#status').select2({
        // placeholder: "Select",
        // selectOnClose: true
    });


    $(document).ready(function() {
        $('#delete-selected-users-btn').on('click', function() {
            var selectedUserId = [];
            $('input[name="test[]"]:checked').each(function() {
                selectedUserId.push($(this).val());
                somethingChecked = true;
            });
            //  alert(selectedUserId); 
            $.ajax({
                url: '/bulk-upload-delete-yes',
                method: 'POST',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), '_method': 'post'},
                data: {
                    _token: $('input[name="_token"]').val(),
                    selectedUserId: selectedUserId
                },
                success: function(response) {
                    location.reload(); // Reload the page or update the user list as needed
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#delete-selected-users-btn-no').on('click', function() {
            var selectedUserId = [];
            $('input[name="test[]"]:checked').each(function() {
                selectedUserId.push($(this).val());
                somethingChecked = true;
            });
            //  alert(selectedUserId); 
            $.ajax({
                url: '/bulk-upload-delete-no',
                method: 'POST',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), '_method': 'post'},
                data: {
                    _token: $('input[name="_token"]').val(),
                    selectedUserId: selectedUserId
                },
                success: function(response) { 
                    location.reload(); // Reload the page or update the user list as needed
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    });

    // $('#demo-foo-filtering').DataTable({
    //     // Disable sorting by passing an empty array to the 'order' option
    //     order: []
    // });
</script>
@endsection

