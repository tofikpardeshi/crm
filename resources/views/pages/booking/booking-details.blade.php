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
                            <li class="breadcrumb-item active">Booking Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Booking Details</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
             @if (session()->has('NoSearch'))
					<div class="alert alert-danger text-center" id="HideMailNotification">
					    {{ session()->get('NoSearch') }} </div>
				    @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <a type="button" class="btn btn-danger waves-effect waves-light  mr-1"
                                        href="{{ route('booking-index') }}">Back</a>
                                </div>
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('is-booking-cancelled', $leads->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Lead Generation Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" id="training_date"
                                                    value="@php echo date('Y-m-d\TH:i:s') @endphp" name="date"
                                                    step="any"
                                                    value="@php echo date('Y-m-d\TH:i:s',strtotime($leads->date)) @endphp"
                                                    disabled>
                                                @error('date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <input type="hidden" value="{{ $leads->id }}" name="leadId">
                                            <div class="form-group mb-3">
                                                @php
                                                    $format = rtrim($leads->contact_number, ' ');
                                                    
                                                    //  dd(rtrim($format, ' '));
                                                    
                                                @endphp
                                                <label for="simpleinput">Contact Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="tel" name="contact_number" id="number"
                                                    value="{{ $leads->contact_number }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    disabled>

                                                <span id="valid-msg" class="hide text-success">
                                                    ✓ Valid
                                                </span>
                                                <span id="error-msg" class="hide text-danger"></span>
                                                @error('contact_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                    <input type="hidden" name="contact_number"
                                                        value="{{ $a = old('contact_number') }}" disabled>
                                                    @php
                                                        $Lead = App\Models\Lead::where('contact_number', $a)
                                                            ->select('id')
                                                            ->first();
                                                    @endphp
                                                    @if ($message == 'The contact number has already been taken.' . old('contact_number'))
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ url('lead-status/' . $Lead->id) }}">
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

                                                <label for="simpleinput">Customer Name<span
                                                        class="text-danger">*</span></label>
                                                {{-- <span class="fa fa-plus add_fields" id="Array_name"
                                                    style="cursor: pointer"></span> --}}
                                                <input type="text" name="lead_name" class="form-control"
                                                    value="{{ $leads->lead_name }}" disabled>
                                                @error('lead_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Alt Contact Number 1</label>
                                                <input type="text" id="alt_contact_number" name="alt_contact_number"
                                                    value="{{ $leads->alt_no_Whatsapp }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    disabled>
                                                <span id="valid-msg-1" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-1" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Alt Contact Number 2</label>
                                                <input type="text" id="alt_contact_number_2" name="alt_contact_number_2"
                                                    value="{{ $leads->alt_no_Whatsapp_2 }}" class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    disabled>
                                                <span id="valid-msg-2" class="hide text-success" style="display: none;">✓
                                                    Valid</span>
                                                <span id="error-msg-2" class="hide text-danger"
                                                    style="display: none;"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 wrapper1">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Customer Email</label>
                                                <input type="email" name="lead_email" value="{{ $leads->lead_email }}"
                                                    class="form-control" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Customer Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="buyer_seller" class="selectpicker" data-style="btn-light"
                                                    id="customerType" onchange="yesnoCheck(this);"
                                                    placeholder="Select Lead Status" disabled>
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
                                                        value="{{ $leads->rent }}" disabled>
                                                </div>
                                            @else
                                                <div class="form-group mb-3" id="rentBudget" style="display: none;">
                                                    <label for="simpleinput">Rent Budget</label>
                                                    <input type="text" name="rent" id="test"
                                                        class="form-control" value="{{ null }}" disabled>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Customer Requirement <span
                                                        class="text-danger">*</span></label>
                                                <select name="customer_requirement[]" class="selectpicker"
                                                    data-style="btn-light" multiple id="customer_requirement" disabled>
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

                                                <label for="example-select">Lead Assigned To <span
                                                        class="text-danger">*</span></label>
                                                <select name="assign_employee_id" class="selectpicker"
                                                    data-style="btn-light" id="assign_employee_id" disabled>
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
                                                <label for="example-select">Buying Location <span
                                                        class="text-danger">*</span></label>
                                                <select name="buying_location" class="selectpicker"
                                                    data-style="btn-light" id="buying_location" disabled>
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
                                                        @endif --}}
                                                        @if (in_array($location->id, $selected))
                                                            <option value="{{ $location->id }}" selected>
                                                                {{ $location->location }}</option>
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
                                                <label for="example-select">Source <span
                                                        class="text-danger">*</span></label>
                                                <select name="source" class="selectpicker" data-style="btn-light"
                                                    id="source" disabled>
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

                                                <label for="example-select">Lead Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_status" class="selectpicker" data-style="btn-light"
                                                    id="lead_status" placeholder="Select Lead Status" disabled>

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

                                                <label for="example-select">Lead Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="lead_type" class="selectpicker" data-style="btn-light"
                                                    id="example-select" disabled>
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
                                                    data-style="btn-light" id="example-select"
                                                    placeholder="Select Lead Status" disabled>
                                                    @foreach ($number_of_units as $number_of_unit)
                                                        @if ($number_of_unit->number_of_units == $leads->number_of_units)
                                                            <option
                                                                value="{{ $number_of_unit->number_of_units . ' unit' }}"
                                                                selected>
                                                                {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                        @else
                                                            <option
                                                                value="{{ $number_of_unit->number_of_units . ' unit' }}">
                                                                {{ $number_of_unit->number_of_units . ' unit' }}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Project</label>
                                                <select name="project" class="selectpicker" data-style="btn-light"
                                                    id="project" disabled>
                                                    <option>Select Project</option>
                                                    @foreach ($projects as $project)
                                                        @if ($project->id == $leads->project_id)
                                                            <option value="{{ $project->id }}" selected>
                                                                {{ $project->project_name }}</option>
                                                        @else
                                                            <option value="{{ $project->id }}">
                                                                {{ $project->project_name }}</option>
                                                        @endif
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
                                                    value="{{ $leads->location_of_client }}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Budget</label>
                                                <select name="budget" class="selectpicker" data-style="btn-light"
                                                    id="budget" placeholder="Select Lead Status" disabled>
                                                    @foreach ($Budgets as $Budget)
                                                        @if ($Budget->budget == $leads->budget)
                                                            <Option name="buget" value="{{ $Budget->budget }}"
                                                                selected>
                                                                {{ $Budget->budget }}</Option>
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
                                                    data-style="btn-light" id="example-select"
                                                    placeholder="Select Lead Status" disabled>

                                                    {{-- @if ($leads->investment_or_end_user == 'Investment') --}}
                                                    <option value="Investment">Investment</option>
                                                    {{-- @elseif($leads->investment_or_end_user ==" End User") --}}
                                                    <option value="End User">End User</option>
                                                    {{-- @elseif($leads->investment_or_end_user =="Both") --}}
                                                    <option value="Both">Both</option>
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
                                                    name="regular_investor" disabled>
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

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Reference Name</label>
                                                <input type="text" name="reference" class="form-control"
                                                    value="{{ $leads->reference }}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Reference Contact Number</label>
                                                <input type="text" name="reference_contact_number"
                                                    class="form-control" value="{{ $leads->reference_contact_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_number">
                                                    Relationship Contact Number
                                                </label>

                                                <input type="text" name="emergeny_contact_number" class="form-control"
                                                    id="RelationContactNumber" onkeyup="myFunction()"
                                                    value="{{ $leads->emergeny_contact_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}" disabled>
                                            </div>
                                        </div>



                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">

                                                <label for="example-select">Relationship Contact Name</label>
                                                <input type="text" class="form-control" name="relationship"
                                                    id="relationshipName" value="{{ $leads->relationship }}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>Relationship</label>
                                                <select name="relationshipName" class="form-control" disabled>
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
                                                <label for="simpleinput">Booking Date <span class="text-danger"
                                                        id="RestrictBookingDate " style="display: none;">*</span></label>
                                                <input type="date" name="booking_date" class="form-control"
                                                    value="{{ $leads->booking_Date }}" disabled>
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
                                                <label for="example-select">Booking Project <span class="text-danger"
                                                        id="BookingProject" style="display: none;">*</span></label>
                                                <select name="booking_project[]" class="selectpicker"
                                                    data-style="btn-light" multiple id="booking_project" disabled>

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
                                                    <span class="text-danger" id="restrict"
                                                        style="display: none;">*</span>
                                                </label>
                                                <input type="text" name="booking_amount" class="form-control"
                                                    value="{{ $leads->booking_amount }}" disabled>
                                                @error('booking_amount')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Customer Interaction</label>
                                                <textarea class="form-control" name="customer_interaction" id="exampleFormControlTextarea1" rows="2" disabled>{{ $leads->customer_interaction }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Customer Profile</label>
                                                <textarea class="form-control" name="about_customer" id="exampleFormControlTextarea1" rows="2" disabled>{{ $leads->about_customer }}</textarea>
                                            </div>
                                        </div>

                                        <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="checkbox-2" name="common_pool_status" type="checkbox"
                                                value="1" {{ $leads->common_pool_status == 1 ? 'checked' : '' }}
                                                disabled>
                                            <label for="checkbox-2">
                                                Move to Common Pool
                                            </label>
                                        </div>

                                        <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="checkbox-3" name="is_featured" type="checkbox" value="1"
                                                {{ $leads->is_featured == 1 ? 'checked' : '' }} disabled>
                                            <label for="checkbox-3">
                                                Featured Lead
                                            </label>
                                        </div>

                                         <div
                                            class="checkbox checkbox-success checkbox-circle  ml-2 d-flex align-items-center">
                                            <input id="BookingCanceld" name="BookingCanceld" type="checkbox"
                                                onclick="BookingCancel()">
                                            <label for="checkbox-3">
                                                Booking Cancelled
                                            </label>
                                        </div> 
                                    </div>

                                     <button name="submit" value="submit" type="submit" id="isBookingCancel"
                                        class="btn btn-primary waves-effect waves-light mt-3" style="display: none">
                                        Update Lead</button>  

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
     <script>
        function BookingCancel() {
            var checkBox = document.getElementById("BookingCanceld");
            var text = document.getElementById("isBookingCancel");
            if (checkBox.checked == true) {
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script> 
@endsection
