@extends('main')

{{-- <style>
    .iti{
        display: inline-flexbox!important;
    }
</style> --}} 

@section('dynamic_page')
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
                    <h4 class="page-title">Update Lead</h4>
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
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                @php
                                     $isNumNotFound = session('mySearch');
                                    $numberNotFound = trim(substr($isNumNotFound, 0, strpos($isNumNotFound, ' ')));
                                    $date = \Carbon\Carbon::parse()->format('d-M-Y'); 
                                    $MainNumber = preg_replace('/\s+/', '',str_replace('+', '', $leads->country_code)) . $leads->contact_number; 
                                    $altNumber = preg_replace('/\s+/', '',str_replace('+', '', $leads->alit_country_code)) . $leads->alt_no_Whatsapp;  
                                    $altNumber2 = preg_replace('/\s+/', '',str_replace('+', '', $leads->alt_country_code1)) . $leads->alt_no_Whatsapp_2;
                                    $relationsNumber = preg_replace('/\s+/', '',str_replace('+', '', $leads->relations_country_code)).$leads->emergeny_contact_number; 
                                @endphp
                                <form method="post" action="{{ route('update-lead', $leads->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput"><strong>Lead Generation Date</strong> <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" id="training_date"
                                                @if (old('date'))
                                                    value="{{ old('date') }}"
                                                    @else
                                                    
                                                    value="@php  echo date('Y-m-d\TH:i:s',strtotime($leads->date)) @endphp"
                                                    @endif
                                                     name="date"
                                                    step="any"
                                                    >
                                                @error('date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <input type="hidden" value="{{ $leads->id }}" name="leadId" id="leads_Id">
                                            {{-- <input type="hidden" value="{{ $leads->country_code }}" name="country_code" id="country_code_hidden"> --}}
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="iSwhatsapp" style="display: flex;">
                                                    <strong>Contact Number</strong> <span class="text-danger">*</span> 
                                                    <a href="https://api.whatsapp.com/send/?phone={{ $MainNumber }}" target="_blank" id="whatsappLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="whatsappValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $leads->contact_number }}" target="_blank" id="googleLink" class="ml-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="googleValue"></span>
                                                    </a>
                                                </label>
                                                
                                                
                                                <input type="tel" id="number" name="contact_number[main]"
                                                    value="{{ $leads->contact_number }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="existContantNumber()"
                                                    data-initial-country="{{ $countryCodeMainIso->iso ?? "In"  }}"
                                                    @if (\Auth::user()->roles_id == 1)
                                                        
                                                    @else
                                                        readonly
                                                    @endif>

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
                                                            <a href="{{ url('lead-status/' . encrypt($leads->id)) }}">
                                                                View Detail {{ $leads->id }}
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
                                                        <a href="https://www.google.com/search?q={{ $leads->lead_name }}" target="_blank" >
                                                                <i class="mdi mdi-google" ></i>
                                                            <span id="CustomerNameGoogle" ></span>
                                                        </a>
                                                        </label> 
                                                <input type="text" name="lead_name" class="form-control"
                                                    value="{{ $leads->lead_name }}" onchange="requiredFiledValidation()"
                                                    id="lead_name"
                                                    onkeyup="updateGoogleSearchLink()">
                                                @error('lead_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4 wrapper1"> 
                                            <div class="form-group mb-3">
                                                <label  for="simpleinput">Alt Contact Number 1
                                                    <a href="https://api.whatsapp.com/send/?phone={{$altNumber}}" target="_blank" id="altWhatsAppLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $leads->alt_no_Whatsapp }}" target="_blank" id="altGoogleLink" class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue"></span>
                                                    </a>
                                                </label>
                                                
                                                <input type="tel" id="alt_contact_number"
                                                     data-initial-country="{{ $countryCodeAltIso->iso ?? "In"  }}"
                                                    name="alt_contact_number[altmain]"
                                                    value="{{ $leads->alt_no_Whatsapp }}"  
                                                    class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateWhatsAppLink()" 
                                                    >
                                                <span id="valid-msg-1" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-1" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div> 
                                        
                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink">Alt Contact Name 1
                                                    <a href="https://www.google.com/search?q={{$leads->alt_contact_name}}" target="_blank"
                                                        >
                                                            <i class="mdi mdi-google"></i>
                                                        </a>
                                                </label>
                                                <input type="text" id="alt_contact_name" name="alt_contact_name"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}}
                                                    value="{{ $leads->alt_contact_name }}" class="form-control"
                                                    onkeyup="updateAltContactSearchLink()">
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactNumber2WhatsAppLink" >Alt Contact Number 2 
                                                   <a href="https://api.whatsapp.com/send/?phone={{ $altNumber2 }}" target="_blank" id="altWhatsAppLink2">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue2"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $leads->alt_no_Whatsapp_2 }}" target="_blank" id="altGoogleLink2"  class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue2"></span>
                                                    </a>
                                                </label>
                                                <input type="tel" name="alt_contact_number_2[altmain2]"
                                                    id="alt_contact_number_2"
                                                    data-initial-country="{{ $countryCodeAlt2Iso->iso ?? "In" }}"
                                                    value="{{ $leads->alt_no_Whatsapp_2 }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateAltContactNumber2WhatsAppLink()"
                                                     >
                                                <span id="valid-msg-2" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-2" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink2">Alt Contact Name 2 
                                                    <a href="https://www.google.com/search?q={{ $leads->alt_contact_name_2 }}" target="_blank"
                                                        >
                                                            <i class="mdi mdi-google"></i>
                                                        </a> 

                                                </label>
                                                <input type="text" id="alt_contact_name_2" name="alt_contact_name_2"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}}
                                                    value="{{ $leads->alt_contact_name_2 }}" class="form-control"
                                                    onkeyup="updateAltContactSearchLink2()">
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Customer Email  </label>
                                                <input type="email" name="customer_email" value="{{ $leads->lead_email }}"
                                                    class="form-control">
                                                    {{-- @error('customer_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror --}}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput"><strong>Customer Type</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="buyer_seller" 
                                                class="selectpicker" 
                                                data-style="btn-light"
                                                    id="property_requirement" 
                                                    onchange="yesnoCheck(this);"
                                                    placeholder="Select Lead Status" selected
                                                    
                                                    >
                                                    {{-- <option value="" selected>Select</option> --}}
                                                    @foreach ($buyerSellers as $buyerSeller)
                                                        @if ($buyerSeller->id == $leads->buyer_seller)
                                                            <option value="{{ $buyerSeller->id }}" selected>
                                                                {{ $buyerSeller->name }}</option>
                                                        @else
                                                            <option value="{{ $buyerSeller->id }}">
                                                                {{ $buyerSeller->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('property_requirement')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div> 
                                        </div>

                                        <div class="col-md-4">
                                            @if ($leads->rent)
                                                <div class="form-group mb-3" id="rentBudget">
                                                    <label for="simpleinput">Rent Budget</label>
                                                    <input type="text" name="rent" class="form-control"
                                                        value="{{ $leads->rent }}">
                                                </div>
                                            @else
                                                <div class="form-group mb-3" id="rentBudget" style="display: none;">
                                                    <label for="simpleinput">Rent Budget</label>
                                                    <input type="text" name="rent" id="test"
                                                        class="form-control" value="{{ null }}">
                                                </div>
                                            @endif

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Customer Requirement</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="customer_requirement[]" class="selectpicker"
                                                    data-style="btn-light" multiple id="customer_requirement">
                                                    @php
                                                        $selected = explode(',', $leads->project_type);
                                                    @endphp
                                                    {{-- <option value="">Select Customer Requirement</option> --}}
                                                    @foreach ($projectTypes as $projectType)
                                                        <option value="{{ $projectType->project_type }}"
                                                            {{ in_array($projectType->project_type, $selected) ? 'selected' : '' }}>
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

                                                <label for="example-select"><strong>Lead Assigned To</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="assign_employee_id" class="selectpicker"
                                                    data-style="btn-light" id="assign_employee_id"
                                                    @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="" selected>Select Assign Employees</option>
                                                    @foreach ($employees as $employee)
                                                        @if ($employee->id == $leads->assign_employee_id)
                                                            <option value="{{ $employee->id }}" selected>
                                                                {{ $employee->employee_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $employee->id }}">
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

                                        <div class="col-md-4">
                                            @php
                                                $emp = DB::table('employees')
                                                    ->where('id', $leads->assign_employee_id)
                                                    ->first();
                                                
                                                $selected = explode(',', $emp->employee_location);
                                                
                                                //print_r($selected)
                                                
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Location</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="buying_location" class="selectpicker"
                                                    data-style="btn-light" id="buying_location"
                                                    @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="" selected>Select Assign Location</option>
                                                    @foreach ($locations as $location)
                                                         {{-- @if ($location->id == $leads->location_of_leads)
                                                            <option value="{{ $location->id }}" selected>
                                                                {{ $location->location }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $location->id }}">
                                                                {{ $location->location }}
                                                            </option>
                                                        @endif   --}}
                                                         @if (in_array($location->id, $selected))
                                                            @if ($leads->location_of_leads == $location->id)
                                                            <option value="{{ $location->id }}" selected>
                                                                {{ $location->location }}</option>
                                                            @else
                                                            <option value="{{ $location->id }}">
                                                                {{ $location->location }}</option>
                                                            @endif 
                                                        @else 
                                                        @endif  
                                                    @endforeach
                                                </select>
                                                @error('buying_location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Source</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="source" class="selectpicker" data-style="btn-light"
                                                    id="source">
                                                    <option value="" selected>Select Source Types</option>
                                                    @foreach ($SourceTypes as $SourceType)
                                                        @if ($SourceType->source_types == $leads->source)
                                                            <option value="{{ $SourceType->source_types }}" selected>
                                                                {{ $SourceType->source_types }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $SourceType->source_types }}">
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

                                                <label for="example-select"><strong>Lead Status</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_status" class="selectpicker" data-style="btn-light"
                                                  id="lead_status"   
                                                    {{-- data-live-search="true"  --}}
                                                    placeholder="Select Lead Status" selected>

                                                    @foreach ($LeadStatus as $LeadStatusData)
                                                        @if ($LeadStatusData->id == $leads->lead_status)
                                                            <option value="{{ $LeadStatusData->id }}" selected>
                                                                {{ $LeadStatusData->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $LeadStatusData->id }}">
                                                                {{ $LeadStatusData->name }}
                                                            </option>
                                                        @endif
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
                                                <select name="lead_type" class="selectpicker" data-style="btn-light"
                                                    id="lead_type">
                                                    {{-- <option selected="">Select Lead Status</option> --}}
                                                    @foreach ($LeadTypes as $LeadType)
                                                        @if ($LeadType->id == $leads->lead_type_bifurcation_id)
                                                            <option value="{{ $LeadType->id }}" selected>
                                                                {{ $LeadType->lead_type_bifurcation }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $LeadType->id }}">
                                                                {{ $LeadType->lead_type_bifurcation }}
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
                                                id="number_of_units"
                                                    data-style="btn-light" id="example-select"
                                                    placeholder="Select Lead Status" selected>
                                                    @foreach ($number_of_units as $number_of_unit)
                                                        @if ($number_of_unit->number_of_units == $leads->number_of_units)
                                                            <option
                                                                value="{{ $number_of_unit->number_of_units }}"
                                                                selected>
                                                                {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                        @else
                                                            <option
                                                                value="{{ $number_of_unit->number_of_units  }}">
                                                                {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            @php
                                                $selected = explode(',', $leads->project_id);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-email">Project Discussed/Visited</label>
                                                <select name="project[]" class="selectpicker" data-style="btn-light"
                                                    id="project" multiple>
                                                    <option value="">Select Project</option>
                                                    @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        {{ in_array($project->id, $selected) ? 'selected' : '' }}>
                                                        {{ $project->project_name }}</option>
                                                        {{-- @if ($project->id == $leads->project_id)
                                                            <option value="{{ $project->id }}" selected>
                                                                {{ $project->project_name }}</option>
                                                        @else
                                                            <option value="{{ $project->id }}">
                                                                {{ $project->project_name }}</option>
                                                        @endif --}}
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
                                                <input type="text" name="location_of_client" class="form-control"
                                                    value="{{ $leads->location_of_client }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Budget</label>
                                                <select name="budget" class="selectpicker" data-style="btn-light"
                                                    id="budget" placeholder="Select Lead Status"> 
                                                     <option value="" selected>Select</option>  
                                                    @foreach ($Budgets as $Budget)
                                                     
                                                        @if ($Budget->budget === $leads->budget)
                                                        
                                                            <Option name="buget" value="{{ $Budget->budget }}"
                                                                selected> {{ $Budget->budget }}</Option>
                                                        @else
                                                       
                                                            <Option name="buget" value="{{ $Budget->budget }}">
                                                                {{ $Budget->budget }}</Option>
                                                        @endif
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
							<option value="" selected>Select</option>
                                                    <option value="Investment"
                                                        @if ($leads->investment_or_end_user == 'Investment') selected
                                                    @else @endif>
                                                        Investment</option>
                                                    <option value="End User"
                                                        @if ($leads->investment_or_end_user == 'End User') selected
                                                    @else @endif>
                                                        End User</option>
                                                    <option value="Both"
                                                        @if ($leads->investment_or_end_user == 'Both') selected @endif>
                                                        Both
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 ">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Regular Investor</label>
                                                <select class="selectpicker" data-style="btn-light"
                                                    name="regular_investor"
                                                    id="regular_investor">
                                                    <option value="NO" selected>
                                                        {{ 'NO' }}
                                                    </option>
                                                    <option value="YES"
                                                        {{ $leads->regular_investor == 'YES' ? 'selected' : '' }}>
                                                        {{ 'YES' }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div> 

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_number">Relationship Contact Number 
                                                    <a href="https://api.whatsapp.com/send/?phone={{ $relationsNumber }}" target="_blank" id="relationWhatsAppLink2">
                                                        <i class="mdi mdi-whatsapp">
                                                            <span id="RelationContactNumberWhatsapp"></span>
                                                        </i>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $leads->emergeny_contact_number }}" target="_blank" id="relationGoogleLinkNumber" class="m-1">
                                                        <i class="mdi mdi-google">
                                                            <span id="RelationContactNumberGoogle"></span>
                                                        </i>
                                                    </a>
                                                </label>

                                                <input type="tel" name="emergeny_contact_number[relationCC]" class="form-control"
                                                    id="RelationContactNumber" onkeyup="myFunction()"
                                                    data-initial-country="{{$RelationCountryCodeIso->iso ?? "In"}}"
                                                    value="{{ $leads->emergeny_contact_number }}"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    {{-- pattern="[1-9]{1}[0-9]{9}" --}}
                                                    >
                                            </div>
                                        </div>



                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">

                                                <label for="example-select">Relationship Contact Name</label>
                                                <input type="text" class="form-control" name="relationship"
                                                    id="relationshipName" value="{{ $leads->relationship }}">
                                                {{-- <select name="relationship" class="form-control" id="example-select">
                                                    <option>Select</option>
                                                    @foreach ($relations as $relation)
                                                        @if ($relation->name == $leads->relationship)
                                                            <option value="{{ $relation->name }}" selected>
                                                                {{ $relation->name }}</option>
                                                        @else
                                                            <option value="{{ $relation->name }}">
                                                                {{ $relation->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select> --}}
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>Relationship</label>
                                                <select name="relationshipName" class="form-control">
                                                    <option value="">Select</option>
                                                    @foreach ($relations as $relation)
                                                        @if ($relation->id == $leads->relationship_name)
                                                            <option value="{{ $relation->id }}" selected>
                                                                {{ $relation->name }}</option>
                                                        @else
                                                            <option value="{{ $relation->id }}">
                                                                {{ $relation->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Booking Date <span class="text-danger" id="RestrictBookingDate " style="display: none;">*</span></label>
                                                <input type="date" name="booking_date" class="form-control"
                                                    value="{{ $leads->booking_Date }}">
                                                @error('booking_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            @php
                                                $selected = explode(',', $leads->booking_project);
                                            @endphp

                                            <div class="form-group mb-3">
                                                <label for="example-select">Booking Project <span class="text-danger" id="BookingProject" style="display: none;">*</span></label>
                                                <select name="booking_project[]" class="selectpicker"
                                                    data-style="btn-light" multiple id="booking_project"> 
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}"
                                                            {{ in_array($project->id, $selected) ? 'selected' : '' }}>
                                                            {{ $project->project_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('booking_project')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3"> 
                                                <label for="simpleinput">Booking Amount 
                                                    <span class="text-danger" id="restrict" style="display: none;">*</span>
                                                </label>
                                                <input type="text" name="booking_amount" class="form-control"
                                                    value="{{ $leads->booking_amount }}">
                                                @error('booking_amount')
                                                    <small class="text-danger">{{ $message }}</small>
                                                 @enderror
                                            </div>
                                        </div>



                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Reference Name</label>
                                                <input type="text" name="reference" class="form-control"
                                                    value="{{ $leads->reference }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Reference Contact Number</label>
                                                <input type="text" name="reference_contact_number"
                                                    class="form-control" value="{{ $leads->reference_contact_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}">
                                            </div>
                                        </div>

                                        @php
                                        $date = \Carbon\Carbon::parse()->format('d-M-Y');
                                        
                                        $dob = Carbon\Carbon::now()->format('Y-m-d');
                                        
                                        $weddingAnniversary = Carbon\Carbon::now()->format('Y-m-d'); 

                                        $NextFollowTodayFeature = Carbon\Carbon::now()->format('Y-m-d\TH:i:s'); 
                                        $nextFollow = Carbon\Carbon::now()->addDays(2); 
                                     @endphp

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Date of Birth</label>
                                                <input type="date"
                                                max="{{ $dob }}"
                                                 name="dob" class="form-control"
                                                    value="{{ $leads->dob }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Wedding Anniversary</label>
                                                <input type="date" name="wedding_anniversary" class="form-control"
                                                max="{{ $weddingAnniversary }}" 
                                                    value="{{ $leads->wedding_anniversary }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Next Follow Up Date</label>
                                                <input type="datetime-local" id="next_follow" name="next_follow_up_date_lead"
                                                {{-- min="{{$NextFollowTodayFeature}}" --}}
                                                step="any"  
                                                value="@php  echo date('Y-m-d\TH:i:s',strtotime($leads->next_follow_up_date)) @endphp" 
                                                  class="form-control">
                                            </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-4">
                                         @php
                                                $selected = explode(',', $leads->existing_property);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select">
                                                    <strong>Existing Property</strong></label>
                                                    {{-- <input type="text" class="form-control" name="existing_property"
                                                id="existing_property" value="{{ $leads->existing_property }}"> --}}
                                                <select name="existing_property[]" class="selectpicker" data-style="btn-light"
                                                id="existing_property" multiple>
                                                <option>Select Project</option>
                                                @foreach ($projects as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ in_array($project->id, $selected) ? 'selected' : '' }}>
                                                    {{ $project->project_name }}</option>
                                                    {{-- @if ($project->id == $leads->project_id)
                                                        <option value="{{ $project->id }}" selected>
                                                            {{ $project->project_name }}</option>
                                                    @else
                                                        <option value="{{ $project->id }}">
                                                            {{ $project->project_name }}</option>
                                                    @endif --}}
                                                @endforeach
                                            </select>

                                                {{-- @error('customer_requirement')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror --}}
                                            </div>
                                        </div>

                                    </div>
 


                                    <div class="row">
                                         {{-- <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Notes</strong> <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="customer_interaction" id="exampleFormControlTextarea1" rows="4">{{ $leads->customer_interaction }}</textarea>
                                                @error('customer_interaction')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Customer Profile</label>
                                                <textarea class="form-control" name="about_customer" id="about_customer" rows="4">{{ $leads->about_customer }}</textarea>
                                            </div>
                                        </div>


                                        {{-- <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="checkbox-2" name="common_pool_status" type="checkbox"
                                                value="1" {{ $leads->common_pool_status == 1 ? 'checked' : '' }}>
                                            <label for="checkbox-2">
                                                Move to Common Pool
                                            </label>
                                        </div> --}}

                                        <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="checkbox-3" name="is_featured" type="checkbox" value="1"
                                                {{ $leads->is_featured == 1 ? 'checked' : '' }}>
                                            <label for="checkbox-3">
                                                Featured Lead
                                            </label>
                                        </div>

                                        <div
                                            class="checkbox checkbox-danger checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="checkbox-4" name="is_dnd" type="checkbox" value="1"
                                                {{ $leads->dnd == 1 ? 'checked' : '' }}>
                                            <label for="checkbox-4">
                                                DND
                                            </label>
                                        </div>

                                         
                                    </div>

                                        <input type="hidden" name="lead_ids" id="lead_ids" value="{{ $leads->id }}">
                                        <button name="submit" value="submit" type="submit"
                                        class="btn btn-primary waves-effect waves-light mt-3" id="updateLead">
                                        Update Lead
                                        </button> 
                                        
                                 
                                    

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
<script src="https://cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script> 
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
         
         /* .iti--allow-dropdown{
            width: 0px!important;
        }   */
    </style>
    <script>

        
 
        // Dropdown Search Value
        $('#buying_location').select2({
            selectOnClose: true
        });
        $('#property_requirement').select2({
            selectOnClose: true
        });
        
        $('#source').select2({
            selectOnClose: true
        });
        $('#assign_employee_id').select2({
            selectOnClose: true
        });
        $('#customer_requirement').select2({
            placeholder: 'Select',

        });
         $('#existing_property').select2({
             placeholder: "Select",
              selectOnClose: true
        });
        $('#lead_status').select2({
            // selectOnClose: true
        });
        $('#project').select2({
            selectOnClose: true
        });
        $('#budget').select2({
           // placeholder: 'Select',
            // selectOnClose: true
        });
        $('#booking_project').select2({
            placeholder: 'Select',
            // selectOnClose: true  
        });

        $('#lead_type').select2({ 
            minimumResultsForSearch: Infinity
        });
        $('#number_of_units').select2({
            // placeholder: 'Select', 
            minimumResultsForSearch: Infinity
        });
        $('#investment_or_end_user').select2({
            placeholder: 'Investment or End User', 
            //minimumResultsForSearch: Infinity
        });
        $('#regular_investor').select2({
            // placeholder: 'Investment or End User', 
            minimumResultsForSearch: Infinity
        });
        // Dropdown Search Value End


           document.getElementById('lead_status').onchange = function() {
              if (this.value == '8'||this.value == '9' || this.value == '10' || this.value == '11' || this.value == '12') { 
                var nextFollowUp = document.getElementById('next_follow');
                nextFollowUp.disabled = true;
              } else {
                var nextFollowUp = document.getElementById('next_follow');
                nextFollowUp.disabled = false; 
              }
          }


         // document.getElementById('lead_status').onchange = function() {
        //     if (this.value == '12') {
        //         document.getElementById("restrict").style.display = "inline"; 
        //         document.getElementById("RestrictBookingDate").style.display = "inline"; 
        //         document.getElementById("BookingProject").style.display = "inline"; 
        //     } else {
        //         document.getElementById("restrict").style.display = "none";
        //         document.getElementById("RestrictBookingDate").style.display = "none";
        //         document.getElementById("BookingProject").style.display = "none";
        //     }
        // } 

        

        function yesnoCheck(that) {
            // alert("Hello");
            if (that.value == "4" || that.value == "5") {
                document.getElementById("rentBudget").style.display = "block";
            } else {
                document.getElementById("rentBudget").style.display = "none";
            }


        };

        function myFunction(search_value) {
            var x = document.getElementById("RelationContactNumber");
            // alert(x.value.length);
            var y = document.getElementById("relationshipName");
            if (x.value.length > 3) {
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


        //   var input = document.querySelector("#number"),
        //       errorMsg = document.querySelector("#error-msg"),
        //       validMsg = document.querySelector("#valid-msg");
        //     //here, the index maps to the error code returned from getValidationError - see readme
        //   var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

        //    // initialise plugin
        //   var iti = window.intlTelInput(input, {
        //       initialCountry: "In",
        //       separateDialCode: true,

        //   });



        // var reset = function() {
        //     input.classList.remove("error");
        //     errorMsg.innerHTML = "";
        //     errorMsg.style.display = "none";
        //     validMsg.style.display = "none";
        // };

        // // on blur: validate
        // input.addEventListener('blur', function() {
        //     reset();
        //     if (input.value.trim()) {
        //         if (iti.isValidNumber()) {
        //             validMsg.style.display = "block";

        //         } else {
        //             input.classList.add("error");
        //             var errorCode = iti.getValidationError();
        //             errorMsg.innerHTML = errorMap[errorCode];
        //             errorMsg.style.display = "block";
        //         }
        //     }
        // });

        // // on keyup / change flag: reset
        // input.addEventListener('change', reset);
        // input.addEventListener('keyup', reset);


       
 


        //set a drop down list in a select field based on select before on laravel

        $(document).ready(function() {
            $('select[name="assign_employee_id"]').on('change', function() {
                var NameOfDeveloper = $(this).val();
                // alert(categoryID);
                if (NameOfDeveloper) {
                    $.ajax({
                        url: "{{ url('/search-by-employee-assign-location/') }}/" +
                            NameOfDeveloper,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            if (data == "error") {
                                $('select[name="buying_location"]').html(
                                    '<option value="">Selected Name of Developer has No Project Assign</option>'
                                ).attr("disabled", true);
                            } else {

                                $('select[name="buying_location"]').html(
                                        '<option value="">Select Buying Location</option>')
                                    .attr(
                                        "disabled", false);;
                                $.each(data, function(key, value) {
                                    $('select[name="buying_location"]').append(
                                        '<option value="' + value.id +
                                        '" class="selectpicker">' + value
                                        .location + '</option>');

                                });
                            }
                        }
                    });
                } else {
                    $('select[name="buying_location"]').html(
                            '<option value="">First Select Name of Developer</option>')
                        .attr("disabled", true);
                }
            });
        });

       // Contact number is number exist;
        var i = 0;
        function existContantNumber() {
           
            var x = document.getElementById("number");
            var y = document.getElementById("numberDetails");
            var z = document.getElementById("lead_ids");
            
            if (x.value.length > 3) {
                $.ajax({
                    url: '/SearchByContactEdit/'+ z.value + '/' + x.value,
                    method: 'get'
                }).done(function(response) {
                     
                    if (response  == "") {  
                        var ButtonID = document.getElementById("updateLead");
                             ButtonID.style.display = "block";
                    } else {
                        
                     
                    if (response['lead_name'] == undefined) {
                         
                      
                        y.value = "";
                            document.getElementById("numberDetails").style.display = "none";
                             document.getElementById("myID").style.display = "none";
                             var mydiv = document.getElementById("myID");
                             var aTag = document.createElement('a');
                             var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                             var atagLength = aTag.innerHTML = "";
                         
                    } else {
   
                        if (i == 0) {   
                            document.getElementById("numberDetails").style.display = "block";
                            var mydiv = document.getElementById("myID");
                            var aTag = document.createElement('a');
                            // var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']); 
                            var hasTag = aTag.setAttribute('href', "https://crm.homents.in/lead-status/" + response['id']); 
                            var atagLength = aTag.innerHTML = "View Details"; 
                             myID.appendChild(aTag);
                             ++i; 

                            
                          
                              
                        } else {
                             document.getElementById("numberDetails").style.display = "block";
                             document.getElementById("myID").style.display = "block";
                             var mydiv = document.getElementById("myID");
                             var aTag = document.createElement('a');
                             var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                             var atagLength = aTag.innerHTML = ""; 
                         
                        }

                        document.getElementById("numberDetails").style.display = "block";
                             document.getElementById("myID").style.display = "block";
                             var mydiv = document.getElementById("myID");
                             var aTag = document.createElement('a');
                             var hasTag = aTag.setAttribute('href', "lead-status/" + response['id']);
                             var atagLength = aTag.innerHTML = ""; 

                        var ButtonID = document.getElementById("updateLead");
                             ButtonID.style.display = "none";
                      
                    }

                }

                }); 
            }
        }
 
         // today extand 2 Days 
        $('#training_date').change(function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd; 
            var nextFollowUpDate = document.getElementById("next_follow");
            var startDate = document.getElementById("training_date");
            var setValue1 = new Date(startDate.value);
            var AddDateDays = setValue1.setDate(setValue1.getDate() + 2);
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
            if (dateFormate1 >= today) { 
                var setValue = new Date(startDate.value);
                var AddDateDays = setValue.setDate(setValue.getDate() + 2);
                var nextFollowDate = new Date(setValue);
                var month = nextFollowDate.getMonth() + 1;
                if (month.toString().length === 1) {
                    var isMontsSingle = '0' + month;
                } else {
                    var isMontsSingle = month;
                }
                var day = ("0" + nextFollowDate.getDate()).slice(-2);
                var year = nextFollowDate.getFullYear();
                // var hours = nextFollowDate.getHours();
                // var minuts = nextFollowDate.getMinutes();
                // var seconds = nextFollowDate.getSeconds();
                var hours = '11';
                var minuts = '00';
                var seconds = '00';
                var dateFormate = [year, isMontsSingle, day].join('-');
                var timeFormate = [hours, minuts, seconds].join(':');
                var dateTime = [dateFormate, timeFormate].join('T');
                document.getElementById("next_follow").value = dateTime;
            } else {
                document.getElementById("next_follow");
            }

        });
        // today extand 2 Days  end

         
    </script>
    
 
    <script>
         //alt_contact_number_2

         var input2 = document.querySelector("#alt_contact_number_2"),
            errorMsg2 = document.querySelector("#error-msg-2"),
            validMsg2 = document.querySelector("#valid-msg-2");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        var initialCountry2 = input2.getAttribute('data-initial-country'); 
        var iti2 = window.intlTelInput(document.querySelector("#alt_contact_number_2"), {
            initialCountry: initialCountry2,
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
    </script>


<script>
     // initialise plugin
     var RelationInput = document.querySelector("#RelationContactNumber");
     var RelationinitialCountryCode = RelationInput.getAttribute('data-initial-country');
     var RelationInput2 = window.intlTelInput(document.querySelector("#RelationContactNumber"), {
            initialCountry: RelationinitialCountryCode,
            separateDialCode: true,
            hiddenInput: "relationCCode",
        });

        $("form").submit(function() {
            var full_numberR = RelationInput2.getNumber(intlTelInputUtils.numberFormat.E164);
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
                if (RelationInput2.isValidNumber()) {
                    validMsg1.style.display = "block";
                } else {
                    input1.classList.add("error");
                    var errorCode = RelationInput2.getValidationError();
                    errorMsg1.innerHTML = errorMap[errorCode];
                    errorMsg1.style.display = "block";
                }
            }
        });

        // on keyup / change flag: reset
        input1.addEventListener('change', reset);
        input1.addEventListener('keyup', reset);

        // relation_contact_number end
</script>
  
 
<script>
      var Maininput1 = document.querySelector("#number"),
            MainerrorMsg1 = document.querySelector("#error-msg-1"),
            MainvalidMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];
 
        var initialCountryCodeMain = Maininput1.getAttribute('data-initial-country');
        // country_code_hidden
        var Mainiti1 = window.intlTelInput(document.querySelector("#number"), {
            initialCountry: initialCountryCodeMain,
            separateDialCode: true,
            hiddenInput: "full",
        });

        $("form").submit(function() {
            var Main_full_number1 = Mainiti1.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='contact_number[full]'").val(Main_full_number1);
        });

        var reset = function() {
            Maininput1.classList.remove("error");
            MainerrorMsg1.innerHTML = "";
            MainerrorMsg1.style.display = "none";
            MainvalidMsg1.style.display = "none";
        };

        // on blur: validate
        Maininput1.addEventListener('blur', function() {
            reset();
            if (Maininput1.value.trim()) {
                if (Mainiti1.isValidNumber()) {
                    MainvalidMsg1.style.display = "block";
                } else {
                    Maininput1.classList.add("error");
                    var errorCode = Mainiti1.getValidationError();
                    MainerrorMsg1.innerHTML = errorMap[errorCode];
                    MainerrorMsg1.style.display = "block";
                }
            }
        });

        // on keyup / change flag: reset
        Maininput1.addEventListener('change', reset);
        Maininput1.addEventListener('keyup', reset);

        // alt_contact_number end
</script>
<script>
            var altInput = document.querySelector("#alt_contact_number"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

            // Index maps to the error code returned from getValidationError
            var errorMap = [
                "Invalid number, enter correct number.",
                "Invalid country code",
                "Enter correct number.",
                "Too long",
                "Invalid number, enter correct number."
            ];

            var altCountryCode = altInput.getAttribute('data-initial-country');
            
            var iti1 = window.intlTelInput(altInput, {
                initialCountry: altCountryCode,
                separateDialCode: true,
                hiddenInput: "altfull"
            });

            $("form").submit(function() {
                var fullNumber1 = iti1.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='alt_contact_number[altfull]'").val(fullNumber1);
            });

            var reset = function() {
                altInput.classList.remove("error");
                errorMsg1.innerHTML = "";
                errorMsg1.style.display = "none";
                validMsg1.style.display = "none";
            };

            // on blur: validate
            altInput.addEventListener('blur', function() {
                reset();
                if (altInput.value.trim()) {
                    if (iti1.isValidNumber()) {
                        validMsg1.style.display = "block";
                    } else {
                        altInput.classList.add("error");
                        var errorCode = iti1.getValidationError();
                        errorMsg1.innerHTML = errorMap[errorCode];
                        errorMsg1.style.display = "block";
                    }
                }
            });

            // on keyup / change flag: reset
            altInput.addEventListener('change', reset);
            altInput.addEventListener('keyup', reset);


        // alt_contact_number end
</script>
<script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script>  
    <script>  
    bkLib.onDomLoaded(function() {
       var test =  new nicEditor().panelInstance('about_customer');
       
    });
</script>

        <script>
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

        function updateAltContactSearchLink2() {
        var searchInput = document.getElementById("alt_contact_name_2");
        var googleLinkDiv = document.getElementById("altContactLink2");
        var googleLink = googleLinkDiv.querySelector("a");
        var icon = googleLink.querySelector("i");

        var inputValue = searchInput.value.trim();

        if (inputValue.length > 0) {
            // googleLinkDiv.style.display = "block";
            googleLink.href = "https://www.google.com/search?q=" + inputValue;
            icon.style.display = "inline"; // Show the icon
        } else {
            googleLinkDiv.style.display = "none";
        }
        }

        function updateAltContactSearchLink() {
        var searchInput = document.getElementById("alt_contact_name"); 
        var googleLinkDiv = document.getElementById("altContactLink");
        var googleLink = googleLinkDiv.querySelector("a");
        var icon = googleLink.querySelector("i");

        var inputValue = searchInput.value.trim();
        // alert(inputValue);

        if (inputValue.length > 0) {
            // googleLinkDiv.style.display = "block";
            googleLink.href = "https://www.google.com/search?q=" + inputValue;
            // icon.style.display = "inline"; // Show the icon
        } else {
            googleLinkDiv.style.display = "none";
        }
        }
</script>
<script>
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
                    // whatsappValue.textContent = "";
                    // googleValue.textContent = "";
                    whatsappLink.href = "javascript:void(0)";
                    googleLink.href = "javascript:void(0)";
                }
            });
</script>
<script>
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
</script>

<script>
    
    var relationX = document.getElementById("RelationContactNumber"); // Replace with your actual input field for Relationship Contact Number

relationX.addEventListener("input", function() {
    var relationInputValue = relationX.value;

    var relationWhatsAppLink = document.getElementById("relationWhatsAppLink2");
    var relationGoogleLink = document.getElementById("relationGoogleLinkNumber");
    var relationContactNumberWhatsapp = document.getElementById("RelationContactNumberWhatsapp");
    var relationContactNumberGoogle = document.getElementById("RelationContactNumberGoogle");

    if (relationInputValue.length === 10) {
        // Set Relationship WhatsApp and Google links
        relationWhatsAppLink.href = "https://api.whatsapp.com/send/?phone=91" + relationInputValue;
        relationGoogleLink.href = "https://www.google.com/search?q=" + relationInputValue;
        
        // Set the content for WhatsApp and Google icons
        // relationContactNumberWhatsapp.textContent = relationInputValue;
        // relationContactNumberGoogle.textContent = relationInputValue;
    } else {
        // Clear the links and content
        relationWhatsAppLink.href = "javascript:void(0)";
        relationGoogleLink.href = "javascript:void(0)";
        relationContactNumberWhatsapp.textContent = "";
        relationContactNumberGoogle.textContent = "";
    }
});


 function updateGoogleSearchLink() {
    var searchInput = document.getElementById("lead_name");
    var googleLinkDiv = document.getElementById("googleLinkName");
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
</script>
@endsection


