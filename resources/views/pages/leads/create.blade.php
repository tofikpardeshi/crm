@extends('main')




@section('dynamic_page')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Lead</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Leads Create</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            {{-- More Links: <span class="fa fa-plus add"></span>
            <div class="appending_div">
                <div>
                    Link URL: <input type="text" name="lead_name[]"> &nbsp; Link Name: <input type="text"
                        name="contact_number[]">
                </div>
            </div> --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                {{-- @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif --}}
                                @php
                                    $isNumNotFound = session('mySearch');
                                    $numberNotFound = trim(substr($isNumNotFound, 0, strpos($isNumNotFound, ' ')));
                                    $date = \Carbon\Carbon::parse()->format('d-M-Y');
                                    
                                    $dob = Carbon\Carbon::now()->format('Y-m-d');
                                    
                                    $weddingAnniversary = Carbon\Carbon::now()->format('Y-m-d');
                                    $NextFollowTodayFeature = Carbon\Carbon::now()->format('Y-m-d\TH:i:s');
                                    $nextFollow = Carbon\Carbon::now()->addDays(2);
                                     // $nextFollow = Carbon\Carbon::now()->addDays(2);
                                   $after_ninePm=Carbon\Carbon::now()->format('H:i:s');
                                  // dd( $after_ninePm);
                                  if ($after_ninePm > '00:00:00' && $after_ninePm < '10:00:00' ) {
                                        $nextFollowAfter_ninePM = Carbon\Carbon::now();
                                        $nextFollow = \Carbon\Carbon::parse($nextFollowAfter_ninePM)->format('d-M-Y 10:00');
                                  } 
                                  else {
                                    // dd(Auth::user());
                                       if (Auth::user()->roles_id == 1) {
                                        if ($after_ninePm >='21:00:00') {
                                        $nextFollowAfter_ninePM = Carbon\Carbon::now()->addDays(1);
                                        $nextFollow = \Carbon\Carbon::parse($nextFollowAfter_ninePM)->format('d-M-Y 10:00');
                                        //dd( $nextFollow);
                                        }else {
                                            $nextFollow = Carbon\Carbon::now()->addHour(2);
                                        // dd( $nextFollow);
                                        }
                                       } else {
                                        if ($after_ninePm >='21:00:00') {
                                        $nextFollowAfter_ninePM = Carbon\Carbon::now()->addDays(2);
                                        $nextFollow = \Carbon\Carbon::parse($nextFollowAfter_ninePM)->format('d-M-Y 10:00');
                                        //dd( $nextFollow);
                                        }else {
                                            $nextFollow = Carbon\Carbon::now()->addDays(2);
                                        // dd( $nextFollow);
                                        }
                                       }
                                       
                                        
                                  }
                                  
                                   
                                  
                                  
                                    
                                @endphp

                                

                                <form method="post" id="myform" action={{ route('leads-create') }}>
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">
                                                    <strong>Lead Generation Date</strong>  
                                                    <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" id="training_date"
                                                    @if (old('date')) value="{{ old('date') }}"
                                                    @else
                                                    value="@php  echo date('Y-m-d\TH:i:s') @endphp" @endif
                                                    value="{{ old('date') }}"
                                                    name="date" step="any">
                                                @error('date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">

                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="iSwhatsapp" style="display: flex;">
                                                    <strong>Contact Number</strong> <span class="text-danger">*</span> 
                                                    <a href="https://api.whatsapp.com/send/?phone=91{{$numberNotFound}}" target="_blank" id="whatsappLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="whatsappValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{$numberNotFound}}" target="_blank" id="googleLink" class="ml-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="googleValue"></span>
                                                    </a>
                                                </label>
                                                

                                                <input type="tel" id="number" name="contact_number[main]"
                                                    value="{{ old('contact_number.main',$numberNotFound) }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="existContantNumber()"
                                                    onchange="requiredFiledValidation()"
                                                    style="background-color: #ffcccb;">

                                                <span id="valid-msg" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg" class="hide text-danger" style="display: none;"></span>
                                                <div id="myID" style="display:none">
                                                    <span id="numberDetails" value="" class="text-danger">
                                                        Contact number already registered in CRM. You may click below link
                                                        to see the details or enter a new number to register.
                                                    </span>
                                                </div>
                                                
                                                

                                                @error('contact_number.main')
                                                    <small class="text-danger">{{ $message }}</small>
                                                    <input type="hidden" name="contact_number.main"
                                                        value="{{ $a = old('contact_number.main') }}">
                                                    @php
                                                        $Lead = App\Models\Lead::where('contact_number', $a)
                                                            ->select('id')
                                                            ->first();
                                                    @endphp
                                                    @if ($message ==
                                                        'Contact number already registered in CRM. You may click below link to see the details or enter a new number to register.' .
                                                            old('contact_number.main'))
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ url('lead-status/' . encrypt($Lead->id)) }}">
                                                                View Detail
                                                            </a>
                                                        </div>
                                                    @else
                                                    @endif
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper">

                                            <div class="form-group mb-3">

                                                <label for="simpleinput" class="d-flex" id="googleLinkName"><strong>Customer Name</strong><span
                                                        class="text-danger">*</span>
                                                        <a href="https://www.google.com/search?q=" target="_blank">
                                                            <i class="mdi mdi-google"></i>
                                                            <span id="CustomerNameGoogle"></span>
                                                        </a>
                                                        </label> 
                                                <input type="text" name="lead_name" class="form-control"
                                                    value="{{ old('lead_name') }}" onchange="requiredFiledValidation()"
                                                    id="lead_name"
                                                    onkeyup="updateGoogleSearchLink()"
                                                    style="background-color: #ffcccb;">
                                                @error('lead_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label  for="simpleinput">Alt Contact Number 1
                                                    <a href="https://api.whatsapp.com/send/?phone=91" target="_blank" id="altWhatsAppLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="altGoogleLink" class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue"></span>
                                                    </a>
                                                </label>
                                                
                                                <input type="tel" id="alt_contact_number"
                                                    name="alt_contact_number[altmain]"
                                                    value="{{ old('alt_contact_number.altmain') }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateWhatsAppLink()">
                                                <span id="valid-msg-1" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-1" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink">Alt Contact Name 1
                                                    <a href="https://www.google.com/search?q=" target="_blank"
                                                        >
                                                            <i class="mdi mdi-google"></i>
                                                        </a>
                                                </label>
                                                <input type="text" id="alt_contact_name" name="alt_contact_name"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}}
                                                    value="{{ old('alt_contact_name') }}" class="form-control"
                                                    onkeyup="updateAltContactSearchLink()">
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactNumber2WhatsAppLink" >Alt Contact Number 2 
                                                    <a href="https://api.whatsapp.com/send/?phone=91" target="_blank" id="altWhatsAppLink2">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue2"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="altGoogleLink2"  class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue2"></span>
                                                    </a>
                                                </label>
                                                <input type="tel" name="alt_contact_number_2[altmain2]"
                                                    id="alt_contact_number_2"
                                                    value="{{ old('alt_contact_number_2.altmain2') }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateAltContactNumber2WhatsAppLink()">
                                                <span id="valid-msg-2" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-2" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink2">Alt Contact Name 2 
                                                    <a href="https://www.google.com/search?q=" target="_blank"
                                                        >
                                                            <i class="mdi mdi-google"></i>
                                                        </a> 

                                                </label>
                                                <input type="text" id="alt_contact_name_2" name="alt_contact_name_2"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}}
                                                    value="{{ old('alt_contact_name_2') }}" class="form-control"
                                                    onkeyup="updateAltContactSearchLink2()">
                                            </div>
                                        </div>
                                        

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Customer Email  
                                                    <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=" target="_blank" id="CustomerMailLink">
                                                        <i class="fa fa-envelope"></i>
                                                            <span id="CustomerMailValue"></span>
                                                        </i>
                                                    </a>
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="altGoogleLinkEmail" class="m-1">
                                                        <i class="mdi mdi-google">
                                                            <span id="altGoogleValueEmail"></span>
                                                        </i>
                                                    </a>
                                                </label>
                                                
                                                <input type="email" name="customer_email" id="customer_email"
                                                    value="{{ old('customer_email') }}" class="form-control">
                                                
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput"><strong>Customer Type</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="property_requirement" class="selectpicker custom-select2"
                                                    id="property_requirement" data-style="btn-light"  
                                                    placeholder="Select Lead Status" onchange="yesnoCheck(this);">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($buyerSellers as $buyerSeller)
                                                        <option value="{{ $buyerSeller->id }}"
                                                            {{ collect(old('property_requirement'))->contains($buyerSeller->id) ? 'selected' : '' }}>
                                                            {{ $buyerSeller->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('property_requirement')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4"> 
                                            @if (old('rent'))
                                                <div class="form-group mb-3" id="rentBudget">
                                                    <label for="simpleinput">Rent Budget</label>
                                                    <input type="text" name="rent" class="form-control"
                                                        value="{{ old('rent') }}">
                                                </div>
                                            @else
                                                <div class="form-group mb-3" id="rentBudget" style="display: none;">
                                                    <label for="simpleinput">Rent Budget</label>
                                                    <input type="text" name="rent" class="form-control"
                                                        value="{{ old('rent') }}">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Customer Requirement</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="customer_requirement[]" class="selectpicker custom-select2"
                                                    data-style="btn-light" style="height:20px!important"
                                                    id="customer_requirement" onchange="requiredFiledValidation()"
                                                    multiple>
                                                    {{-- <option value="" selected>Select Customer Requirement</option> --}}
                                                    @foreach ($projectTypes as $projectType)
                                                        <option value="{{ $projectType->project_type }}"
                                                            {{ collect(old('customer_requirement'))->contains($projectType->project_type) ? 'selected' : '' }}>
                                                            {{ $projectType->project_type }}</option>
                                                    @endforeach
                                                </select>

                                                @error('customer_requirement')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                @php
                                                     $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                                                @endphp
                                                <label for="example-select"><strong>Lead Assigned To</strong> <span
                                                        class="text-danger">*</span></label>
                                                        <input type="hidden" id="empId" value="{{ $empId->id }}">
                                                         @if(Auth::user()->role_id == 1)
                                                         <input type="hidden" id="isadmin" value="1">
                                                       
                                                        @else
                                                        <input type="hidden" id="isadmin" value="2">
                                                       
                                                        @endif
                                                <select name="assign_employee_id" class="selectpicker custom-select2"
                                                    data-style="btn-light" 
                                                    id="assign_employee_id"
                                                    onchange="AssignLocation(this);">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($employees as $employee)
                                                         @if (Auth::user()->roles_id == 1)
                                                    <option value="{{ $employee->id }}"
                                                        {{ collect(old('assign_employee_id'))->contains($employee->id) ? 'selected' : '' }}
                                                        {{ old('assign_employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->employee_name }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $employee->id }}"
                                                        {{ $employee->user_id == Auth::user()->id ? 'selected' : '' }}
                                                        {{ collect(old('assign_employee_id'))->contains($employee->id) ? 'selected' : '' }}
                                                        {{ old('assign_employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->employee_name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                @error('assign_employee_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        @if (Auth::user()->roles_id == 1)
                                            <div class="col-md-4">
                                                <div class="form-group mb-3" id="buying_location_hide"
                                                    @if (old('buying_location'))  @else style="display: none" @endif>
                                                    <label for="example-select"><strong>Location</strong> <span
                                                            class="text-danger">*</span></label>
                                                    <select name="buying_location" class="selectpicker custom-select2"
                                                        data-style="btn-light" id="buying_location"
                                                        onchange="requiredFiledValidation()">
                                                        <option value="" selected>Select Location</option>
                                                        @foreach ($locations as $locations)
                                                            <option value="{{ $locations->id }}"
                                                                {{ collect(old('buying_location'))->contains($locations->id) ? 'selected' : '' }}>
                                                                {{ $locations->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('buying_location')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                $indivisulEmpLocation = DB::table('employees')
                                                    ->where('user_id', Auth::user()->id)
                                                    ->first();
                                                
                                                $emplocation = explode(',', $indivisulEmpLocation->employee_location);
                                                //print_r($emplocation);
                                            @endphp
                                            <div class="col-md-4">
                                                <div class="form-group mb-3" id="buying_location_hide"
                                                    @if (old('buying_location')) style="display: inline" @endif>
                                                    <label for="example-select">Location <span
                                                            class="text-danger">*</span></label>
                                                    <select name="buying_location" class="selectpicker custom-select2"
                                                        data-style="btn-light" id="buying_location"
                                                        onchange="requiredFiledValidation()">
                                                        <option value="" selected>Select Location</option>
                                                        @foreach ($locations as $locations)
                                                            @if (in_array($locations->id, $emplocation))
                                                                <option value="{{ $locations->id }}"
                                                                    {{ collect(old('buying_location'))->contains($locations->id) ? 'selected' : '' }}>
                                                                    {{ $locations->location }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('buying_location')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Source</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="source" class="selectpicker custom-select2" data-style="btn-light"
                                                    id="source" 
                                                    {{-- onchange="requiredFiledValidation()" --}}
                                                    >
                                                    
                                                    @foreach ($SourceTypes as $SourceType)
                                                      @if ($SourceType->source_types == "YouTube")
                                                    <option value="{{ $SourceType->source_types }}" selected>
                                                        {{ $SourceType->source_types }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $SourceType->source_types }}"
                                                        {{ collect(old('source'))->contains($SourceType->source_types) ? 'selected' : '' }}>
                                                        {{ $SourceType->source_types }}
                                                    </option>
                                                    @endif 
                                                    @endforeach
                                                </select>
                                                @error('source')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="example-select"><Strong>Lead Status</Strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_status" class="selectpicker custom-select2" data-style="btn-light"
                                                data-live-search="true" 
                                                  id="lead_status"  
                                                    {{-- onchange="requiredFiledValidation()" --}}
                                                    >
                                                    @foreach ($LeadStatus as $LeadStatusData)
                                                        <option value="{{ $LeadStatusData->id }}"
                                                            {{ collect(old('lead_status'))->contains($LeadStatusData->id) ? 'selected' : '' }}
                                                            @if ($LeadStatusData->id == 5) selected @endif>
                                                            {{ $LeadStatusData->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('lead_status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="example-select"><strong>Lead Type</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_type" class="selectpicker custom-select2" data-style="btn-light"
                                                    id="lead_type" 
                                                    {{-- onchange="requiredFiledValidation()" --}}
                                                    >
                                                    {{-- <option value="" selected>Select Lead Type</option> --}}
                                                    @foreach ($leadTypeBifurcations as $leadTypeBifurcation)
                                                        @if ($leadTypeBifurcation->id == 3)
                                                            <option value="{{ $leadTypeBifurcation->id }}" selected>
                                                                {{ $leadTypeBifurcation->lead_type_bifurcation }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $leadTypeBifurcation->id }}">
                                                                {{ $leadTypeBifurcation->lead_type_bifurcation }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('lead_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Number of Units</label>
                                                <select name="number_of_units" class="selectpicker"
                                                    data-style="btn-light" id="number_of_units"
                                                    placeholder="Select Lead Status" selected>
                                                    @foreach ($number_of_units as $number_of_unit)
                                                        <option value="{{ $number_of_unit->number_of_units }}"
                                                            {{ collect(old('number_of_units'))->contains($number_of_unit->number_of_units . ' unit') ? 'selected' : '' }}
                                                            {{ old('number_of_units') == $number_of_unit->number_of_units . ' unit' ? 'selected' : '' }}>
                                                            {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Project Discussed/Visited</label>
                                                <select class="selectpicker" data-style="btn-light" name="project[]"
                                                    id="project" multiple>
                                                    {{-- <option value="" selected>Select Project</option> --}}
                                                    @foreach ($projects as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('project'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->project_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('project')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Location of Customer</label>
                                                <input type="text" name="location_of_customer" class="form-control"
                                                    value="{{ old('location_of_customer') }}">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Budget for Property</label>
                                                <select name="budget" class="selectpicker" data-style="btn-light"
                                                    id="budget">
                                                    <option value="" selected>Select Budget</option>
                                                    @foreach ($Budgets as $Budget)
                                                        <Option name="buget" value="{{ $Budget->budget }}"
                                                            {{ old('budget') == $Budget->budget ? 'selected' : '' }}>
                                                            {{ $Budget->budget }}
                                                        </Option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Investment or End User</label>

                                                <select name="investment_or_end_user" class="selectpicker"
                                                    data-style="btn-light" id="investment_or_end_user"
                                                    placeholder="Select Lead Status" selected>
                                                    <option value="" selected>Select Investment or End User</option>
                                                    {{-- @if ($leads->investment_or_end_user == 'Investment') --}}
                                                    <option value="Investment"
                                                        {{ old('investment_or_end_user') == 'Investment' ? 'selected' : '' }}>
                                                        Investment</option>
                                                    {{-- @elseif($leads->investment_or_end_user ==" End User") --}}
                                                    <option value="End User"
                                                        {{ old('investment_or_end_user') == 'End User' ? 'selected' : '' }}>
                                                        End User</option>
                                                    {{-- @elseif($leads->investment_or_end_user =="Both") --}}
                                                    <option value="Both"
                                                        {{ old('investment_or_end_user') == 'Both' ? 'selected' : '' }}>
                                                        Both</option>
                                                    {{-- @else --}}

                                                    {{-- @endif
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 ">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Regular Investor</label>
                                                <select class="selectpicker" data-style="btn-light"
                                                    name="regular_investor"
                                                    id="regular_investor">
                                                    <option value="NO"
                                                        {{ old('regular_investor') == 'NO' ? 'selected' : '' }} selected>
                                                        {{ 'NO' }}
                                                    </option>
                                                    <option value="YES"
                                                        {{ old('regular_investor') == 'YES' ? 'selected' : '' }}>
                                                        {{ 'YES' }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Next Follow Up Date</label>
                                                <input type="datetime-local" id="next_follow"
                                                    name="next_follow_up_date_lead" min="{{ $NextFollowTodayFeature }}"
                                                    step="any"
                                                    @if (old('next_follow_up_date_lead')) value="{{ old('next_follow_up_date_lead') }}"
                                                @else
                                                value="{{ date('Y-m-d\TH:i:s', strtotime($nextFollow)) }}" @endif
                                                    class="form-control">
                                            </div>
                                        </div>
                                        
                                         <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Existing Property </strong> 
                                                    <i class="fa fa-home text-info"></i> 
                                                </label>
                                                {{-- <input type="text" class="form-control" name="existing_property" id="existing_property" 
                                                value="{{ old('existing_property') }}">  --}}
                                                <select class="selectpicker" data-style="btn-light" name="existing_property[]"
                                                    id="existing_property" multiple>
                                                    {{-- <option value="" selected>Select Project</option> --}}
                                                    @foreach ($projects as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('existing_property'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->project_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Follow Up Buddy</strong></label>
                                                <select name="co_follow_up" class="selectpicker" data-style="btn-light"
                                                     id="co_follow_up"
                                                    @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="">Select</option>
                                                    @foreach ($employees as $employee) 
                                                        <option  value="{{ $employee->user_id }}"
                                                            {{ collect(old('co_follow_up'))->contains($employee->user_id) ? 'selected' : '' }}
                                                             >
                                                            {{ $employee->employee_name }}
                                                        </option> 
                                                    @endforeach
                                                </select> 
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            @php
                                                $RWAE = DB::table('employees')->where('role_id',10)->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Channel Partner</strong> </label> 
                                                <select class="selectpicker" data-style="btn-light" name="rwa" id="rwa" 
                                                @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="" selected>Select RWA</option>
                                                    @foreach ($RWAE as $RWAEMP)
                                                         @php
                                                        $isRwaAuth = \DB::table('employees')
                                                        ->where('user_id',Auth::user()->id)
                                                        ->first();
                                                    @endphp
                                                        
                                                        @if (Auth::user()->roles_id == 10)
                                                        <option value="{{ $RWAEMP->user_id }}"
                                                            {{ collect(old('rwa'))->contains($RWAEMP->user_id) ? 'selected' : '' }} selected>
                                                            {{ $isRwaAuth->employee_name }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $RWAEMP->user_id }}"
                                                            {{ collect(old('rwa'))->contains($RWAEMP->user_id) ? 'selected' : '' }}>
                                                            {{ $RWAEMP->employee_name }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-end">
                                        <span id="showMore1" class="btn btn-info ml-auto" style="cursor: pointer"
                                            onclick="$('#moreField').slideToggle(function(){$('#showMore1').html($('#moreField').is(':visible')?'&#x25B2 Show less':'&#x25BC Show More');});">
                                            &#x25BC Show More</span>
                                        {{-- <span class="btn btn-info ml-auto" id="showMore"> Show More </span> --}}
                                    </div>
                                    <div class="row" id="moreField" style="display: none;">
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_number">Relationship Contact Number
                                                    <a href="https://api.whatsapp.com/send/?phone=91" target="_blank" id="relationWhatsAppLink2">
                                                        <i class="mdi mdi-whatsapp">
                                                            <span id="RelationContactNumberWhatsapp"></span>
                                                        </i>
                                                    </a>
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="relationGoogleLinkNumber" class="m-1">
                                                        <i class="mdi mdi-google">
                                                            <span id="RelationContactNumberGoogle"></span>
                                                        </i>
                                                    </a>
                                                </label>

                                                <input type="tel" name="emergeny_contact_number[relationCC]" class="form-control"
                                                    id="RelationContactNumber" onkeyup="myFunction()"
                                                    value="{{ old('emergeny_contact_number.relationCC') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    {{-- pattern="[1-9]{1}[0-9]{9}" --}}
                                                    >
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="newRelationshipName">Relationship Contact Name
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="newRelationGoogleLink">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="NewRelationContactNameGoogle"></span>
                                                    </a>
                                                </label>
                                                <input type="text" class="form-control" name="relationship" id="relationshipName" value="{{ old('relationship') }}"> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>Relationship</label>
                                                <select name="relationshipName" class="form-control"
                                                id="relationship">
                                                    <option value="">Select</option>
                                                    @foreach ($relations as $relation)
                                                        <option value="{{ $relation->id }}"
                                                            {{ old('relationshipName') == $relation->id ? 'selected' : '' }}>
                                                            {{ $relation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Booking Date</label>
                                                <input type="date" name="booking_date" class="form-control"
                                                    value="{{ old('booking_date') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group mb-3">
                                                <label for="example-select">Booking Project</label>
                                                <select name="booking_project[]" 
                                                class="selectpicker"
                                                    data-style="btn-light" multiple id="booking_project">
                                                    {{-- <option>Select Project</option> --}}
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}"
                                                            {{ old('booking_project') == $project->id ? 'selected' : '' }}>
                                                            {{ $project->project_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Booking Amount</label>
                                                <input type="text" name="booking_amount" class="form-control"
                                                    value="{{ old('booking_amount') }}">
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="reference">Reference Name
                                                    <a href="https://www.google.com/search?q=" target="_blank" id="ReferenceLink2" class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="ReferenceNameGoogle"></span>
                                                    </a>
                                                </label>
                                                <input type="text" name="reference" class="form-control" id="reference" value="{{ old('reference') }}">
                                                
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Reference Contact Number
                                                     
                                             <a href="https://api.whatsapp.com/send/?phone=91" target="_blank" id="refWhatsAppLink2">
                                                 <i class="mdi mdi-whatsapp">
                                                     <span id="ReferenceContactNumberWhatsapp"></span>
                                                 </i>
                                             </a>
                                             <a href="https://www.google.com/search?q=" target="_blank" id="refGoogleLink2" class="m-1">
                                                 <i class="mdi mdi-google">
                                                     <span id="ReferenceContactNumberGoogle"></span>
                                                 </i>
                                             </a>
                                             
                                                </label>

                                                <input type="text" id="reference_contact_number" name="reference_contact_number"
                                                    class="form-control" value="{{ old('reference_contact_number') }}"
                                                     >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Date of Birth</label>
                                                <input type="date" max="{{ $dob }}" name="dob"
                                                    class="form-control" value="{{ old('dob') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Wedding Anniversary</label>
                                                <input type="date" name="wedding_anniversary" class="form-control"
                                                    max="{{ $weddingAnniversary }}"
                                                    value="{{ old('wedding_anniversary') }}">
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select">
                                                    <strong>Notes</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control" name="customer_interaction" id="customer_interaction" rows="4"
                                                onchange="requiredFiledValidation()" 
                                                    >{{ old('customer_interaction') }}</textarea> 
                                                @error('customer_interaction')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Customer Profile</label>
                                                <textarea class="form-control" name="about_customer" id="about_customer" rows="4">{{ old('about_customer') }}</textarea>
                                            </div>
                                        </div>

                                        <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 mb-2 d-flex align-items-center">
                                            <input id="checkbox-3" name="is_featured" type="checkbox" value="1">
                                            <label for="checkbox-3">
                                                Featured Lead
                                            </label>
                                        </div>

                                        <div
                                            class="checkbox checkbox-danger checkbox-circle  ml-2 mb-2 d-flex align-items-center">
                                            <input id="checkbox-4" name="is_dnd" type="checkbox" value="1">
                                            <label for="checkbox-4">
                                                DND
                                            </label>
                                        </div>
                                    </div>

                                    <button name="submit" value="submit" type="submit" id="formButton"
                                        onclick="AfterFormSubmitDisable()" style="pointer-events:none; filter: blur(1px)"
                                        class="btn btn-success waves-effect waves-light mr-1 btn-darkblue ">Add
                                        Lead</button>
                                        {{-- btn btn-success waves-effect waves-light  mr-1 btn-darkblue --}}
                                        <span class="btn btn-denger waves-effect waves-light" id="remove_processing"  onclick="removeProcessing()" style="display: none;">
                                            Remove Processing
                                        </span>



                            </div>

                            </form>
                        </div>
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

        
            .iti__flag{
                display: none;
            }


            .custom-select2 + .select2-container .select2-selection {
    background-color: #ffcccb !important;
}

.custom-select2 + .select2-container .select2-selection__arrow b {
    border-color: transparent transparent #ffcccb !important;
}
        
    </style>


    <script>
        // Dropdown Search Value
        $('#buying_location').select2({
            selectOnClose: true
        });
        $('#source').select2({
            selectOnClose: true
        });
        $('#assign_employee_id').select2({
            selectOnClose: true
        });
        $('#customer_requirement').select2({
            placeholder: "Select",
            // selectOnClose: true
        });

        $('#co_follow_up').select2({
          //  placeholder: "Select",
            // selectOnClose: true
        }); 
        
        $('#lead_status').select2({
            // selectOnClose: true
            placeholder: "Select"
        });
        $('#project').select2({
            // selectOnClose: true,
            placeholder: "Select"

        });
        $('#budget').select2({
            selectOnClose: true
        });
        $('#existing_property').select2({
             placeholder: "Select",
            // selectOnClose: true
         });
        $('#booking_project').select2({
            placeholder: 'Select Project',
            // selectOnClose: true  
        });

 	$('#rwa').select2({
           // placeholder: "Select",
            //selectOnClose: true
        });
        $('#property_requirement').select2({
            placeholder: 'Select', 
        });

        $('#lead_type').select2({
            placeholder: 'Select', 
            minimumResultsForSearch: Infinity
        });
        $('#number_of_units').select2({
            // placeholder: 'Select', 
            minimumResultsForSearch: Infinity
        });
        
        $('#relationship').select2({
            placeholder: 'Select', 
        });
        

        $('#investment_or_end_user').select2({
            placeholder: 'Investment or End User', 
            minimumResultsForSearch: Infinity
        });
        $('#regular_investor').select2({
            // placeholder: 'Investment or End User', 
            minimumResultsForSearch: Infinity
        });

        
        
        
        // Dropdown Search Value End

        $(document).ready(function() {
            $("#showMore").click(function() {
                //  alert("helo");
                $("#moreField").toggle();
            });
        });

        function AssignLocation(that) {
                 
            if (that.value) {
                 
                document.getElementById("buying_location_hide").style.display = "block";
            } else {
                document.getElementById("buying_location_hide").style.display = "none";
            }
            var isadmin =$('#isadmin').val();
            if (isadmin ==2) {
                var empId = $('#empId').val(); 
                    // alert(that.value);
                    // alert(empId);
                if (that.value == empId) {
                    var nextFollowUpDateN = document.getElementById("next_follow");
                    var startDateN = document.getElementById("training_date");
                    var setValueN = new Date(startDateN.value);

                    // Add 48 hours (48 * 60 * 60 * 1000 milliseconds) to the startDate
                    setValueN.setTime(setValueN.getTime() + (48 * 60 * 60 * 1000));

                
                    var formattedDate = setValueN.getFullYear() + "-" +
                        String(setValueN.getMonth() + 1).padStart(2, '0') + "-" +
                        String(setValueN.getDate()).padStart(2, '0') + " " +
                        String(setValueN.getHours()).padStart(2, '0') + ":" +
                        String(setValueN.getMinutes()).padStart(2, '0') + ":" +
                        String(setValueN.getSeconds()).padStart(2, '0');

                    // Now, formattedDate contains the date in "Y-m-d H:s:i" format
                    console.log(formattedDate);
                    nextFollowUpDateN.value =formattedDate
                    // alert(formattedDate);

                }else{
                    var nextFollowUpDateN = document.getElementById("next_follow");
                    var startDateN = document.getElementById("training_date");
                    var setValueN = new Date(startDateN.value);

                    // Add 48 hours (48 * 60 * 60 * 1000 milliseconds) to the startDate
                    setValueN.setTime(setValueN.getTime() + (2 * 60 * 60 * 1000));

                
                    var formattedDate = setValueN.getFullYear() + "-" +
                        String(setValueN.getMonth() + 1).padStart(2, '0') + "-" +
                        String(setValueN.getDate()).padStart(2, '0') + " " +
                        String(setValueN.getHours()).padStart(2, '0') + ":" +
                        String(setValueN.getMinutes()).padStart(2, '0') + ":" +
                        String(setValueN.getSeconds()).padStart(2, '0');

                    // Now, formattedDate contains the date in "Y-m-d H:s:i" format
                    console.log(formattedDate);
                    nextFollowUpDateN.value =formattedDate

                }

                            
                
            } else {
                
            }
            var empId = $('#empId').val();  
                var leadStatusSelect = $('#lead_status');
                var newSelectedValue = ''; 
                that.value == empId ?  newSelectedValue = '5' : newSelectedValue = '1' 
                leadStatusSelect.find('option[value="' + newSelectedValue + '"]').prop('selected', true);
                leadStatusSelect.trigger('change'); // Manually trigger the change event 
               //leadStatusSelect.selectpicker('refresh'); 
        };

        function yesnoCheck(that) {
            // alert("Hello");
            if (that.value == "4" || that.value == "5") {
                document.getElementById("rentBudget").style.display = "block";
            } else {
                document.getElementById("rentBudget").style.display = "none";
            }
        };
 

        var x = document.getElementById("number");
            var y = document.getElementById("numberDetails");
            
             // for whatsapp number exist or now
             x.addEventListener("input", function() {
                var inputValue = x.value; 
                var whatsappLink = document.getElementById("whatsappLink");
                var whatsappValue = document.getElementById("whatsappValue");
                
                var googleLink = document.getElementById("googleLink");
                var googleValue = document.getElementById("googleValue");
                
                if (inputValue.length === 10) { 
                    // Set WhatsApp and Google links
                    whatsappLink.href = "https://api.whatsapp.com/send/?phone=91" + inputValue;
                    googleLink.href = "https://www.google.com/search?q=" + inputValue;
                } else {
                    // Clear the values and hide the links
                    whatsappValue.textContent = "";
                    googleValue.textContent = "";
                    whatsappLink.href = "javascript:void(0)";
                    googleLink.href = "javascript:void(0)";
                }
            });
  
            // for whatsapp number exist or now endpoint

        var i = 0;

        function existContantNumber() {

            
            
            if (x.value.length > 4) {
                $.ajax({
                    url: '/SearchByContact/' + x.value,
                    method: 'get'
                }).done(function(response) {

                    if (response['lead_name'] == undefined) {
                        // alert("byy");
                        y.value = "";
                        document.getElementById("numberDetails").style.display = "none";
                        document.getElementById("myID").style.display = "none";
                        var mydiv = document.getElementById("myID");
                        var aTag = document.createElement('a');
                        var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                        var atagLength = aTag.innerHTML = "";
                        // document.getElementById("formButton").style='pointer-events:visible;';
                    } else {

                        if (i == 0) {
                            document.getElementById("numberDetails").style.display = "block";
                            var mydiv = document.getElementById("myID");
                            var aTag = document.createElement('a');
                            var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                            var atagLength = aTag.innerHTML = "View Details";
                            myID.appendChild(aTag);
                            ++i;
                            document.getElementById("formButton").style = 'pointer-events:none; filter: blur(1px)';
                        } else {
                            document.getElementById("numberDetails").style.display = "block";
                            document.getElementById("myID").style.display = "block";
                            var mydiv = document.getElementById("myID");
                            var aTag = document.createElement('a');
                            var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                            var atagLength = aTag.innerHTML = "";
                            document.getElementById("formButton").style = 'pointer-events:none; filter: blur(1px)';

                        }

                    }

                });

            }
        }


        function myFunction(search_value) {
            var x = document.getElementById("RelationContactNumber");
            //alert(x.value);
            var y = document.getElementById("relationshipName");
            if (x.value.length > 4) {
                $.ajax({
                    url: '/SearchByContact/' + x.value,
                    method: 'get'
                }).done(function(response) {
                    if (response['lead_name'] == undefined) {
                        y.value = "";
                    } else {
                        y.value = response['lead_name'];
                    }

                });
            } else {
                y.value = "";
            }
        }

        // Country Code Section

        var input = document.querySelector("#number"),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        var iti = window.intlTelInput(document.querySelector("#number"), {
            initialCountry: "In",
            preferredCountries: ['in'],
            separateDialCode: true,            
            numberType: "MOBILE",
            hiddenInput: "full",            
            // nationalMode=true 
        });






        $("form").submit(function() {

            var full_number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='contact_number[full]'").val(full_number);
            
            // alert(full_number)

        });

        var reset = function() {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.style.display = "none";
            validMsg.style.display = "none";
        };

        // on blur: validate
        input.addEventListener('blur', function() {
            reset();
            if (input.value.trim()) {
                if (iti.isValidNumber()) {
                    validMsg.style.display = "block";
                } else {
                    input.classList.add("error");
                    var errorCode = iti.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.style.display = "block";
                }
            }
        });

        // on keyup / change flag: reset
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);
        
        
        
        // Function to remove spaces from the input value
            function removeSpacesAndFormatInput() {
            var input = document.querySelector("#number");
            if (input.value) {
                input.value = input.value.replace(/\s/g, ""); // Remove spaces
            }
            }

            // Add an event listener to call the function when the page is loaded
            window.addEventListener('load', removeSpacesAndFormatInput);
        // relation_contact_number


        var input1 = document.querySelector("#RelationContactNumber"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        // initialise plugin
        var iti1 = window.intlTelInput(document.querySelector("#RelationContactNumber"), {
            initialCountry: "In",
            separateDialCode: true,
            hiddenInput: "relationCCode",
        });

        $("form").submit(function() {
            var full_numberR = iti1.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name=emergeny_contact_number[relationCCode]").val(full_numberR);
        });

        var reset = function() {
            input1.classList.remove("error");
            errorMsg1.innerHTML = "";
            errorMsg1.style.display = "none";
            validMsg1.style.display = "none";
        };

        // on blur: validate
        input1.addEventListener('blur', function() {
            reset();
            if (input1.value.trim()) {
                if (iti1.isValidNumber()) {
                    validMsg1.style.display = "block";
                } else {
                    input1.classList.add("error");
                    var errorCode = iti1.getValidationError();
                    errorMsg1.innerHTML = errorMap[errorCode];
                    errorMsg1.style.display = "block";
                }
            }
        });

        // on keyup / change flag: reset
        input1.addEventListener('change', reset);
        input1.addEventListener('keyup', reset);

        // relation_contact_number end

        // alt_contact_number


        var input1 = document.querySelector("#alt_contact_number"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        // initialise plugin
        var iti1 = window.intlTelInput(document.querySelector("#alt_contact_number"), {
            initialCountry: "In",
            separateDialCode: true,
            hiddenInput: "altfull",
        });

        $("form").submit(function() {
            var full_number1 = iti1.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='alt_contact_number[altfull]'").val(full_number1);
        });

        var reset = function() {
            input1.classList.remove("error");
            errorMsg1.innerHTML = "";
            errorMsg1.style.display = "none";
            validMsg1.style.display = "none";
        };

        // on blur: validate
        input1.addEventListener('blur', function() {
            reset();
            if (input1.value.trim()) {
                if (iti1.isValidNumber()) {
                    validMsg1.style.display = "block";
                } else {
                    input1.classList.add("error");
                    var errorCode = iti1.getValidationError();
                    errorMsg1.innerHTML = errorMap[errorCode];
                    errorMsg1.style.display = "block";
                }
            }
        });

        // on keyup / change flag: reset
        input1.addEventListener('change', reset);
        input1.addEventListener('keyup', reset);

        // alt_contact_number end


        //alt_contact_number_2

        var input2 = document.querySelector("#alt_contact_number_2"),
            errorMsg2 = document.querySelector("#error-msg-2"),
            validMsg2 = document.querySelector("#valid-msg-2");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        var iti2 = window.intlTelInput(document.querySelector("#alt_contact_number_2"), {
            initialCountry: "In",
            separateDialCode: true,
            hiddenInput: "altfull2",
        });

        $("form").submit(function() {
            var full_number2 = iti2.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='alt_contact_number_2[altfull2]'").val(full_number2);
        });

        var reset = function() {
            input2.classList.remove("error");
            errorMsg2.innerHTML = "";
            errorMsg2.style.display = "none";
            validMsg2.style.display = "none";
        };

        // on blur: validate
        input2.addEventListener('blur', function() {
            reset();
            if (input2.value.trim()) {
                if (iti2.isValidNumber()) {
                    validMsg2.style.display = "block";
                } else {
                    input2.classList.add("error");
                    var errorCode = iti2.getValidationError();
                    errorMsg2.innerHTML = errorMap[errorCode];
                    errorMsg2.style.display = "block";
                }
            }
        });


        // on keyup / change flag: reset
        input2.addEventListener('change', reset);
        input2.addEventListener('keyup', reset);

        // Country Code Section End

        //set a drop down list in a select field based on select before on laravel

        $(document).ready(function() {
            $('select[name="assign_employee_id"]').on('change', function() {
                var assign_employee_id = $(this).val();
                // alert(assign_employee_id);
                if (assign_employee_id) {
                    $.ajax({
                        url: "{{ url('/search-by-employee-assign-location/') }}/" +
                            assign_employee_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            if (data == "error") {
                                $('select[name="buying_location"]').html(
                                    '<option value="">Selected Name of Developer has No Project Assign</option>'
                                ).attr("disabled", true);
                            } else {
                                $('select[name="buying_location"]').html(
                                        '<option value="">Select Location</option>')
                                    .attr(
                                        "disabled", false);;
                                $.each(data, function(key, value) {
                                    $('select[name="buying_location"]').append(
                                        '<option value="' + value.id + '">' + value
                                        .location + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('select[name="project[]"]').html(
                            '<option value="">First Select Name of Developer</option>')
                        .attr("disabled", true);
                }
            });
        });
        //end set a drop down list in a select field based on select before on laravel

 

        function AfterFormSubmitDisable() {
            document.getElementById("formButton").style = 'pointer-events:none;'
            document.getElementById("formButton").innerHTML = 'Processing ...'
            document.getElementById("remove_processing").style=''
        }

        function removeProcessing() {
            document.getElementById("formButton").style = '';
            document.getElementById("formButton").innerHTML = 'Add Lead'
            document.getElementById("remove_processing").style='display: none;'

            exit();

        }

        // today extand 2 Days 
        // $('#training_date').change(function() {
        //     var today = new Date();
        //     var dd = String(today.getDate()).padStart(2, '0');
        //     var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        //     var yyyy = today.getFullYear();
        //     today = yyyy + '-' + mm + '-' + dd; 
        //     var nextFollowUpDate = document.getElementById("next_follow");
        //     var startDate = document.getElementById("training_date");
        //     var setValue1 = new Date(startDate.value);
        //     var AddDateDays = setValue1.setDate(setValue1.getDate() + 2);
        //     var nextFollowDate1 = new Date(setValue1);
        //     var month1 = nextFollowDate1.getMonth() + 1;
        //     if (month1.toString().length === 1) {
        //         var isMontsSingle1 = '0' + month1;
        //     } else {
        //         var isMontsSingle1 = month1;
        //     }
        //     var day1 = ("0" + nextFollowDate1.getDate()).slice(-2);
        //     var year1 = nextFollowDate1.getFullYear();
        //     var dateFormate1 = [year1, isMontsSingle1, day1].join('-');
        //     if (dateFormate1 >= today) { 
        //         var setValue = new Date(startDate.value);
        //         var AddDateDays = setValue.setDate(setValue.getDate() + 2);
        //         var nextFollowDate = new Date(setValue);
        //         var month = nextFollowDate.getMonth() + 1;
        //         if (month.toString().length === 1) {
        //             var isMontsSingle = '0' + month;
        //         } else {
        //             var isMontsSingle = month;
        //         }
        //         var day = ("0" + nextFollowDate.getDate()).slice(-2);
        //         var year = nextFollowDate.getFullYear();
        //         // var hours = nextFollowDate.getHours();
        //         // var minuts = nextFollowDate.getMinutes();
        //         // var seconds = nextFollowDate.getSeconds();
        //         var hours = '11';
        //         var minuts = '00';
        //         var seconds = '00';
        //         var dateFormate = [year, isMontsSingle, day].join('-');
        //         var timeFormate = [hours, minuts, seconds].join(':');
        //         var dateTime = [dateFormate, timeFormate].join('T');
        //         document.getElementById("next_follow").value = dateTime;
        //     } else {
        //         document.getElementById("next_follow");
        //     }

        // });

        // today extand 2 Days  end



        //todays extands @hours start 
        // today extand 2 Days 
        // today extand 2 Days 
        $('#training_date').change(function() {
            var today = new Date();
           // alert(today);
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd; 
            var nextFollowUpDate = document.getElementById("next_follow");
            var startDate = document.getElementById("training_date");
            var setValue1 = new Date(startDate.value);
            
            
             // Get the current date
            var currentDate = new Date(); 
            currentDate.setDate(currentDate.getDate() - 1);

           
            // alert(currentDate);

            // is Old Lead Created  
            var isOldStatus = new Date(setValue1);
            var leadStatusSelect = $('#lead_status');
            var newSelectedValue = '';
            // Extract year, month, and day components from both dates
            var currentYear = currentDate.getFullYear();
            var currentMonth = currentDate.getMonth();
            var currentDay = currentDate.getDate();

            var setValue1Year = setValue1.getFullYear();
            var setValue1Month = setValue1.getMonth();
            var setValue1Day = setValue1.getDate();

            // Compare the date components
            if (currentYear === setValue1Year &&
                currentMonth === setValue1Month &&
                currentDay === setValue1Day) { 
               newSelectedValue = '1';
            } else {  
               isOldStatus < new Date() ?  newSelectedValue = '18' : newSelectedValue = '1' 
            } 
            leadStatusSelect.find('option[value="' + newSelectedValue + '"]').prop('selected', true);
            leadStatusSelect.trigger('change'); // Manually trigger the change event 
            leadStatusSelect.selectpicker('refresh'); 
             // is Old Lead Created End
             
            var AddDateDays = setValue1.setDate(setValue1.getDate() );
            var nextFollowDate1 = new Date(setValue1);
            var month1 = nextFollowDate1.getMonth() + 1;
            if (month1.toString().length === 1) {
                var isMontsSingle1 = '0' + month1;
            } else {
                var isMontsSingle1 = month1;
            }
            var day1 = ("0" + nextFollowDate1.getDate()).slice(-2);
            var year1 = nextFollowDate1.getFullYear();
            var dateFormate1 = [year1, isMontsSingle1, day1].join('-');
            
            if (dateFormate1 < today) {
                // selected date is in the past 
                var nextFollowDate0 = new Date(); 
                if (month1.toString().length === 1) {
                var isMontsSingle0 = '0' + month1;
                } else {
                    var isMontsSingle0 = month1;
                }
                var day0 = ("0" + nextFollowDate0.getDate()).slice(-2);
                var IsMonthDateEnd = Date.setMonth(day0 , ['30','31','28']); 
                var year0 = nextFollowDate0.getFullYear();
                var hours0 = nextFollowDate0.getHours()+ 2;
                var minuts0 = nextFollowDate0.getMinutes();
                var seconds0 = '00';
                var dateFormate0 = [year0, isMontsSingle0, day0].join('-');
                var timeFormate0 = [hours0, minuts0, seconds0].join(':');
                var dateTime0 = [dateFormate0, timeFormate0].join('T');
                document.getElementById("next_follow").value = dateTime0;
            }
            
            if (dateFormate1 >= today) { 
                var setValue = new Date(startDate.value);
                // var AddDateDays = setValue.setDate(setValue.getDate());
                 var nextFollowDate = new Date(setValue);
                // var month = nextFollowDate.getMonth() + 1;
                
              
                if (nextFollowDate.getHours() > '00' && nextFollowDate.getHours() < '10') {
                    //next day at 10:00
                    var AddDateDays = setValue.setDate(setValue.getDate());
                    var nextFollowDate = new Date(setValue);
                    var month = nextFollowDate.getMonth() + 1;
                            if (month.toString().length === 1) {
                            var isMontsSingle = '0' + month;
                        } else {
                            var isMontsSingle = month;
                        }
                    var day = ("0" + nextFollowDate.getDate()).slice(-2);
                    var year = nextFollowDate.getFullYear();
                    var hours = '10';
                    var minuts = '00';
                } else {

                    if (nextFollowDate.getHours() >='21') {
                        var AddDateDays = setValue.setDate(setValue.getDate() + 1);
                        var nextFollowDate = new Date(setValue);
                        var month = nextFollowDate.getMonth() + 1;
                            if (month.toString().length === 1) {
                             var isMontsSingle = '0' + month;
                            } else {
                                var isMontsSingle = month;
                            }
                        var day = ("0" + nextFollowDate.getDate()).slice(-2);
                        var year = nextFollowDate.getFullYear();
                        var hours = '10';
                         var minuts = '00';
                     
                        }else {
                            var AddDateDays = setValue.setDate(setValue.getDate());
                            var nextFollowDate = new Date(setValue);
                            var month = nextFollowDate.getMonth() + 1;
                            if (month.toString().length === 1) {
                                var isMontsSingle = '0' + month;
                            } else {
                                var isMontsSingle = month;
                            }
                            var day = ("0" + nextFollowDate.getDate()).slice(-2);
                            var year = nextFollowDate.getFullYear();
                            var hours = nextFollowDate.getHours()+2;
                            var minuts = nextFollowDate.getMinutes();
                        }
                    
                }
               
                // var year = nextFollowDate.getFullYear();
                // var hours = nextFollowDate.getHours();
                // var minuts = nextFollowDate.getMinutes();
                // var seconds = nextFollowDate.getSeconds();
               // alert(nextFollowDate.getHours());
                // if (nextFollowDate.getHours() > '00' && nextFollowDate.getHours() < '10') {
                //     //next day at 10:00
                //     nextFollowDate= nextFollowDate.setDate(nextFollowDate.getDate() );
                //    // alert('ggg');
                //     var hours = '10';
                //     var minuts = '00';
                // } else {

                //     if (nextFollowDate.getHours() >='21') {
                        
                //        var nextFollowDate12=setValue1.setDate(setValue1.getDate() + 1);
                //         // var day12 = ("0" + nextFollowDate12.getDate()).slice(-2);
                //         // alert('lll');
                //         //  alert(day12);
                //         //  alert(nextFollowDate12);
                //         var hours = '10';
                //         var minuts = '00';
                //         }else {
                //             alert('pp');
                //             var hours = nextFollowDate.getHours()+2;
                //             var minuts = nextFollowDate.getMinutes();
                //         }
                    
                // }
              // alert(day);
                var seconds = '00';
                var dateFormate = [year, isMontsSingle, day].join('-');
                var timeFormate = [hours, minuts, seconds].join(':');
                var dateTime = [dateFormate, timeFormate].join('T');
               //(dateTime);
                document.getElementById("next_follow").value = dateTime;
            } else {
                document.getElementById("next_follow");
            }

        });






        //todays Extand @hours end
    </script>
     
 <script>
    


    function requiredFiledValidation() {
        var myID = document.getElementById("myID");
        var lead_name = document.getElementById("lead_name");
        var customer_requirement = document.getElementById("customer_requirement"); 
        var property_requirement = document.getElementById("property_requirement");
        var assign_employee_id = document.getElementById("assign_employee_id");
        var buying_location = document.getElementById("buying_location");
        var mainNumber = document.getElementById("number").value;
          
         
            // CKEditor content is not empty, check other form fields
            // and enable the form button if all required fields have values
            if (lead_name.value != "" && customer_requirement.value != "" && property_requirement.value != "" && buying_location.value != "" && assign_employee_id.value != "" && mainNumber != "") { 
                document.getElementById("formButton").style = 'pointer-events:visible;';
            } else {
                document.getElementById("formButton").style = 'pointer-events:none; filter: blur(1px);';
            }
       
    }

    // Call the validation function on CKEditor content change event
    // CKEDITOR.instances['customer_interaction'].on("change", requiredFiledValidation);

</script>

<script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script>  
    <script>
  
     new nicEditor().panelInstance('customer_interaction');
    
    new nicEditor().panelInstance('about_customer');
       
  
</script>

<script>
  
   
   
     function updateGoogleSearchLink() {
    var searchInput = document.getElementById("lead_name");
    var googleLinkDiv = document.getElementById("googleLink");
    var googleLink = googleLinkDiv.querySelector("a");
    var icon = googleLink.querySelector("i");

    var inputValue = searchInput.value.trim();

    if (inputValue.length > 0) {
        googleLinkDiv.style.display = "block";
        googleLink.href = "https://www.google.com/search?q=" + inputValue;
        icon.style.display = "inline"; // Show the icon
    } else {
        googleLinkDiv.style.display = "none";
    }
}


 var altX = document.getElementById("alt_contact_number"); // Replace with your actual input field for Alt Contact Number 1

altX.addEventListener("input", function() {
    var altInputValue = altX.value;

    var altWhatsAppLink = document.getElementById("altWhatsAppLink");
    var altWhatsAppValue = document.getElementById("altWhatsAppValue");

    var altGoogleLink = document.getElementById("altGoogleLink");
    var altGoogleValue = document.getElementById("altGoogleValue");

    if (altInputValue.length === 10) {
        // Set Alt WhatsApp and Google values
        // altWhatsAppValue.textContent = altInputValue;
        // altGoogleValue.textContent = altInputValue;

        // Set Alt WhatsApp and Google links
        altWhatsAppLink.href = "https://api.whatsapp.com/send/?phone=91" + altInputValue;
        altGoogleLink.href = "https://www.google.com/search?q=" + altInputValue;
    } else {
        // Clear the values and hide the links
        altWhatsAppValue.textContent = "";
        altGoogleValue.textContent = "";
        altWhatsAppLink.href = "javascript:void(0)";
        altGoogleLink.href = "javascript:void(0)";
    }
});


function updateAltContactSearchLink() {
    var searchInput = document.getElementById("alt_contact_name");
    var googleLinkDiv = document.getElementById("altContactLink");
    var googleLink = googleLinkDiv.querySelector("a");
    var icon = googleLink.querySelector("i");

    var inputValue = searchInput.value.trim();

    if (inputValue.length > 0) {
        googleLinkDiv.style.display = "block";
        googleLink.href = "https://www.google.com/search?q=" + inputValue;
        icon.style.display = "inline"; // Show the icon
    } else {
        googleLinkDiv.style.display = "none";
    }
}

function updateAltContactSearchLink2() {
    var searchInput = document.getElementById("alt_contact_name_2");
    var googleLinkDiv = document.getElementById("altContactLink2");
    var googleLink = googleLinkDiv.querySelector("a");
    var icon = googleLink.querySelector("i");

    var inputValue = searchInput.value.trim();

    if (inputValue.length > 0) {
        googleLinkDiv.style.display = "block";
        googleLink.href = "https://www.google.com/search?q=" + inputValue;
        icon.style.display = "inline"; // Show the icon
    } else {
        googleLinkDiv.style.display = "none";
    }
}

var altContact2Input = document.getElementById("alt_contact_number_2"); // Replace with your actual input field for Alt Contact Number 2

altContact2Input.addEventListener("input", function() {
    var altContact2InputValue = altContact2Input.value;

    var altWhatsAppLink = document.getElementById("altWhatsAppLink2");
    var altWhatsAppValue = document.getElementById("altWhatsAppValue2");

    var altGoogleLink = document.getElementById("altGoogleLink2");
    var altGoogleValue = document.getElementById("altGoogleValue2");

    if (altContact2InputValue.length === 10) {
        // Set Alt WhatsApp and Google values within the icons
        // altWhatsAppValue.textContent = altContact2InputValue;
        // altGoogleValue.textContent = altContact2InputValue;

        // Set Alt WhatsApp and Google links
        altWhatsAppLink.href = "https://api.whatsapp.com/send/?phone=91" + altContact2InputValue;
        altGoogleLink.href = "https://www.google.com/search?q=" + altContact2InputValue;

        // Show Alt WhatsApp and Google icons
        altWhatsAppLink.style.display = "inline";
        altGoogleLink.style.display = "inline";
    } else {
        // Clear the Alt WhatsApp and Google values and hide the icons
        altWhatsAppValue.textContent = "";
        altGoogleValue.textContent = "";
        altWhatsAppLink.href = "javascript:void(0)";
        altGoogleLink.href = "javascript:void(0)";
        altWhatsAppLink.style.display = "none";
        altGoogleLink.style.display = "none";
    }
});

</script> 

<script>
    var emailInput = document.getElementById("customer_email"); // Replace with your actual input field for Customer Email

emailInput.addEventListener("input", function() {
    var emailInputValue = emailInput.value;

    var customerMailLink = document.getElementById("CustomerMailLink");
    var customerMailValue = document.getElementById("CustomerMailValue");

    if (emailInputValue.trim().length > 0) {
        // Set the Customer Email value within the Google icon
        // customerMailValue.textContent = emailInputValue;
        
        // Set the Customer Email link
        customerMailLink.href = "https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=" + emailInputValue;
        
        // Show the Google icon
        customerMailLink.style.display = "inline";
    } else {
        // Clear the Customer Email value and hide the Google icon
        customerMailValue.textContent = "";
        
        // Hide the Google icon
        customerMailLink.style.display = "none";
    }
});



var emailInput = document.getElementById("customer_email"); // Replace with your actual input field for Customer Email

emailInput.addEventListener("input", function() {
    var emailInputValue = emailInput.value;

    var customerMailLink = document.getElementById("CustomerMailLink");
    var customerMailValue = document.getElementById("CustomerMailValue");

    var altGoogleLinkEmail = document.getElementById("altGoogleLinkEmail");
    var altGoogleValueEmail = document.getElementById("altGoogleValueEmail");

    if (emailInputValue.trim().length > 0) {
        // Set Customer Email value within the Google icon
        // customerMailValue.textContent = emailInputValue;
        // altGoogleValueEmail.textContent = emailInputValue;

        // Set Customer Email and Google Search links
        customerMailLink.href = "https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=" + emailInputValue;
        altGoogleLinkEmail.href = "https://www.google.com/search?q=" + emailInputValue;

        // Show Customer Email and Google Search icons
        customerMailLink.style.display = "inline";
        altGoogleLinkEmail.style.display = "inline";
    } else {
        // Clear the values and hide the icons
        customerMailValue.textContent = "";
        altGoogleValueEmail.textContent = "";
        customerMailLink.href = "javascript:void(0)";
        altGoogleLinkEmail.href = "javascript:void(0)";
        customerMailLink.style.display = "none";
        altGoogleLinkEmail.style.display = "none";
    }
});
 
    var referenceContactInput = document.getElementById("reference_contact_number");
    var whatsappLink = document.getElementById("refWhatsAppLink2");
    var whatsappValue = document.getElementById("ReferenceContactNumberWhatsapp");
    var googleLink = document.getElementById("refGoogleLink2");
    var googleValue = document.getElementById("ReferenceContactNumberGoogle");

    referenceContactInput.addEventListener("input", function() {
        // Remove non-numeric characters using a regular expression
        var cleanedValue = this.value.replace(/\D/g, "");

        // Ensure the value starts with a non-zero digit and is 10 digits long
        if (/^[1-9]\d{9}$/.test(cleanedValue)) {
            // Set the WhatsApp and Google values
            // whatsappValue.textContent = cleanedValue;
            // googleValue.textContent = cleanedValue;

            // Set the WhatsApp and Google links
            whatsappLink.href = "https://api.whatsapp.com/send/?phone=91" + cleanedValue;
            googleLink.href = "https://www.google.com/search?q=" + cleanedValue;

            // Show the WhatsApp and Google links
            // whatsappLink.style.display = "inline";
            // googleLink.style.display = "inline";
        } else {
            // Clear the values and hide the links
            whatsappValue.textContent = "";
            googleValue.textContent = "";
            whatsappLink.href = "javascript:void(0)";
            googleLink.href = "javascript:void(0)";
            // whatsappLink.style.display = "none";
            // googleLink.style.display = "none";
        }

        // Update the input field value
        this.value = cleanedValue;
    }); 
    

    var relationContactInput = document.getElementById("RelationContactNumber");
var relationWhatsAppLink = document.getElementById("relationWhatsAppLink2");
var relationWhatsAppValue = document.getElementById("RelationContactNumberWhatsapp");
var relationGoogleLink = document.getElementById("relationGoogleLink2");
var relationGoogleValue = document.getElementById("RelationContactNumberGoogle");

relationContactInput.addEventListener("input", function() {
    // Remove non-numeric characters using a regular expression
    var cleanedValue = this.value.replace(/\D/g, "");

    // Set the WhatsApp and Google values
    //relationWhatsAppValue.textContent = cleanedValue;
    //relationGoogleValue.textContent = cleanedValue;

    // Set the WhatsApp and Google links
    relationWhatsAppLink.href = "https://api.whatsapp.com/send/?phone=91" + cleanedValue;
    relationGoogleLinkNumber.href = "https://www.google.com/search?q=" + cleanedValue;

    // Show the WhatsApp and Google links
    relationWhatsAppLink.style.display = "inline";
    relationGoogleLink.style.display = "inline";

    // Update the input field value
    this.value = cleanedValue;
});



 
    var newRelationshipNameInput = document.getElementById("RelationshipName");
    var newRelationGoogleLink = document.getElementById("newRelationGoogleLink");
    var newRelationContactNameGoogleValue = document.getElementById("NewRelationContactNameGoogle");

    newRelationshipNameInput.addEventListener("input", function() {
        var newRelationContactName = this.value.trim();

        // Set the Google value
        // newRelationContactNameGoogleValue.textContent = newRelationContactName;

        // Set the Google link
        newRelationGoogleLink.href = "https://www.google.com/search?q=" + newRelationContactName;

        // Show the Google link
        newRelationGoogleLink.style.display = "inline";
    });
 
    var referenceNameInput = document.getElementById("reference");
    var relationGoogleLink = document.getElementById("ReferenceLink2");
    var referenceNameGoogleValue = document.getElementById("ReferenceNameGoogle");

    referenceNameInput.addEventListener("input", function() {
        var referenceName = this.value.trim();

        // Set the Google value
        // referenceNameGoogleValue.textContent = referenceName;

        // Set the Google link
        relationGoogleLink.href = "https://www.google.com/search?q=" + referenceName;

        // Show the Google link
        relationGoogleLink.style.display = "inline";
    }); 

    var customerNameInput = document.getElementById("lead_name");
var customerNameGoogleLink = document.getElementById("googleLinkName").querySelector("a");
var customerNameGoogleValue = document.getElementById("CustomerNameGoogle");

customerNameInput.addEventListener("input", function() {
    var customerName = this.value.trim();

    // Set the Google value
    // customerNameGoogleValue.textContent = customerName;

    // Set the Google link
    customerNameGoogleLink.href = "https://www.google.com/search?q=" + customerName;
});




</script>
@endsection

