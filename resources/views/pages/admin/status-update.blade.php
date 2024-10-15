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
                            <li class="breadcrumb-item active">Update-Leads-Status</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Update Leads Status -> {{ $Leads->lead_name }}</h4>
                </div>

                @if (session()->has('NoSearch'))
                    <div class="alert alert-danger text-center" id="HideMailNotification">
                        {{ session()->get('NoSearch') }} </div>
                @endif
            </div>
        </div>
        <!-- end page title -->


        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12">
                                @php
                                    $isBack = false;
                                @endphp
                                <div class="d-flex justify-content-end">
                                    <a type="button" class="btn btn-danger waves-effect waves-light  "
                                        href="{{ url('lead-status/' . encrypt($Leads->id)) }}">Back</a>

                                    {{-- <a type="button" class="btn btn-danger waves-effect waves-light  "
                                        href="{{ url('employee-leads/' . $emp_id->id . '/' . $location_id->id) }}" >Back</a> --}}
                                </div>
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif

                                <form method="POST" action="{{ url('status-update/' . $Leads->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    {{-- <input type="text" name="lead_id" id="lead_id"> --}}

                                    @php
                                        $nextFollowUpDate = Carbon\Carbon::now()->addDays(1);
                                        $MainNumber = preg_replace('/\s+/', '', str_replace('+', '', $Leads->country_code)) . $Leads->contact_number;
                                        $altNumber = preg_replace('/\s+/', '', str_replace('+', '', $Leads->alit_country_code)) . $Leads->alt_no_Whatsapp;
                                        $altNumber2 = preg_replace('/\s+/', '', str_replace('+', '', $Leads->alt_country_code1)) . $Leads->alt_no_Whatsapp_2;
                                        $relationsNumber = preg_replace('/\s+/', '', str_replace('+', '', $Leads->relations_country_code)) . $Leads->emergeny_contact_number;

                                        $updateSutatus = DB::table('lead_status_histories')
                                            ->where('lead_id', $Leads->id)
                                            ->orderBy('id', 'DESC')
                                            ->first();

                                        $employees = DB::table('employees')
                                            ->where('organisation_leave', 0)
                                            ->orderBy('employee_name', 'asc')
                                            ->get();
                                        // $locations = DB::table('locations')->get();
                                        $SearchByEmployeeAssignLocation = DB::table('employees')
                                            ->where('id', $Leads->assign_employee_id)
                                            ->first();

                                        $select = explode(',', $SearchByEmployeeAssignLocation->employee_location);

                                        $locations = DB::table('locations')
                                            ->whereIn('id', $select)
                                            ->get();
                                        $selectedID = explode(',', $Leads->project_id);

                                        $selecteds = explode(',', $Leads->existing_property);
                                        $cleanedCountryCode = str_replace(' ', '', $Leads->alit_country_code);
                                     

                                    @endphp
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Status Date</label>
                                                <input type="hidden" name="status_date" value="{{ $Leads->date }}">
                                                <input type="datetime-local" id="date" step="any"
                                                    name="status_date" class="form-control"
                                                    @if (old('status_date')) value="{{ old('status_date') }}"
                                                    @else
                                                    value="@php  echo date('Y-m-d\TH:i:s') @endphp" @endif
                                                    @if ($Leads->lead_status == '14') disabled @endif>

                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="lead_ids" id="lead_ids" value="{{ $Leads->id }}">
                                        <div class="col-md-4 wrapper1">
                                            <input type="hidden" value="{{ $Leads->id }}" name="leadId" id="leads_Id">
                                             
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="iSwhatsapp" style="display: flex;">
                                                    <strong>Contact Number</strong> <span class="text-danger">*</span> 
                                                    <a href="https://api.whatsapp.com/send/?phone={{ $MainNumber }}" target="_blank" id="whatsappLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="whatsappValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $Leads->contact_number }}" target="_blank" id="googleLink" class="ml-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="googleValue"></span>
                                                    </a>
                                                </label>
                                                

                                                <input type="tel" id="number" name="contact_number[main]"
                                                    value="{{ $Leads->contact_number }}" class="form-control"
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
                                                            <a href="{{ url('lead-status/' . encrypt($Leads->id)) }}">
                                                                View Detail {{ $Leads->id }}
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
                                                        <a href="https://www.google.com/search?q={{ $Leads->lead_name }}" target="_blank" >
                                                                <i class="mdi mdi-google" ></i>
                                                            <span id="CustomerNameGoogle" ></span>
                                                        </a>
                                                        </label> 
                                                <input type="text" name="lead_name" class="form-control"
                                                    value="{{ $Leads->lead_name }}" onchange="requiredFiledValidation()"
                                                    id="lead_name"
                                                    onkeyup="updateGoogleSearchLink()">
                                                @error('lead_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Alt Contact Number 1
                                                    <a href="https://api.whatsapp.com/send/?phone={{ $altNumber }}"
                                                        target="_blank" id="altWhatsAppLink">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $Leads->alt_no_Whatsapp }}"
                                                        target="_blank" id="altGoogleLink" class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue"></span>
                                                    </a>
                                                </label>

                                                <input type="tel" id="alt_contact_number"
                                                    name="alt_contact_number[altmain]"
                                                    data-initial-country="{{ $countryCodeAltIso->iso ?? "In"  }}" 
                                                    value="{{ $Leads->alt_no_Whatsapp }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateWhatsAppLink()"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                <span id="valid-msg-1" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-1" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink">Alt Contact Name 1
                                                    <a href="https://www.google.com/search?q={{ $Leads->alt_contact_name }}"
                                                        target="_blank">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </label>
                                                <input type="text" id="alt_contact_name" name="alt_contact_name"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}} value="{{ $Leads->alt_contact_name }}"
                                                    class="form-control" onkeyup="updateAltContactSearchLink()"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactNumber2WhatsAppLink">Alt Contact Number 2
                                                    <a href="https://api.whatsapp.com/send/?phone={{ $altNumber2 }}"
                                                        target="_blank" id="altWhatsAppLink2">
                                                        <i class="mdi mdi-whatsapp"></i>
                                                        <span id="altWhatsAppValue2"></span>
                                                    </a>
                                                    <a href="https://www.google.com/search?q={{ $Leads->alt_no_Whatsapp_2 }}"
                                                        target="_blank" id="altGoogleLink2" class="m-1">
                                                        <i class="mdi mdi-google"></i>
                                                        <span id="altGoogleValue2"></span>
                                                    </a>
                                                </label>
                                                <input type="tel" name="alt_contact_number_2[altmain2]"
                                                    id="alt_contact_number_2" value="{{ $Leads->alt_no_Whatsapp_2 }}"
                                                    data-initial-country="{{ $countryCodeAlt2Iso->iso ?? "In" }}"
                                                    class="form-control"
                                                    oninput="this.value = this.value.replace(/^(\+91|0)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    onkeyup="updateAltContactNumber2WhatsAppLink()"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                <span id="valid-msg-2" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-2" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>




                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput" id="altContactLink2">Alt Contact Name 2
                                                    <a href="https://www.google.com/search?q={{ $Leads->alt_contact_name_2 }}"
                                                        target="_blank">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>

                                                </label>
                                                <input type="text" id="alt_contact_name_2" name="alt_contact_name_2"
                                                    {{-- onkeydown="return /[a-z /]/i.test(event.key)" --}} value="{{ $Leads->alt_contact_name_2 }}"
                                                    class="form-control" onkeyup="updateAltContactSearchLink2()"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Lead Status</label>
                                                <input type="hidden" name="lead_status"
                                                    value="{{ $Leads->lead_status }}">
                                                <select name="lead_status" class="selectpicker" data-style="btn-light"
                                                    id="lead_status" onchange="IfBookingConfirmed();" selected
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    @foreach ($LeadStatus as $LeadStatusUpdate)
                                                        @if ($LeadStatusUpdate->id == $Leads->lead_status)
                                                            <option value="{{ $LeadStatusUpdate->id }}" selected>
                                                                {{ $LeadStatusUpdate->name }}</option>
                                                        @else
                                                            <option value="{{ $LeadStatusUpdate->id }}">
                                                                {{ $LeadStatusUpdate->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    @if ($Leads->lead_status == '14')
                                        <div class="row" id="isBookinigConfirm">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="simpleinput">Booking Date
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                    {{-- <input type="datetime-local" id="booking_date" name="booking_date"
                                                        value="{{ $Leads->booking_Date }}" class="form-control"> --}}


                                                    <input type="date" id="booking_date" step="any"
                                                        name="booking_date" class="form-control"
                                                        @if (old('booking_date')) value="{{ old('booking_date') }}"
                                                        @else
                                                        value="{{ $Leads->booking_Date }}" @endif
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                    @error('booking_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="example-select">Booking Project</label>
                                                    <select name="booking_project[]" class="selectpicker"
                                                        data-style="btn-light" multiple data-live-search="true"
                                                        id="booking_project"
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                        @foreach ($projectLists as $project)
                                                            <option value="{{ $project->id }}"
                                                                @if ($project->id == $Leads->project_id) selected @endif>
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
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                    <input type="text" id="booking_amount" name="booking_amount"
                                                        class="form-control" value="{{ $Leads->booking_amount }}"
                                                        @if ($Leads->lead_status == '14') disabled @endif>

                                                    @error('booking_amount')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row" style="display:none" id="isBookinigConfirm">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="simpleinput">Booking Date
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                    <input type="date" id="booking_date" name="booking_date"
                                                        value="{{ old('booking_date') }}" class="form-control"
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                    @error('booking_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="example-select">Booking Project
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                    <select name="booking_project[]" class="selectpicker"
                                                        data-style="btn-light" multiple data-live-search="true"
                                                        id="booking_project"
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                        {{-- <option>Select Project</option> --}}
                                                        @foreach ($projectLists as $project)
                                                            <option value="{{ $project->id }}"
                                                                {{ old('booking_project') == $project->id ? 'selected' : '' }}>
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
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                    <input type="text" id="booking_amount" name="booking_amount"
                                                        class="form-control" value="{{ old('booking_amount') }}"
                                                        @if ($Leads->lead_status == '14') disabled @endif>

                                                    @error('booking_amount')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput"><strong>Customer Type</strong> <span
                                                        class="text-danger">*</span></label>
                                                <input type="hidden" name="buyer_seller"
                                                    value="{{ $Leads->buyer_seller }}">
                                                <select name="buyer_seller" class="selectpicker" data-style="btn-light"
                                                    id="property_requirement" onchange="yesnoCheck(this);"
                                                    placeholder="Select Lead Status" selected
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    {{-- <option value="" selected>Select</option> --}}
                                                    @foreach ($buyerSellers as $buyerSeller)
                                                        @if ($buyerSeller->id == $Leads->buyer_seller)
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
                                                <select name="customer_requirement[]" class="selectpicker"
                                                    data-style="btn-light" multiple id="customer_requirement"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    @php
                                                        $selected = explode(',', $Leads->project_type);
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
                                                <input type="hidden" name="assign_employee_id"
                                                    value="{{ $Leads->assign_employee_id }}"
                                                    @if ($Leads->lead_status != '14') disabled @endif>
                                                <select name="assign_employee_id" class="selectpicker"
                                                    data-style="btn-light" id="assign_employee_id"
                                                    onchange="AssignLocation(this);"
                                                    @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="" selected>Select</option>
                                                    @foreach ($employees as $employee)
                                                        @if ($employee->id == $Leads->assign_employee_id)
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

                                        @php
                                            $indivisulEmpLocation = DB::table('employees')
                                                ->where('user_id', Auth::user()->id)
                                                ->first();

                                            $emplocation = explode(',', $indivisulEmpLocation->employee_location);

                                        @endphp


                                        <div class="col-md-4">
                                            <div class="form-group mb-3" id="buying_location_hide"
                                                @if (old('buying_location'))  @endif>
                                                <label for="example-select"> Location <span
                                                        class="text-danger">*</span></label>
                                                <input type="hidden" name="buying_location"
                                                    value="{{ $Leads->location_of_leads }}"
                                                    @if ($Leads->lead_status != '14') disabled @endif>
                                                <select name="buying_location" class="selectpicker"
                                                    data-style="btn-light" id="buying_location"
                                                    @if (Auth::user()->roles_id == 10) disabled @endif>
                                                    <option value="" selected>Select Location</option>
                                                    @foreach ($locations as $location)
                                                        @if (in_array($location->id, $select))
                                                            @if ($location->id == $Leads->location_of_leads)
                                                                <option value="{{ $location->id }}" selected>
                                                                    {{ $location->location }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $location->id }}">
                                                                    {{ $location->location }}
                                                                </option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('buying_location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="example-select"><strong>Lead Type</strong> <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_type" class="selectpicker" data-style="btn-light"
                                                    id="lead_type" onchange="requiredFiledValidation()"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
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
                                                    placeholder="Select Lead Status" selected
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    @foreach ($number_of_units as $number_of_unit)
                                                        <option value="{{ $number_of_unit->number_of_units }}"
                                                            {{ collect(old('number_of_units'))->contains($number_of_unit->number_of_units . ' unit') ? 'selected' : '' }}
                                                            {{ old('number_of_units') == $number_of_unit->number_of_units . ' unit' ? 'selected' : '' }}>
                                                            {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4"> 
                                            <div class="form-group mb-3">
                                                <label for="example-select">Project Discussed/Visited </label>
                                                <input type="hidden" name="project_name[]"
                                                    value="{{ $Leads->project_id }}"
                                                    @if ($Leads->lead_status != '14') disabled @endif>
                                                <select name="project_name[]" class="selectpicker" data-style="btn-light"
                                                    style="height:20px!important" id="project_name" multiple
                                                    data-live-search="true"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    @foreach ($projectLists as $projectList)
                                                        @if (in_array($projectList->id, $selectedID))
                                                            <option value="{{ $projectList->id }}" selected>
                                                                {{ $projectList->project_name }}</option>
                                                        @else
                                                            <option value="{{ $projectList->id }}">
                                                                {{ $projectList->project_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select> 
                                                @error('project_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div> 
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Location of Customer</label>
                                                <input type="text" name="location_of_customer" class="form-control"
                                                    value="{{ $Leads->location_of_client }}"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Budget for Property</label>
                                                <select name="budget" class="selectpicker" data-style="btn-light"
                                                    id="budget" placeholder="Select Lead Status">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($Budgets as $Budget)
                                                        @if ($Budget->budget === $Leads->budget)
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


                                        <div class="col-md-4" id="isConfirmHide"
                                            @if ($Leads->lead_status == '14') style="display: none" @else style="display: block" @endif>

                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Next Follow Up Date</label>
                                                <input type="hidden" name="next_follow_up_date"
                                                    value="{{ $nextFollowUpDate }}">
                                                <input type="datetime-local" id="next_follow" name="next_follow_up_date"
                                                    step="any" value="{{ $nextFollowUpDate }}" class="form-control"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                {{-- min="{{ date('Y-m-d\TH:i:s', strtotime(Carbon\Carbon::today())) }}" --}}
                                            </div>
                                        </div> 

                                        <div class="col-md-4"> 
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Existing Property</strong> </label>

                                                <select class="selectpicker" data-style="btn-light"
                                                    name="existing_property[]" id="existing_property" multiple
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    {{-- <option value="" selected>Select Project</option> --}}
                                                    @foreach ($projectLists as $item)
                                                        {{-- <option value="{{ $item->id }}"
                                                        {{ collect(old('existing_property'))->contains($item->id) ? 'selected' : '' }}> 
                                                        {{ $item->project_name }}
                                                    </option> --}}
                                                        <option value="{{ $item->id }}"
                                                            {{ in_array($item->id, $selecteds) ? 'selected' : '' }}>
                                                            {{ $item->project_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>




                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Follow Up Buddy</strong>
                                                    <i class="fas fa-user-plus text-info" title="Follow Up Buddy"></i>
                                                </label>
                                                <select name="co_follow_up" class="selectpicker" data-style="btn-light"
                                                    id="co_follow_up" @if (Auth::user()->roles_id == 10) disabled @endif
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    <option value="" selected>Select</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->user_id }}"
                                                            @if ($employee->user_id == $Leads->co_follow_up) selected @endif>
                                                            {{ $employee->employee_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            @php
                                                $RWAE = DB::table('employees')
                                                    ->where('role_id', 10)
                                                    ->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select"><strong>Channel Partner</strong>
                                                    <i class="fa solid fa-handshake text-primary"
                                                        title="Channel Partner"></i>
                                                </label>
                                                <select class="selectpicker" data-style="btn-light" name="rwa"
                                                    id="rwa" @if ($Leads->lead_status == '14') disabled @endif>
                                                    <option value="">Select</option>
                                                    @foreach ($RWAE as $RWAEMP)
                                                        @if ($RWAEMP->user_id == $Leads->rwa)
                                                            <option value="{{ $RWAEMP->user_id }}" selected>
                                                                {{ $RWAEMP->employee_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $RWAEMP->user_id }}">
                                                                {{ $RWAEMP->employee_name }}
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="example-email"> Documents Types</label>
                                                <select name="documents" class="selectpicker" data-style="btn-light"
                                                    id="lead_documents"
                                                    @if ($Leads->lead_status == '14') disabled @endif>
                                                    <option value="">Select Documents</option>
                                                    <option value="1">Customer Reg. Copy</option>
                                                    <option value="2">Booking Docs</option>
                                                    <option value="3">Site Visit Pics & Docs</option>
                                                    <option value="4">BBA / ATS Docs</option>
                                                    <option value="5">Property Pictures</option>
                                                    <option value="6">KYC Buyer</option>
                                                    <option value="7">KYC Seller</option>
                                                    <option value="8">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contactNumber"> File Upload </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="file" name="file_uploads[]" multiple
                                                        class="form-control" accept=".pdf, .jpg, .jpeg, .png, .doc, .docx"
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12"> 
                                             <div class="form-group mb-3">
                                                <label for="simpleinput">Notes<span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="customer_interaction" name="customer_interaction"  rows="6" ></textarea> 
                                                @error('customer_interaction')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div> 
                                        </div>

                                        <div class="col-md-4">
                                            @if (
                                                $Leads->lead_status == 8 ||
                                                    $Leads->lead_status == 9 ||
                                                    $Leads->lead_status == 10 ||
                                                    $Leads->lead_status == 11 ||
                                                    $Leads->lead_status == 12 ||
                                                    $Leads->lead_status == 16)
                                            @else 
                                            @endif
                                            <div class="d-flex">
                                                <div
                                                    class="checkbox checkbox-success checkbox-circle  ml-2 mb-2 d-flex align-items-center">
                                                    <input id="checkbox-3" name="is_featured" type="checkbox"
                                                        value="1" {{ $Leads->is_featured == 1 ? 'checked' : '' }}
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                    <label for="checkbox-3">
                                                        Featured Lead
                                                    </label>
                                                </div>

                                                <div
                                                    class="checkbox checkbox-danger checkbox-circle  ml-2 mb-2 d-flex align-items-center">
                                                    <input id="checkbox-4" name="is_dnd" type="checkbox" value="1"
                                                        {{ $Leads->dnd == 1 ? 'checked' : '' }}
                                                        @if ($Leads->lead_status == '14') disabled @endif>
                                                    <label for="checkbox-4">
                                                        DND
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                            </div>

                            @php
                                // $bookings = DB::table('lead_status_histories')
                                //     ->join('leads', 'lead_status_histories.lead_id', '=', 'leads.id')
                                //     ->where('next_follow_up_date', '>', Carbon\Carbon::now()->toDateTimeString())
                                //     ->where('status_id', 4)
                                //     ->select('leads.lead_email','lead_status_histories.*')
                                //     ->get();

                                // echo $bookings;
                            @endphp

                            <div class="modal-footer d-flex">
                                <button name="submit" value="submit" type="submit"
                                    class="btn btn-primary waves-effect waves-light" id="formButton"
                                    onclick="AfterFormSubmitDisable()">Update Lead</button>
                                </form>

                                @if ($Leads->lead_status == 14)
                                    <form action="{{ url('/lead-reopne' . '/' . encrypt($Leads->id)) }}" method="post">
                                        @csrf
                                        <button class="btn btn-info waves-effect waves-light" value="reopen"
                                            name="reopen">Reopen</button>
                                    </form>
                                    <a href="{{ url('is-booking-cancelled/' . encrypt($Leads->id)) }}"
                                        class="btn btn-warning waves-effect waves-light">Cancel</a>
                                @endif

                            </div>



                        </div>
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
    <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script>  
    <script>
    bkLib.onDomLoaded(function() {
       var test =  new nicEditor().panelInstance('customer_interaction');
       
    });
    </script>
    <style>
        .bootstrap-select .dropdown-menu {
            max-height: 300px !important;
        }

        .bootstrap-select .dropdown-menu .inner {
            max-height: 300px !important;
            overflow-y: auto !important
        }

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
        
    </style>
    <script>
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
                                        '<option value="">Select Buying Location</option>')
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

        $('#project_name').select2({
            placeholder: "Select"
        });

        $('#lead_status').select2({
            placeholder: "Select"
        });
        $('#buying_location').select2({
            placeholder: "Select"
        });
        $('#assign_employee_id').select2({
            // placeholder: "Select"
        });

        $('#co_follow_up').select2({
            //placeholder: "Select"
            selectOnClose: true
        });
        customer_requirement
        $('#lead_documents').select2({
            //placeholder: "Select" 
        });

        $('#property_requirement').select2({
            //placeholder: "Select" 
        });

        $('#customer_requirement').select2({
            //placeholder: "Select" 
        });

        $('#booking_project').select2({
            //placeholder: "Select" 
        });

        $('#budget').select2({
            selectOnClose: true
        });


        $('#existing_property').select2({
            placeholder: "Select",
            // selectOnClose: true
        });

        $('#rwa').select2({
            // placeholder: "Select",
            selectOnClose: true
        });

        function IfBookingConfirmed() {
            var isConfirmed = document.getElementById("lead_status");

            var now = new Date();
            var year = now.getFullYear();
            var month = now.getMonth() + 1;
            var day = now.getDate();
            var hour = now.getHours();
            var minute = now.getMinutes();
            var second = now.getSeconds();
            if (month.toString().length == 1) {
                month = '0' + month;
            }
            if (day.toString().length == 1) {
                day = '0' + day;
            }
            if (hour.toString().length == 1) {
                hour = '0' + hour;
            }
            if (minute.toString().length == 1) {
                minute = '0' + minute;
            }
            if (second.toString().length == 1) {
                second = '0' + second;
            }

            if (isConfirmed.value == '8' || isConfirmed.value == '9' || isConfirmed.value == '10' || isConfirmed.value ==
                '11' || isConfirmed.value == '12') {
                var dateTime = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                var nextFollowUp = document.getElementById('next_follow');
                document.getElementById('next_follow').value = dateTime;
            } else if (isConfirmed.value == '4' || isConfirmed.value == '7') {
                var dateTime = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                var nextFollowDate = new Date(dateTime);
                var hoursTwoHRAdd = nextFollowDate.getHours() + 2;
                var dateTime1 = year + '-' + month + '-' + day + ' ' + hoursTwoHRAdd + ':' + minute + ':' + second;
                document.getElementById('next_follow').value = dateTime1;
            } else {
                var nextFollowUp = document.getElementById('next_follow');
                nextFollowUp.disabled = false;
            }
            if (isConfirmed.value == 14) {
                var dateTime = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                var nextFollowDate = new Date(dateTime);
                var hoursTwoHRAdd = nextFollowDate.getHours();
                var dateTime1 = year + '-' + month + '-' + day;

                document.getElementById('booking_date').value = dateTime1;

                document.getElementById("isBookinigConfirm").style = "block";
                document.getElementById("isConfirmHide").style.display = "none";
            } else {
                document.getElementById("isBookinigConfirm").style.display = "none";
                document.getElementById("isConfirmHide").style.display = "block";

            }
        };


        function AssignLocation(that) {
            // alert("Hello");
            if (that.value) {
                document.getElementById("buying_location_hide").style.display = "block";
            } else {
                document.getElementById("buying_location_hide").style.display = "none";
            }
        };



        {{-- function IfBookingConfirmed() {
                            var isConfirmed = document.getElementById("lead_status");
                            if (isConfirmed.value == '8' || isConfirmed.value == '9' || isConfirmed.value == '10' || isConfirmed.value ==
                                '11' || isConfirmed.value == '12') {
                                // alert("huu");
                                var nextFollowUp = document.getElementById('next_follow');
                                //nextFollowUp.disabled = true;
                            } else {
                                var nextFollowUp = document.getElementById('next_follow');
                                nextFollowUp.disabled = false;
                            }
                            if (isConfirmed.value == 14) {
                                document.getElementById("isBookinigConfirm").style = "block";
                            } else {
                                document.getElementById("isBookinigConfirm").style.display = "none";
                            }
                        }; --}}

        function yesnoCheck(that) {
            // alert("Hello");
            if (that.value == "4" || that.value == "5") {
                document.getElementById("rentBudget").style.display = "block";
            } else {
                document.getElementById("rentBudget").style.display = "none";
            }
        };
    </script>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
        var input1 = document.querySelector("#alt_contact_number");
        var errorMsg1 = document.querySelector("#error-msg-1");
        var validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin 
        var initialCountry = input1.getAttribute('data-initial-country');
        var iti1 = window.intlTelInput(input1, {
            initialCountry: initialCountry, // Set the initial country based on the data attribute
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
    });


        // alt_contact_number end


        //alt_contact_number_2

        var input2 = document.querySelector("#alt_contact_number_2"),
            errorMsg2 = document.querySelector("#error-msg-2"),
            validMsg2 = document.querySelector("#valid-msg-2");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        var initialCountry2 = input2.getAttribute('data-initial-country'); 
        var iti2 = window.intlTelInput(document.querySelector("#alt_contact_number_2"), {
            initialCountry: initialCountry2 ? initialCountry2 : "In",
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
    </script>

    <script>
        var altX = document.getElementById(
        "alt_contact_number"); // Replace with your actual input field for Alt Contact Number 1

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
        var altContact2Input = document.getElementById(
        "alt_contact_number_2"); // Replace with your actual input field for Alt Contact Number 2

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
                googleLinkDiv.style.display = "block";
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
                googleLinkDiv.style.display = "block";
                googleLink.href = "https://www.google.com/search?q=" + inputValue;
                icon.style.display = "inline"; // Show the icon
            } else {
                googleLinkDiv.style.display = "none";
            }
        }
        
        
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

    <script>

         // Country Code Section

         var input = document.querySelector("#number"),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        var initialCountryCodeMain = input.getAttribute('data-initial-country');
        var iti = window.intlTelInput(document.querySelector("#number"), {
            initialCountry: initialCountryCodeMain, 
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
                            var hasTag = aTag.setAttribute('href', "http://127.0.0.1:8000/lead-status/" + response['id']); 
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
    </script>


<script>
    function AfterFormSubmitDisable() {
            document.getElementById("formButton").style = 'pointer-events:none;'
            document.getElementById("formButton").innerHTML = 'Processing ...'
            document.getElementById("remove_processing").style=''
        }
</script>
     
@endsection

