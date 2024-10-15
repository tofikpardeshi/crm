@extends('main')

@section('dynamic_page')

    <style>
        .alot-btns {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end
        }

        @media only screen and (max-width: 600px) {
            .alot-btns {
                justify-content: flex-start;
                flex-direction: row;
            }

            .alot-btns>button {
                flex: 0 48%;
                margin-top: 5px
            }

            .alot-btns>a {
                flex: 0 48%;
                margin-top: 5px
            }
        }
    </style>

    <div class="row">
        <div class="col-lg-12 my-3 coppyed">
            @if (session()->has('success'))
                <div class="alert alert-success text-center" id="HideMailNotification">
                    {{ session()->get('success') }} </div>
            @endif

            <div id="success-message" style="display: none;">
                Success! You clicked the link.
            </div>


            @if (session()->has('message'))
                <div class="alert alert-danger text-center" id="HideMailNotification">
                    {{ session()->get('message') }} </div>
            @endif
            <div class="card">
                <div class="card-body">
                    @php
                        $isEmp = DB::table('employees')
                            ->where('user_id', Auth::user()->id)
                            ->first();
                        $isEmpCode = DB::table('employees')
                            ->where('id', $leads->assign_employee_id)
                            ->first();
                    @endphp
                    {{-- @if (Auth::user()->roles_id == 1) --}}
                    <div class="row">
                        <div class="col-sm-3">
                            @if ($previous)
                                <a href="{{ url('lead-status/' . encrypt($previous)) }}"
                                    @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                                    <button class="btn  btn-info"><i class="fa fa-chevron-left"></i> Previous</button>
                                </a>
                            @endif
                            @if ($next)
                                <a href="{{ url('lead-status/' . encrypt($next)) }}"
                                    @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                                    <button class="btn  btn-info">Next <i class="fa fa-chevron-right"></i></button>
                                </a>
                            @endif
                        </div>
                        <div class="col-sm-9 alot-btns">

                            <a type="button" class="btn btn-info waves-effect waves-light  mr-1" href="#"
                                data-toggle="modal" data-target="#documents">
                                <i class="fa fa-file"></i>
                                Upload file
                            </a>

                            @php
                                $isLeadDocs = DB::table('lead_gallerys')
                                    ->where('lead_id', $leads->id)
                                    ->exists();
                            @endphp

                            <a type="button" class="btn btn-info waves-effect waves-light  mr-1"
                                href="{{ url('gallary-view/' . encrypt($leads->id)) }}"
                                @if ($isLeadDocs == false) style="display:none" @endif>
                                <i class="fa fa-image"></i>
                                Gallery
                            </a>

                            <button type="button" class="clipboard btn btn-primary waves-effect waves-light  mr-1">
                                <i class="fa fa-copy"></i> Copy Link
                            </button>
                            {{-- <p>Have you already clicked?</p> --}}

                            @can('History-Update')
                                <a type="button" class="btn btn-primary waves-effect waves-light  mr-1" href="#"
                                    data-toggle="modal" data-target="#exampleModal"
                                    @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                                    <i class="fa fa-envelope"></i> Email</a>
                            @endcan

                            {{-- @can('History-Update')
                        <a type="button" class="btn btn-primary waves-effect waves-light  mr-1"
                           href="{{ url('is-send-mail-with-number/' . encrypt($leads->id)) }}">Email with Number</a>
                        @endcan

                        @can('History-Update')
                        <a type="button" class="btn btn-primary waves-effect waves-light  mr-1"
                           href="{{ url('is-send-mail-without-number/' . encrypt($leads->id)) }}">Email without
                            Number</a>
                        @endcan --}}


                            <a type="button" class="btn btn-primary waves-effect waves-light  mr-1"
                                href="{{ url('lead-info-history/' . encrypt($leads->id)) }}"
                                @if (Auth::user()->roles_id == 10) style="display:none;" @endif> <i class="fa fa-undo"></i>
                                Open History</a>



                            @can('History-Update')
                                <a type="button" class="btn btn-warning waves-effect waves-light  mr-1"
                                    href="{{ url('lead-stutas-update/' . encrypt($leads->id)) }}"> <i class="fa fa-check"></i>
                                    Update Status</a>
                            @endcan

                            @can('Update')
                                <a type="button" class="btn btn-warning waves-effect waves-light  mr-1"
                                    href="{{ url('edit-leads/' . encrypt($leads->id)) }}"><i class="fa fa-pencil-alt"></i>
                                    Edit</a>
                            @endcan

                            @can('Create')
                                <a type="button" class="btn btn-success waves-effect waves-light  mr-1 btn-darkblue"
                                    href="{{ route('create-leads') }}" target="_blank"><i class="fa fa-plus"></i> Add New</a>
                            @endcan


                            <a type="button" class="btn btn-info waves-effect waves-light  "
                                href="{{ route('leads-index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div><!-- end col-->


                    </div>
                    {{-- @elseif ($isEmp->id == $leads->assign_employee_id)
                <div class="row">
                    <div class="col-sm-6">
                        @if ($previous)
                        <a href="{{ url('lead-status/' . encrypt($previous)) }}">
                            <button class="btn  btn-info">Previous</button>
                        </a>
                        @endif
                        @if ($next)
                        <a href="{{ url('lead-status/' . encrypt($next)) }}">
                            <button class="btn  btn-info">Next</button>
                        </a>
                        @endif
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        @can('Create')
                        <a type="button" class="btn btn-danger waves-effect waves-light  mr-1"
                           href="{{ route('create-leads') }}">Add New</a>
                        @endcan

                        @can('History-Update')
                        <a type="button" class="btn btn-danger waves-effect waves-light  mr-1"
                           href="{{ url('lead-stutas-update/' . encrypt($leads->id)) }}">Update Status</a>
                        @endcan

                        @can('Update')
                        <a type="button" class="btn btn-danger waves-effect waves-light  mr-1"
                           href="{{ url('edit-leads/' . encrypt($leads->id)) }}">Edit</a>
                        @endcan


                        <a type="button" class="btn btn-danger waves-effect waves-light  "
                           href="{{ route('leads-index') }}">Back</a>
                    </div><!-- end col-->
                </div>
                @else
                @endif --}}

                    <h2 class="page-title mt-3 ">Leads Information</h2>
                    <hr>
                    <div>
                        @php
                            $location = App\Models\Location::where('id', $leads->location_of_leads)
                                ->select('location')
                                ->first();
                            
                            //dd($location);
                            
                            $trimNumber = trim($leads->contact_number);
                            $trimAltNumOne = trim($leads->alt_no_Whatsapp);
                            $trimAltNumTwo = trim($leads->alt_no_Whatsapp_2);
                            
                            $LeadType = DB::table('lead_type_bifurcation')
                                ->where('id', $leads->lead_type_bifurcation_id)
                                ->select('lead_type_bifurcation')
                                ->first();
                            
                            $Units = DB::table('number_of_units')
                                ->where('id', $leads->number_of_units)
                                ->select('number_of_units')
                                ->first();
                            
                            $arrayProject = explode(',', $leads->project_id);
                            // dd();
                            
                            $Project = App\Models\Project::whereIn('id', $arrayProject)
                                ->select('project_name', 'id')
                                ->get();
                            //dd($Project);
                            
                            $existingProject = explode(',', $leads->existing_property);


                            $existingProperty = $leads->existing_property;
                            $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                            $existingPropertyCount = count($existingProjects);

                            $projectDis = $leads->project_id;
                            $projectsDisLent = array_filter(explode(',', $projectDis), 'strlen');
                            $projectDisCount = count($projectsDisLent);


                            $CustomerRequirement = $leads->project_type;
                            $CustomerRequirementLent = array_filter(explode(',', $CustomerRequirement), 'strlen');
                            $CustomerRequirementCount = count($CustomerRequirementLent);

                            
                            
                            
                            $exiProject = App\Models\Project::whereIn('id', $existingProject)
                                ->select('project_name', 'id')
                                ->get();
                            
                            $emergeny_name = App\Models\Lead::where('id', $leads->emergeny_contact_name)
                                ->select('lead_name')
                                ->first();
                            
                            $rwa = DB::table('employees')
                                ->where('user_id', $leads->rwa)
                                ->select('employees.employee_name', 'employees.emp_country_code', 'employees.official_phone_number')
                                ->first();
                            
                            $Customer_type = DB::table('buyer_sellers')
                                ->where('id', $leads->buyer_seller)
                                ->select('name')
                                ->first();
                            
                            $s = '';
                            
                            $relationContactNumberIsRelative = DB::table('leads')
                                ->where('contact_number', $leads->emergeny_contact_number)
                                ->first();
                            
                            //dd($relationContactNumberIsRelative);
                            
                        @endphp

                        {{-- Modal For Mail Send Builder Project --}}

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Send Mail Builder Project</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">

                                                <form
                                                    action="{{ url('is-send-mail-with-number/' . encrypt($leads->id)) }}">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label for="example-email">Project Discussed/Visited <span
                                                                class="text-danger">*</span></label>
                                                        <select name="project" class="selectpicker" data-style="btn-light"
                                                            id="project" required>
                                                            <option value="">Select Project</option>
                                                            @foreach ($Project as $ProjectSendEmail)
                                                                <option value="{{ $ProjectSendEmail->id }}">
                                                                    {{ $ProjectSendEmail->project_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactNumber"> CC Email </label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="email" name="builder_cc_mail"
                                                                class="form-control" placeholder="CC Mail" multiple
                                                                value={{ old('builder_cc_mail') }}>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Notes</label>
                                                        <textarea class="form-control" name="email_notes" id="email_notes" rows="3"></textarea>

                                                    </div>


                                                    <div class="d-flex ">
                                                        <div class="form-check ">
                                                            <input class="form-check-input" type="radio"
                                                                name="flexRadioDefault" id="EmailwithNumber"
                                                                value="witNumber" required>
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                Email with Number
                                                            </label>
                                                        </div>
                                                        <div class="form-check ml-2">
                                                            <input class="form-check-input" type="radio"
                                                                name="flexRadioDefault" id="EmailwithoutNumber"
                                                                value="withoutNumber" required>
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                                Email without Number
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button name="submit" value="submit" type="submit"
                                                            class="btn btn-primary waves-effect waves-light">Send
                                                            Mail</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Modal For Mail Send End --}}

                        {{-- Modal For Lead Documnts --}}

                        <div class="modal fade" id="documents" tabindex="-1" role="dialog"
                            aria-labelledby="lead_doces" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="lead_doces">Lead Documents</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="{{ url('lead-documents-uploade/') }}"
                                                    enctype="multipart/form-data" method="post" id="uploadForm">
                                                    @csrf
                                                    <input type="hidden" value="{{ $leads->id }}" name="leadId">
                                                    <div class="form-group mb-3">
                                                        <label for="example-email"> Documents Types <span
                                                                class="text-danger">*</span></label>
                                                        <select name="documents" class="form-control"
                                                            data-style="btn-light" id="lead_documents" required>
                                                            <option value="">Select Documents</option>
                                                            <option value="1">Customer Reg. Copy</option>
                                                            <option value="2">Booking Docs</option>
                                                            <option value="3">Site Visit Pics & Docs</option>
                                                            <option value="4">BBA / ATS Docs</option>
                                                            <option value="5">Property Pictures</option>
                                                            <option value="6">KYC Buyer</option>
                                                            <option value="7">KYC Seller</option>
                                                            <option value="8">Audio File</option> 
                                                            <option value="9">Others</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactNumber"> File Upload </label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="file" name="file_uploads[]" multiple
                                                                class="form-control" required
                                                                accept=".pdf, .jpg, .jpeg, .png, .doc, .docx">
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit"
                                                            class="btn btn-primary waves-effect waves-light">Upload</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Modal For Lead Documnts End --}}

                        <div class="row d-flex justify-content-between mx-auto">
                            <div class="col-md-4">
                                <div class="my-1">Lead Generation Date:
                                    <strong>{{ \Carbon\Carbon::parse($leads->date)->format('d-M-Y H:i') }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Contact Number:
                                    {{-- @if (Auth::user()->roles_id == 1)
                                <strong>{{ $leads->contact_number }} </strong>
                                @else
                                <strong>{{ $leads->contact_number }} </strong> --}}
                                    {{-- <strong>{{ substr_replace($leads->contact_number, '******', 0, 6) }} </strong> --}}
                                    {{-- @endif --}}

                                    @php
                                        $trimNumber = trim($leads->contact_number);
                                        if ($leads->country_code == null) {
                                            $country_code = ['1' => ''];
                                        } else {
                                            $country_code = explode('+', trim($leads->country_code));
                                        }
                                        
                                        // dd($country_code);
                                        
                                    @endphp
                                    @if (Auth::user()->roles_id == 1)
                                    <a
                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $leads->official_phone_number }}&phone2={{ $leads->contact_number }}">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </a>
                                        <a class="text-muted" href="tel:{{ $leads->country_code }}{{ $trimNumber }}">
                                            {{ $leads->country_code }}{{ $trimNumber }}
                                        </a>
                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] }}{{ $trimNumber }}"
                                            target="_blank">
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                        <a href="https://www.google.com/search?q={{ $trimNumber }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                        
                                    @else
                                    <a href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $leads->official_phone_number }}&phone2={{ $leads->contact_number }}"
                                        id="custom-link">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </a>
                                        <a class="text-muted"
                                            href="tel:{{ $leads->country_code }}{{ $trimNumber }}">{{ $leads->country_code }}
                                            {{ $trimNumber }}</a>
                                        <a
                                            href="whatsapp://send?abid=phonenumber&text={{ $country_code[1] }}{{ $trimNumber }}">
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                        <a href="https://www.google.com/search?q={{ $trimNumber }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                        
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Customer Name:
                                    <strong class="text-capitalize">{{ $leads->lead_name }}</strong>
                                    <a href="https://www.google.com/search?q={{ $leads->lead_name }}" target="_blank">
                                        <i class="mdi mdi-google"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php
                                    if ($leads->alit_country_code == null) {
                                        $country_code1 = ['1' => ''];
                                    } else {
                                        $country_code1 = explode('+', trim($leads->alit_country_code));
                                    }
                                    
                                @endphp

                                @if ($leads->alt_no_Whatsapp == null)
                                    <div class="my-1">Alt Contact Number 1: <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Alt Contact Number 1:
                                        <a
                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $leads->official_phone_number }}&phone2={{ $leads->alt_no_Whatsapp }}">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </a>
                                        <a class="text-muted"
                                            href="tel:{{ $leads->alit_country_code }}{{ $trimAltNumOne }}">
                                            {{ $leads->alit_country_code }}{{ $trimAltNumOne }}</a>
                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code1[1] }}{{ $trimAltNumOne }}"
                                            target="_blank">
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                        <a href="https://www.google.com/search?q={{ $trimAltNumOne }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                         
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($leads->alt_contact_name == null)
                                    <div class="my-1">Alt Contact Name: <strong>{{ 'N/A' }}</strong>
                                    </div>
                                @else
                                    <div class="my-1">Alt Contact Name: <strong
                                            class="text-capitalize">{{ $leads->alt_contact_name }}</strong>
                                    </div>
                                @endif

                            </div>
                            <div class="col-md-4">
                                @php
                                    
                                    if ($leads->alt_country_code1 == null) {
                                        $country_code3 = ['1' => ''];
                                    } else {
                                        $country_code3 = explode('+', trim($leads->alt_country_code1));
                                    }
                                    
                                @endphp


                                @if ($leads->alt_no_Whatsapp_2 == null)
                                    <div class="my-1">Alt Contact Number 2: <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Alt Contact Number 2:
                                        <a
                                            href="http://agent.drivesu.in/iconnect/?authkey=AxjSB12ioewI91j&custid=16405&passwd=IB0118T2&phone1={{ $leads->official_phone_number }}&phone2={{ $leads->alt_no_Whatsapp_2 }}">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </a>
                                        <a class="text-muted"
                                            href="tel:{{ $leads->alt_country_code1 }}{{ $trimAltNumTwo }}"
                                            target="_blank">
                                            {{ $leads->alt_country_code1 }}{{ $trimAltNumTwo }}</a>
                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code3[1] }}{{ $trimAltNumTwo }}"
                                            target="_blank">
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                        <a href="https://www.google.com/search?q={{ $trimAltNumTwo }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a> 
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <div class="my-1">Alt Contact Name 2: <strong
                                        class="text-capitalize">{{ $leads->alt_contact_name_2 ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-4">
                                @if ($leads->lead_email == null)
                                    <div class="my-1">Customer Email:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Customer Email:
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to={{ $leads->lead_email }}"
                                            target="_blank">
                                            <strong class="text-muted">{{ $leads->lead_email }} </strong>
                                            {{-- <i class="mdi mdi-google"></i> --}}
                                            <i class="fa fa-envelope"></i>
                                        </a>
                                        <a href="https://www.google.com/search?q={{ $leads->lead_email }}"
                                            target="_blank">

                                            <i class="mdi mdi-google"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($Customer_type == null)
                                    <div class="my-1">Customer Type: <strong>{{ 'Null' }}</strong>
                                    </div>
                                @else
                                    <div class="my-1">Customer Type:
                                        <strong>{{ $Customer_type->name }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($leads->rent == null)
                                    <div class="my-1">Rent Budget:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Rent Budget:
                                        <strong>{{ $leads->rent }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Customer Requirement:
                                     {{-- @if ($CustomerRequirementCount > 1)
                                        <span class="badge badge-pill badge-primary">
                                            {{ $CustomerRequirementCount }}
                                        </span>
                                    @endif --}}
                                    <strong>{{ $leads->project_type ?? "N/A" }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">

                                @php
                                    $trimNumber = trim($leads->official_phone_number);
                                    if (isset($leads->emp_country_code)  == null) {
                                        $emp_country_code = ['1' => ''];
                                    } else {
                                        //$country_code= explode('+',trim($leads->emp_country_code));
                                        $country_code = str_replace('+', '', $isEmpCode->emp_country_code);
                                        // dd($country_code[1]);
                                    }
                                    
                                @endphp

                                <div class="my-1">Lead Assigned To: <strong>{{ $leads->employee_name }}</strong>
                                     
                                    <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1] . $leads->official_phone_number }}"
                                        target="_blank">
                                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                    </a>
                                </div>


                            </div>

                            <div class="col-md-4">
                                @php
                                    $emp = DB::table('employees')
                                        ->where('user_id', $leads->co_follow_up)
                                        ->select('employees.employee_name', 'employees.official_phone_number', 'employees.emp_country_code')
                                        ->first();

                                       
                                    
                                    $coderwa = str_replace(['+', ' '], '',  isset($rwa->emp_country_code) ?$rwa->emp_country_code : "" );
                                    
                                    $co_follow_up = str_replace(['+', ' '], '', isset($emp->emp_country_code) ?$emp->emp_country_code : "" );

                                    $fubofficial_phone_number = $emp != null ? $emp->official_phone_number : "";
                                    $rwaofficial_phone_number = $rwa != null ? $rwa->official_phone_number : "";
                                  
                                @endphp
                                <div class="my-1">Follow Up Buddy : <strong>{{ $emp->employee_name ?? 'N/A' }}</strong>
                                    <a href="https://api.whatsapp.com/send/?phone={{  $co_follow_up.  $fubofficial_phone_number}}"
                                        target="_blank" @if ($emp == null)
                                         style="display: none"
                                        @endif>
                                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                    </a>

                                    <i class="fas fa-user-plus text-info" title="Follow Up Buddy" @if ($leads->co_follow_up  !== null)
                                        @else
                                        style="display:none;" @endif> 
                                    </i>    
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="my-1">Channel Partner:
                                    <strong>{{ $rwa->employee_name ?? 'N/A' }}</strong>
                                    <a href="https://api.whatsapp.com/send/?phone={{ $coderwa.$rwaofficial_phone_number }}"
                                        target="_blank"
                                        @if ($rwa == null)
                                         style="display: none"
                                        @endif>
                                        <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                    </a>

                                    <i class="fa solid fa-handshake text-primary" title="Channel Partner"  @if ($leads->rwa  !== null )  @else style="display:none;" @endif></i> 
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="my-1">Location: <strong>{{ $location->location ?? "N/A" }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Source: <strong>{{ $leads->source }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Lead Status:
                                    @php
                                        $leadStatusName = DB::table('leads')
                                            ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                            ->select('leads.*', 'lead_statuses.name')
                                            ->where('leads.lead_status', $leads->lead_status)
                                            ->first();
                                        
                                        $BookingleadStatusName = DB::table('booking_confirms')
                                            ->join('lead_statuses', 'booking_confirms.booking_status', '=', 'lead_statuses.id')
                                            ->select('booking_confirms.*', 'lead_statuses.name')
                                            ->where('booking_confirms.booking_status', 15)
                                            ->first();
                                    @endphp
                                    @if ($leadStatusName->lead_status == $leads->lead_status)
                                        <strong
                                            @if ($leads->lead_status == 14 || $leads->lead_status == 15) class="text-danger" @else @endif>{{ $leadStatusName->name }}</strong>
                                    @else
                                        <strong>{{ $BookingleadStatusName->name }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Lead Type:
                                    <strong>{{ $LeadType ? $LeadType->lead_type_bifurcation : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="my-1">Number of Units: <strong>
                                        {{ $Units->number_of_units ?? null }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if ($leads->project_id == null || $leads->project_id == "Select Project")
                                    <div class="my-1">Project Discussed/Visited : <strong>{{ 'N/A' }}</strong>
                                    </div>
                                @else
                                    <div class="my-1">Project Discussed/Visited 
                                        @if ($projectDisCount > 1)
                                        <span class="badge badge-pill badge-primary">
                                            {{ $projectDisCount }}
                                        </span>
                                        @endif
                                         :
                                        @foreach ($Project as $ProjectName)
                                            <strong>{{ $s . $ProjectName->project_name }}</strong>
                                            @php
                                                $s = ', ';
                                            @endphp
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($leads->location_of_client == null)
                                    <div class="my-1">Location of Customer: <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Location of Customer:
                                        <strong>{{ $leads->location_of_client }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if ($leads->budget == null)
                                    <div class="my-1">Budget for Property:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Budget for Property:
                                        <strong>{{ $leads->budget }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <div class="my-1">Investment or End Use:
                                    <strong>{{ $leads->investment_or_end_user }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if ($leads->regular_investor == null)
                                    <div class="my-1">Regular Investor: <strong>{{ 'NO' }}</strong></div>
                                @else
                                    <div class="my-1">Regular Investor:
                                        <strong>{{ $leads->regular_investor }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                {{-- @php
                            $next_follow_up_date = date('Y-m-d H:i:s', strtotime($request->next_follow_up_date));
                            @endphp --}}

                                <div class="my-1">Next Follow Up Date:
                                    <strong>{{ $leads->next_follow_up_date == null ? 'N/A' : \Carbon\Carbon::parse($leads->next_follow_up_date)->format('d-M-Y H:i') }}</strong>
                                </div>
                            </div>

                            <div class="col-md-4">
                                @if ($leads->about_customer == null)
                                    <div class="my-1  text-wrap">Customer Profile:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1  text-wrap">Customer Profile:
                                        <strong>{!! $leads->about_customer !!}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                @php
                                    $employeeN = DB::table('leads')
                                        // ->join('users','users.id','leads.created_by')
                                        ->join('employees', 'employees.user_id', 'leads.created_by')
                                        ->where('employees.user_id', $leads->created_by)
                                        ->first();
                                    
                                    $trimNumber = trim(isset($employeeN->official_phone_number));
                                    $isExist = isset($employeeN->official_phone_number) ? $employeeN->official_phone_number : '';
                                    if (isset($employeeN->emp_country_code) == null) {
                                        $emp_country_code = ['1' => ''];
                                    } else {
                                        //$country_code= explode('+',trim($leads->emp_country_code));
                                        $country_code = str_replace('+', '', $employeeN->emp_country_code);
                                        // dd($country_code[1]);
                                    }
                                    
                                    // $LeadCreateUserName = App\Models\User::where('id', $leads->created_by)->first();
                                    
                                @endphp
                                @if ($leads->created_by == null)
                                    <div class="my-1">Created By: <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-1">Created By:
                                        <strong>{{ $employeeN == null ? 'N/A' : $employeeN->employee_name }}</strong>
                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code . $isExist }}"
                                            target="_blank"
                                            @if ($employeeN == null)
                                                style="display: none"
                                            @endif>
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>


                            <div class="col-md-4">
                                <div class="my-1">
                                   
                                    @if ($leads->existing_property == null)
                                        <div class="my-1">Existing Property : <strong>{{ 'N/A' }}</strong></div>
                                    @else
                                    
                                        <div class="my-1">Existing Property 
                                            @if ($existingPropertyCount > 1 )
                                            <span class="badge badge-pill badge-primary">
                                                {{ $existingPropertyCount }}
                                            </span>
                                            @endif
                                            :
                                            @foreach ($exiProject as $key => $ProjectName)
                                                @if ($key == '0')
                                                    <strong>{{ $ProjectName->project_name }}</strong>
                                                @else
                                                    <strong>{{ $s . $ProjectName->project_name }}</strong>
                                                @endif
                                                @php
                                                    $s = ', ';
                                                @endphp
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>





                        </div>




                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <span id="showMore1" class="btn btn-info ml-auto" style="cursor: pointer"
                                        onclick="$('#rentBudget').slideToggle(function () {
                                            $('#showMore1').html($('#rentBudget').is(':visible') ? '&#x25B2 Show less' : '&#x25BC Show More');
                                        });">
                                        &#x25BC Show More</span>
                                    {{-- <button class="btn btn-info ml-auto" id="showMore"> Show More </button> --}}
                                </div>
                            </div>
                        </div>



                        {{-- More Lead Detaile Section --}}
                        <div id="rentBudget" style="display: none;">


                            <div class="row d-flex justify-content-between mx-auto">
                                @php
                                    $trimRelationNumber = trim($leads->emergeny_contact_number);
                                    if ($leads->relations_country_code == null) {
                                        $relations_country_code = ['1' => ''];
                                    } else {
                                        $relations_country_code = explode('+', trim($leads->relations_country_code));
                                    }
                                    
                                    // $trimReferenceContactNumber = trim($leads->reference_contact_number);
                                    // // dd($trimReferenceContactNumber);
                                    // if ($leads->reference_contact_number == null) {
                                    // $reference_contact_number = ['1' => ''];
                                    // } else {
                                    // $reference_contact_number = explode('+', trim($leads->reference_contact_number));
                                    // }
                                    
                                @endphp
                                <div class="col-md-4">

                                    @if ($leads->emergeny_contact_number == null)
                                        <div class="my-1">Relationship Contact Number:
                                            <strong>N/A</strong>
                                        </div>
                                    @elseif($relationContactNumberIsRelative == null)
                                        <div class="my-1">Relationship Contact Number:
                                            <a class="text-muted"
                                                href="tel:{{ $leads->relations_country_code . $leads->emergeny_contact_number }}">{{ $leads->relations_country_code . $leads->emergeny_contact_number }}</a>
                                            <a href="https://api.whatsapp.com/send/?phone={{ $relations_country_code[1] }}{{ $trimRelationNumber }}"
                                                target="_blank">
                                                <i class="mdi mdi-whatsapp"></i>
                                            </a>
                                            <a href="https://www.google.com/search?q={{ $leads->emergeny_contact_number }}"
                                                target="_blank">
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </div>
                                    @elseif($leads->emergeny_contact_number == $relationContactNumberIsRelative->contact_number)
                                        <div class="my-1">Relationship Contact Number:
                                            <a class="text-muted"
                                                href="{{ url('lead-status/' . encrypt($relationContactNumberIsRelative->id)) }}">
                                                <strong>{{ $leads->emergeny_contact_number }}</strong>
                                            </a>
                                            <a
                                                href="https://api.whatsapp.com/send/?phone={{ $relations_country_code[1] }}{{ $trimRelationNumber }}">
                                                <i class="mdi mdi-whatsapp"></i>
                                            </a>
                                            <a href="https://www.google.com/search?q={{ $leads->emergeny_contact_number }}"
                                                target="_blank">
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </div>
                                    @endif

                                </div>
                                <div class="col-md-4">
                                    @if ($leads->relationship == null)
                                        <div class="my-1">Relationship Contact Name:
                                            <strong> N/A </strong>
                                        </div>
                                    @else
                                        <div class="my-1">Relationship Contact Name:
                                            <strong> {{ $leads->relationship }} </strong>
                                            <a href="https://www.google.com/search?q={{ $leads->relationship }}"
                                                target="_blank">
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="my-1">Relationship:
                                        @php
                                            $relationshipName = DB::table('leads')
                                                ->where('relationship_name', $leads->relationship_name)
                                                ->join('relationship', 'leads.relationship_name', '=', 'relationship.id')
                                                ->select('relationship.name')
                                                ->first();
                                            // dd($relationshipName);
                                            
                                            $bookingProjectName = DB::table('leads')
                                                ->where('booking_project', $leads->booking_project)
                                                ->join('projects', 'leads.booking_project', '=', 'projects.id')
                                                ->select('projects.project_name')
                                                ->first();
                                            
                                        @endphp
                                        @if ($leads->relationship_name == null)
                                            <strong>N/A</strong>
                                        @else
                                            <strong> {{ $relationshipName->name }} </strong>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    @if ($leads->booking_Date == null)
                                        <div class="my-1">Booking Date: <strong>N/A</strong>
                                        </div>
                                    @else
                                        <div class="my-1">Booking Date:
                                            <strong>{{ \Carbon\Carbon::parse($leads->booking_Date)->format('d-M-Y') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="my-1">
                                        @php
                                            $bookingProjectName = DB::table('projects')->get();
                                            $selected = explode(',', $leads->booking_project);
                                            
                                        @endphp
                                        @if ($leads->booking_project == null)
                                            Booking Project: <strong>N/A</strong>
                                        @else
                                            Booking Project:
                                            @foreach ($bookingProjectName as $project)
                                                <strong>{{ in_array($project->id, $selected) ? $project->project_name : '' }}
                                                </strong>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-4">

                                    @if ($leads->booking_amount == null)
                                        <div class="my-1">Booking Amount: <strong>N/A</strong>
                                        </div>
                                    @else
                                        <div class="my-1">Booking Amount: <strong>{{ $leads->booking_amount }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    @if ($leads->reference == null)
                                        <div class="my-1">Reference Name: <strong>N/A</strong></div>
                                    @else
                                        <div class="my-1">Reference Name: <strong>{{ $leads->reference }}</strong>
                                            <a href="https://www.google.com/search?q={{ $leads->reference }}"
                                                target="_blank">
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    @if ($leads->reference_contact_number == null)
                                        <div class="my-1">Reference Contact Number:
                                            <strong>N/A</strong>
                                        </div>
                                    @else
                                        <div class="my-1">Reference Contact Number:

                                            <strong>{{ $leads->reference_contact_number }}</strong>
                                            <a href="https://api.whatsapp.com/send/?phone={{ '+91' . $leads->reference_contact_number }}"
                                                target="_blank">
                                                <i class="mdi mdi-whatsapp"></i>
                                            </a>
                                            <a href="https://www.google.com/search?q={{ $leads->reference_contact_number }}"
                                                target="_blank">
                                                <i class="mdi mdi-google"></i>
                                            </a>

                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="my-1">

                                        @if ($leads->dob == null)
                                            Date of Birth: <strong>N/A</strong>
                                        @else
                                            Date of
                                            Birth:<strong>{{ \Carbon\Carbon::parse($leads->dob)->format('d-M-Y') }}</strong>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    @if ($leads->wedding_anniversary == null)
                                        <div class="my-1">Wedding Anniversary: <strong>N/A</strong></div>
                                    @else
                                        <div class="my-1">Wedding Anniversary:
                                            <strong>{{ \Carbon\Carbon::parse($leads->wedding_anniversary)->format('d-M-Y') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    @if ($leads->is_featured == 0)
                                        <div class="my-1">Featured: <strong>{{ 'NO' }}</strong></div>
                                    @else
                                        <div class="my-1">Featured: <strong>{{ 'YES' }}</strong></div>
                                    @endif
                                </div> 
                            </div>
                        </div>
                    </div>

                </div>

                {{-- <hr> --}}
                <div class="mt-1">
                    <div class="d-flex mt-3 ml-1">
                        <h2 class="page-title">Status History</h2>


                    </div>

                    <table class="table table-responsive table-centered table-nowrap table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Lead Status</th>
                                <th>Next Follow Up Date</th>
                                {{-- <th>Project Discussed/Visited</th> --}}
                                <th>Updated By</th>
                                <th>Notes</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leadstatushistory as $leadstatus)
                                <tr>

                                    <td>
                                        {{ \Carbon\Carbon::parse($leadstatus->created_at)->format('d-M-Y H:i') }}
                                    </td>
                                    {{-- <td>
                                {{ $leadstatus->follow_up_time }}
                            </td> --}}
                                    @php
                                        $isStatus = DB::table('lead_statuses')
                                            ->where('id', $leadstatus->status_id)
                                            ->select('lead_statuses.name')
                                            ->first();
                                    @endphp
                                    <td
                                        @if ($leadstatus->status_id == 14 || $leadstatus->status_id == 15) class="text-danger" style="font-weight: bold;" @else @endif>
                                        {{ $isStatus->name }}
                                    </td>
                                    @if ($leadstatus->next_follow_up_date == null)
                                        <td>N/A</td>
                                    @else
                                        <td>
                                            @if (\Carbon\Carbon::now()->diffInSeconds($leadstatus->next_follow_up_date, false) > 0)
                                                {{-- <div class="text-info"> --}}
                                                {{ \Carbon\Carbon::parse($leadstatus->next_follow_up_date)->format('d-M-Y H:i') }}
                                                {{-- </div> --}}
                                            @else
                                                <div class="text-danger">
                                                    {{ \Carbon\Carbon::parse($leadstatus->next_follow_up_date)->format('d-M-Y H:i') }}

                                                </div>
                                            @endif

                                        </td>
                                    @endif

                                    {{-- @php
                                        $projects = DB::table('projects')->get();
                                        $selected = explode(',', $leadstatus->project_id);
                                        $test = '';
                                    @endphp

                                    @if ($leadstatus->project_id == null)
                                        <td>N/A</td>
                                    @else
                                        <td class="text-wrap">
                                            @foreach ($projects as $project)
                                                {{ in_array($project->id, $selected) ? $project->project_name : '' }}
                                            @endforeach
                                        </td>
                                    @endif --}}



                                    @php
                                        $updatedBY = DB::table('users')
                                            ->where('id', $leadstatus->created_by)
                                            ->first();
                                    @endphp
                                    @if ($updatedBY == null)
                                        <td>N/A</td>
                                    @else
                                        <td>{{ $updatedBY->name }}</td>
                                    @endif


                                    @if ($leadstatus->customer_interaction == null)
                                        <td>N/A</td>
                                    @else
                                        <td class="text-wrap text-justify" style="min-width: 334px">

                                            {!! $leadstatus->customer_interaction !!}
                                            {{-- {{ strip_tags($leadstatus->customer_interaction) }}  --}}
                                            {{-- {{

                            (strip_tags(str_replace(array("&#39;", "&quot;","&nbsp;"), 
                            array("'", '"',' '), $leadstatus->customer_interaction)));  }}  --}}
                                            {{-- {{ \Illuminate\Support\Str::limit(strip_tags($leadstatus->customer_interaction), 20) }}
                            <br>
                            @if (strlen(strip_tags($leadstatus->customer_interaction)) > 20)
                            <button class='text-white updateStatus btn btn-info btn-sm'
                                    style="cursor:pointer" data-toggle="modal" data-target="#exampleModalCenter"
                                    value="{{ $leadstatus->customer_interaction }}">
                                Read More
                            </button>
                            @endif --}}

                                            <!-- Modal -->
                                            {{-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Customer
                                                Interaction</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ Form::textarea('comment', $leadstatus->customer_interaction, ['id' => 'lead_id', 'class' => 'form-control', 'rows' => '5', 'readonly' => 'true']) }}
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    @if (Auth::user()->roles_id == 1)
                        <a href="{{ url('lead-delete/' . encrypt($leads->id)) }}" class="action-icon">
                            <i class="mdi mdi-delete text-danger"></i></a>
                    @else
                    @endif


                    {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                {{ $leadstatushistory->links('pagination::bootstrap-4') }}
            </ul> --}}
                </div>

            </div> <!-- end card-body-->

            {{-- {{ $leadstatushistory->links(); }} --}}

        </div> <!-- end card-->
    </div> <!-- end col -->

    <!-- Modal -->



    </div>
@endsection

@section('scripts')
    <script>
        setTimeout(function() {
            $("#HideMailNotification").hide();
        }, 2000);
        $(document).ready(function() {
            $("#showMore").click(function() {
                // alert("helo");
                $("#rentBudget").toggle();
            });
        });

        //  modal in latavel 
        $(document).ready(function() {
            $(document).on('click', '.updateStatus', function() {
                var lead_id = $(this).val();
                $('#lead_id').val(lead_id)
                $('#exampleModalCenter').modal('show');
            })
        });

        $(document).ready(function() {
            $(document).on('click', '.aboutcustomer', function() {
                var lead_ids = $(this).val();
                $('#about_customer').val(lead_ids)
                $('#exampleModalCenter1').modal('show');
            })
        })


        var $temp = $("<input>");
        var $url = $(location).attr('href');

        $('.clipboard').on('click', function() {
            $("body").append($temp);
            $temp.val($url).select();
            document.execCommand("copy");
            $temp.remove();
            // $("p").text("URL copied!");
        })

        //  modal in latavel End

        // document.getElementById("uploadForm").addEventListener("submit", function(event) {
        //     const selectedDocument = document.getElementById("lead_documents").value;

        //     const fileInput = document.querySelector("input[type='file']");

        //     const allowedExtensions = {
        //         "1": ["jpeg"],
        //         "2": ["png"],
        //         "3": ["jpg"],
        //         "4": ["pdf"],
        //         "5": ["doc", "docx"]
        //     };

        //     const selectedExtensions = allowedExtensions[selectedDocument];

        //     if (!selectedExtensions) {
        //         return; // No validation needed for this document type
        //     }

        //     for (const file of fileInput.files) {
        //         const extension = file.name.split('.').pop().toLowerCase();
        //         if (!selectedExtensions.includes(extension)) { 
        //             event.preventDefault();
        //             alert("Invalid file type for the selected document type.");
        //             return;
        //         }
        //     }
        // });

        document.getElementById('custom-link').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior (navigating to another page)

            // Display the success message
            document.getElementById('success-message').style.display = 'block';
        });
    </script>
@endsection

