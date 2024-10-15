@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    @if (session()->has('errorFilter'))
                        <div class="alert alert-danger mt-3 text-center">
                            {{ session()->get('errorFilter') }} </div>
                    @endif
                    <div class="page-title-right">

                        <form action="{{ route('project-search') }}" method="get">
                            @csrf
                            <div class="form-group d-flex">
                                {{-- <label for="example-select">filter Leads</label> --}}
                                @php
                                    $locations = DB::table('locations')->get();
                                    $categorys = DB::table('category')->get();
                                    $isBack = true;
                                @endphp
                                <select name="filter_project" id="projectFilter" class="selectpicker"
                                    data-live-search="true">
                                    <option value="" selected>Location/Category</option>
                                    <optgroup label="Location">
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->location }}">{{ $location->location }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Category">
                                        @foreach ($categorys as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                    value="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="page-title-box">
                    @php
                        $ProjectCount = DB::table('projects')->count();
                    @endphp
                    <h4 class="page-title">Projects <br><small>Showing {{ $ProjectCount }} projects</small></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-xl-12 order-xl-1 order-2">
                <div class="card mb-2">
                    <div class="card-body">
                        @if (session()->has('delete'))
                            <div class="alert alert-danger text-center">
                                {{ session()->get('delete') }} </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-8">
                                <form class="form-inline" method="get" action="{{ url('search-project') }}">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" name="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <div class="text-lg-right mt-3 mt-lg-0">
                                    @if ($searchTrue == 1)
                                        <a href="{{ route('project-index') }}">
                                            <button type="button"
                                                class="btn btn-danger waves-effect waves-light mr-1">Back</button>
                                        </a>
                                    @endif

                                    @can('Project Reports')
                                    <a href="{{ route('project-export-reports') }}">
                                        <button type="button" class="btn btn-primary waves-effect waves-light"
                                            data-toggle="modal" data-target="#custom-modal"> Projects Reports</button>
                                    </a>
                                    @endcan
                                    

                                    @can('Create') 
                                        <a href="{{ route('create-project') }}">
                                            <button type="button" class="btn btn-success waves-effect waves-light btn-darkblue"
                                                data-toggle="modal" data-target="#custom-modal"> Add New</button>
                                        </a>
                                    @endcan
                                </div>
                            </div><!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card-body-->
                </div> <!-- end card-->



                @foreach ($projectTeams as $projectTeam)
                    <div class="card-box mb-2">
                        <div class="row align-items-center">
                            {{-- @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }} </div>
                            @endif --}}
                            <div class="col-sm-6">
                                <div class="media">
                                    {{-- @if (File::exists($projectTeam->project_image))
                                        <img src="{{ $projectTeam->project_image }}" alt="table-user"
                                            class="d-flex align-self-center mr-3 rounded-circle" controls preload="none"
                                            height="64" />
                                    @else
                                        <img src="{{ url('') }}/assets/images/users/no.png" alt="table-user"
                                            class="d-flex align-self-center mr-3 rounded-circle" controls preload="none"
                                            height="64" />
                                    @endif --}}

                                    <div class="media-body">
                                        <h4 class="mt-0 mb-2 font-15">{{ $projectTeam->project_name }}</h4>
                                        <p class="mb-0"><b>Location:</b> {{ $projectTeam->location }}</p>

                                        @php
                                            $category = DB::table('category')
                                                ->join('projects', 'category.id', '=', 'projects.project_category')
                                                ->where('category.id', $projectTeam->project_category)
                                                ->select('category.category_name')
                                                ->first();
                                            
                                            $project_status_name = DB::table('projects')
                                                ->join('project_status', 'project_status.id', '=', 'projects.project_status_id')
                                                ->where('project_status.id', $projectTeam->project_status_id)
                                                ->select('project_status.status_name')
                                                ->first();
                                            
                                            //dd($project_status_name);
                                            
                                            //Buyer Count
                                           

                                            $projectTeamId = $projectTeam->id;

                                            // Count the leads where the project_id or existing_property contains the project team ID
                                            $Projectbuyercount = DB::table('leads')
                                                ->where('buyer_seller', 1)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count(); 
                                                
                                            
                                            //seller 
                                            // Count the leads where the project_id or existing_property contains the project team ID
                                            $sellerProjectCount = DB::table('leads')
                                                ->where('buyer_seller', 2)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();  
                                           
                                            //buyer & seller count 
                                            $buyersellerProjectCount = DB::table('leads')
                                                ->where('buyer_seller', 3)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();  

                                            
                                            //Tenants count 
 
                                            $tentantProjectCount = DB::table('leads')
                                                ->where('buyer_seller', 4)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();  
                                            
                                            //owners count  
                                            $ownerProjectCount = DB::table('leads')
                                                ->where('buyer_seller', 5)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();  
                                                 
                                            //Broker / CP  

                                            // Count the leads where the project_id or existing_property contains the project team ID
                                            $BrokerCPProjectCount = DB::table('leads')
                                                ->where('buyer_seller', 6)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();

                                            //Owner - Commercial count   
                                            $ownerProjectCommercialCount = DB::table('leads')
                                                ->where('buyer_seller', 13)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();


                                            // Buyer - Commercial 

                                            $buyerProjectCommercialCount = DB::table('leads')
                                                ->where('buyer_seller', 10)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();

                                            // Seller - Commercial
                                           

                                            $ProjecttSellerCommercialCount = DB::table('leads')
                                                ->where('buyer_seller', 11)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();


                                            // Tenant - Commercial 
                                            $ProjecttTenantCommercialCount = DB::table('leads')
                                                ->where('buyer_seller', 12)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();




                                            // Resident Data

                                            $ResidentDataCommercialCount = DB::table('leads')
                                                ->where('buyer_seller', 14)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();

                                            // dd($ResidentDataCommercialCount);

                                            $InteriorsCount = DB::table('leads')
                                                ->where('buyer_seller', 15)
                                                ->where(function ($query) use ($projectTeamId) {
                                                    $query->whereRaw("FIND_IN_SET(?, project_id) OR FIND_IN_SET(?, existing_property)", [$projectTeamId, $projectTeamId]);
                                                })
                                                ->count();

                                            
                                        @endphp
                                        @if ($category == null)
                                            <p class="mb-0"><b>Category:</b> {{ 'N/A' }}
                                            </p>
                                        @else
                                            <p class="mb-0"><b>Category:</b> {{ $category->category_name }}
                                            </p>
                                        @endif

                                        @if ($project_status_name == null)
                                            <p class="mb-0"><b>Project Status:</b>
                                                N/A
                                            </p>
                                        @else
                                            <p class="mb-0"><b>Project Status:</b>
                                                {{ $project_status_name->status_name }}
                                            </p>
                                        @endif

                                        @if ($projectTeam->project_website_link == null)
                                            <p class="mb-0"><b>Project Website Link: </b> N/A</p>
                                        @else
                                            <p class="mb-0"><b>Project Website Link: </b>
                                                @php 
                                                $websiteLink = $projectTeam->project_website_link; // Remove the extra '$$' here
                                            
                                                // Add protocol if missing
                                                if (!preg_match('~^(?:f|ht)tps?://~i', $websiteLink)) {
                                                    $websiteLink = 'http://' . $websiteLink;
                                                } 
                                                @endphp
                                                <a href="{{ $websiteLink }}">
                                                    Link
                                                </a> 
                                            </p>
                                        @endif

                                        @if ($projectTeam->project_fb_group_link == null)
                                            <p class="mb-0"><b>Additonal Info Link: </b> N/A</p>
                                        @else
                                            <p class="mb-0"><b>Additonal Info Link: </b>
                                                @php 
                                                $websiteLink = $projectTeam->project_fb_group_link; // Remove the extra '$$' here
                                            
                                                // Add protocol if missing
                                                if (!preg_match('~^(?:f|ht)tps?://~i', $websiteLink)) {
                                                    $websiteLink = 'http://' . $websiteLink;
                                                } 
                                                @endphp
                                                <a href="{{ $websiteLink }}" target="_blank">
                                                    Click Here
                                                </a> 
                                            </p>
                                        @endif

                                        

                                        {{-- <h4 class="mt-0 mb-2 font-15">Project Type</h4> --}}
                                        <p class="mb-1"><b>Project Type:</b> {{ $projectTeam->project_type }}</p>


                                        {{-- <p class="mb-1"><b> Project Plan Details:</b> {!!$projectTeam->project_plan_details ?? 'N/A'!!}</p> --}}
                                      
                                    </div>



                                </div>
                            </div>
                            <div class="col-sm-3">
                                @if ($projectTeam->email == null)
                                    <p class="mb-1 mt-3 mt-sm-0"><i class="mdi mdi-email mr-1"></i>N/A</p>
                                @else
                                <i class="mdi mdi-email mr-1"></i>
                                <a class="mb-1 mt-3 mt-sm-0 text-muted" href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to={{ $projectTeam->email }}"
                                    target="_blank"> {{ $projectTeam->email }} 
                                </a> 
                                @endif

                                @if ($projectTeam->contact_number == null)
                                    <p class="mb-0">
                                        <i class="mdi mdi-phone-classic mr-1"></i> N/A
                                    </p>
                                @else
                                    <p class="mb-0">
                                        <a class="text-muted" href="tel:{{ $projectTeam->contact_number }}">
                                            <i class="mdi mdi-phone-classic mr-1"></i>
                                            {{ Auth::user()->roles_id == 1 ? $projectTeam->contact_number : substr_replace($projectTeam->contact_number, '******', 0, 6) }}</a>

                                            <a href="https://api.whatsapp.com/send/?phone={{'91'}}{{$projectTeam->contact_number }}" target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>
                                    </p>
                                @endif

                                <p class="mb-0">
                                    @php
                                        $ProjectNameOfDevelopers = DB::table('projects')
                                            ->join('name_of_developers', 'projects.name_of_developers', '=', 'name_of_developers.id')
                                            ->where('projects.id', $projectTeam->id)
                                            ->select('projects.*', 'name_of_developers.name_of_developer')
                                            ->first();
                                        
                                    @endphp
                                    @if ($ProjectNameOfDevelopers == null)
                                        <p class="mb-0">
                                            <i class="mdi mdi-account-hard-hat" style="font-size:17px"></i>N/A
                                        </p>
                                    @else
                                        <a class="text-muted">
                                            <i class="mdi mdi-account-hard-hat" style="font-size:17px"></i>
                                            {{ $ProjectNameOfDevelopers->name_of_developer }}</a>
                                    @endif

                                </p>
                            </div>
                            <div class="col-sm-3">
                                <div class=" mt-3 mt-sm-0">
                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 1) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Buyer - Residential
                                            {{ $Projectbuyercount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 2) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Seller - Residential
                                            {{ $sellerProjectCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 3) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Buyer & Seller
                                            {{ $buyersellerProjectCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 4) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Tenant - Residential
                                            {{ $tentantProjectCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 5) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Owner - Residential
                                            {{ $ownerProjectCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 6) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Broker / CP
                                            {{ $BrokerCPProjectCount }}
                                        </div>
                                    </a>

                                   

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 10) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                           Buyer - Commercial
                                            {{ $buyerProjectCommercialCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 11) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Seller - Commercial
                                            {{ $ProjecttSellerCommercialCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 12) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Tenant - Commercial
                                            {{ $ProjecttTenantCommercialCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 13) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Owner - Commercial
                                            {{ $ownerProjectCommercialCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 14) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Resident Data
                                            {{ $ResidentDataCommercialCount }}
                                        </div>
                                    </a>

                                    <a href="{{ url('project-details/' . encrypt($projectTeam->id) . '/' . 15) }}"
                                        class="mb-1 d-block">
                                        <div class="badge font-14 bg-soft-info text-info p-1">
                                            Interiors
                                            {{ $InteriorsCount }}
                                        </div>
                                    </a>

                                    

                                    

                                </div>
                            </div>
                            <div class="col-sm-12">

                                <div class="mt-2">

                                    <a class="action-icon mr-4 btn btn-success btn-xs text-light clipboard"
                                        onclick="copy_to_clipboard('{{ url('project-history/' . encrypt($projectTeam->id)) }}')"
                                        title="Copy Project Info">
                                        <i class="mdi mdi-content-copy"></i></a>

                                    @can('History-View')
                                        <a data-toggle="tooltip" data-placement="top" title="Check Status"
                                            href="{{ url('project-history/' . encrypt($projectTeam->id)) }}"
                                            class="action-icon mr-4 btn btn-success btn-xs text-light">
                                            <img style="width:20px; margin-bottom:2px"
                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                        </a>
                                    @endcan

                                    @can('View')
                                        <a href="{{ url('project-builder-list/' . encrypt($projectTeam->id)) }}"
                                            class="action-icon mr-4 btn btn-info btn-xs text-light">
                                            <i class="mdi mdi-account-hard-hat"></i></a>
                                    @endcan


                                    @can('Update')
                                        <a href="{{ url('edit-project/' . encrypt($projectTeam->id)) }}"
                                            class="action-icon mr-4 btn btn-warning btn-xs text-light">
                                            <i class="mdi mdi-square-edit-outline">
                                            </i></a>
                                    @endcan
                                    @php
                                        $DeleteProject = DB::table('leads')
                                            ->where('project_id', encrypt($projectTeam->id))
                                            ->get();
                                        
                                    @endphp
                                    @if (Auth::user()->roles_id == 1)
                                        @if ($DeleteProject->isEmpty())
                                            <a href="{{ url('project-delete/' . encrypt($projectTeam->id)) }}"
                                                class="action-icon mr-4 btn btn-danger btn-xs text-light"> <i
                                                    class="mdi mdi-delete"></i></a>
                                        @endif
                                    @endif
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card-box-->
                @endforeach


            </div> <!-- end col -->

        </div>
 
                @if(method_exists($projectTeams, 'links'))
                    <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $projectTeams->links('pagination::bootstrap-4') }}
                    </ul>  
                @endif 
            
            
            
     
        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('scripts')
    <style>
        .bootstrap-select .dropdown-menu {
            max-height: 400px !important
        }

        .bootstrap-select .dropdown-menu .inner {
            max-height: 400px !important;
            overflow-y: auto !important
        }

        /* .iti {
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
                } */

        /* .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 35px
                } */
    </style>

    <script>
        // $('#projectFilter').select2({
        //     selectOnClose: true,

        // });
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
@endsection



