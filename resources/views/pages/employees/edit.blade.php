@extends('main')


@section('dynamic_page')
<style>
    .bootstrap-select .dropdown-menu{
        /* max-height: 400px !important */
    }
    .bootstrap-select .dropdown-menu .inner{
     max-height: 200px !important;  
     overflow-y: auto !important
     }
</style>
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
                    <h4 class="page-title">Edit Employee</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a type="button" class="btn btn-danger waves-effect waves-light  "
                                href="{{ url('employees') }}">Back</a>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form action="{{ route('update-employee', $employee->id) }}" enctype="multipart/form-data"
                                    method="post">
                                    @csrf


                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Employee ID<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" value="{{ $employee->employeeID }}" name="employeeID"
                                                    class="form-control" autocomplete="off">
                                                @error('employeeID')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Nick Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="employee_name" class="form-control"
                                                    value="{{ $employee->employee_name }}" autocomplete="off" required>
                                                @error('employee_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Full Name</label>
                                                <input type="text" name="full_emp_name" class="form-control"
                                                    value="{{ $employee->full_emp_name }}" autocomplete="off"> 
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Official Email
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" value="{{ $employee->email }}" name="official_email"
                                                    id="example-email" name="example-email" class="form-control"
                                                    placeholder="Official Email" autocomplete="off">
                                                @error('official_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Personal Email</label>
                                                <input type="email" value="{{ $employee->personal_email }}"
                                                    name="personal_email" id="example-email" class="form-control"
                                                    placeholder="Email" autocomplete="off">
                                                @error('personal_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Role <span class="text-danger">*</span></label>
                                                <select name="role_id" class="form-control" id="example-select" required>
                                                    @foreach ($roles as $role)
                                                        @if ($role->id == $employee->role_id)
                                                            <option value="{{ $role->id }}" selected>
                                                                {{ $role->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $role->id }}">
                                                                {{ $role->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Phone-Number">Official Phone Number
                                                    {{-- <span class="text-danger">*</span> --}}
                                                </label>
                                                <div class="d-flex">

                                                    <input type="tel" id="officialC" name="official_phone_number[emp_country_code]" class="form-control"
                                                    data-initial-country="{{$EmpCountryCodeIso->iso ?? "In"}}"
                                                    value="{{ $employee->emp_country_code }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                     > 

                                                    <input type="tel" id="official" name="official_phone_number[official_cc]" class="form-control"
                                                    value="{{ $employee->official_phone_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    style="margin-left: 61px!important; z-index:1!important">
                                                </div>
                                                @error('official_phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Phone-Number">Personal Phone Number
                                                    {{-- <span class="text-danger">*</span> --}}
                                                </label>
                                                <input type="text" name="personal_phone_number" class="form-control"
                                                    value="{{ $employee->personal_phone_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}" maxlength="10">
                                                @error('personal_phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                @php
                                                    $departments = DB::table('departments')->get();
                                                @endphp

                                                <label for="example-email">Department</label>
                                                <select class="selectpicker" data-style="btn-light" name="department">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($departments as $item)
                                                        @if ($item->id == $employee->department)
                                                            <option value="{{ $item->id }}"
                                                                {{ collect(old('department'))->contains($item->id) ? 'selected' : '' }}
                                                                selected>
                                                                {{ $item->department_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item->id }}"
                                                                {{ collect(old('department'))->contains($item->id) ? 'selected' : '' }}>
                                                                {{ $item->department_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Aadhaar Number</label>
                                                <input type="text" name="addhar_number" id="addhar_number"
                                                    class="form-control" value="{{ $employee->addhar_number }}">

                                                @error('addhar_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="pan-number">PAN Number</label>
                                                <input type="text" name="pan_Number" class="form-control"
                                                    value="{{ $employee->pan_Number }}">
                                                @error('pan_Number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Date Of Joining</label>
                                                <input type="date" name="date_joining" class="form-control"
                                                    value="{{ $employee->date_joining }}">
                                                @error('date_joining')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>
                                                    Last working Date
                                                </label>
                                                <input type="date" name="leaving_date" class="form-control"
                                                    value="{{ $employee->leaving_date }}">
                                                @error('leaving_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        @php
                                            $dob = Carbon\Carbon::now()
                                                ->subYears(18)
                                                ->format('Y-m-d');
                                            
                                            $weddingAnniversary = Carbon\Carbon::now()->format('Y-m-d');
                                            // echo $weddingAnniversary;
                                        @endphp

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>Date of Birth</label>
                                                <input type="date" name="date_of_brith" class="form-control"
                                                    max="{{ $dob }}" value="{{ $employee->date_of_brith }}">
                                                @error('date_of_brith')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Marriage Anniversary</label>
                                                <input type="date" name="marriage_anniversary" class="form-control"
                                                    max="{{ $weddingAnniversary }}"
                                                    value="{{ $employee->marriage_anniversary }}">
                                                @error('marriage_anniversary')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Current Address</label>
                                                <input type="text" name="current_address" id="address"
                                                    class="form-control" value="{{ $employee->current_address }}">

                                                @error('address')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Permanent Address</label>
                                                <input type="text" name="permanent_address" id="address"
                                                    class="form-control" value="{{ $employee->permanent_address }}">

                                                @error('address')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Education Background</label>
                                                <input type="text" name="education_background" class="form-control"
                                                    value="{{ $employee->education_background }}">
                                                @error('education_background')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Blood Group<span
                                                        class="text-danger">*</span></label>
                                                <select name="blood_group" 
                                                class="selectpicker" id="blood_group"
                                                data-live-search="true">
                                                    <option value="" selected>Select Blood Group</option>
                                                    @foreach ($bloobgroups as $bloobgroup)
                                                        @if ($bloobgroup->name == $employee->blood_group)
                                                            <option value="{{ $bloobgroup->name }}" selected>
                                                                {{ $bloobgroup->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $bloobgroup->name }}">
                                                                {{ $bloobgroup->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('blood_group')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_name">Emergency Contact Name</label>
                                                <input type="text" name="emergeny_contact_name" class="form-control"
                                                    value="{{ $employee->emergeny_contact_name }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_name">Emergency Contact Number</label>
                                                <input type="text" name="emergeny_contact_number" class="form-control"
                                                    value="{{ $employee->emergeny_contact_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}">
                                                @error('emergeny_contact_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Relationship</label>
                                                <select name="relationship" class="form-control" id="example-select">
                                                    <option>Select</option>
                                                    @foreach ($relations as $relation)
                                                        @if ($relation->name == $employee->relationship)
                                                            <option class="text-capitalize" value="{{ $relation->name }}"
                                                                selected>
                                                                {{ $relation->name }}
                                                            </option>
                                                        @else
                                                            <option class="text-capitalize"
                                                                value="{{ $relation->name }}">
                                                                {{ $relation->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            @php
                                                $selected = explode(',', $employee->employee_location);
                                                $isEmpLeadNotAssign = DB::table('leads')
                                                    ->where('assign_employee_id', $employee->id)
                                                    ->first();
                                                //dd($isEmpLeadNotAssign);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="employee_location[]" class="selectpicker"
                                                    id="location" multiple data-live-search="true">
                                                    @foreach ($locations as $location)
                                                        @if (in_array($location->id, $selected))
                                                            <option id="empLocation" value="{{ $location->id }}"
                                                                @if ($isEmpLeadNotAssign == null) selected
                                                            @else
                                                            selected disabled @endif
                                                            class="text-wrap">
                                                                {{ $location->location }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $location->id }}"
                                                                class="text-wrap">
                                                                {{ $location->location }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($isEmpLeadNotAssign == null)
                                                @else
                                                    <input type="hidden" name="employee_location[]"
                                                        value="{{ $employee->employee_location }}" 
                                                        class="text-wrap">
                                                @endif

                                                <input type="hidden" name="animal" value="cat" /> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4"> 
                                            <div class="form-group mb-3">
                                                <label for="example-select">Login Status </label>
                                                <select name="loginStatus" class="form-control selectpicker"
                                                    id="example-select" >  
                                                        <option value="1" {{$employee->login_status == 1 ? 'selected': ''}} >Active</option> 
                                                         
                                                        <option value="0" {{$employee->login_status == 0 ? 'selected': ''}}>Inactive</option>  
                                                </select> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="example-select">Choose Image</label>
                                            <div class="dropzone-previews " id="file-previews"></div>

                                            <div class="fallback">
                                                <input class="form-control" name="photo" type="file" />
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-success checkbox-circle mb-2 mt-4">
                                                <input id="checkbox-2" name="lead_assignment" type="checkbox"
                                                    value="1"
                                                    {{ $employee->lead_assignment == 1 ? ' checked' : '' }}>
                                                <label for="checkbox-2">
                                                    Lead Assignment
                                                </label>
                                                <input id="checkbox-3" class="mr-2" name="organisationLeave" type="checkbox"
                                                    value="1" 
                                                    {{ $employee->organisation_leave == 1 ? ' checked' : '' }}>
                                                <label for="checkbox-3">
                                                   Leave Organisation
                                                </label>
                                            </div> 
                                        </div> 
                                    </div>



                                    <button name="submit" type="submit" class=" mt-2 btn btn-primary"
                                        value="submit">Update</button>

                                </form>
                            </div>




                        </div>


                    </div> <!-- end card-body-->

                </div> <!-- end card-->
                <div class="row">
                    <div class="col-12">
                    </div>
                </div>


            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('scripts')
 
<style>
 
            .iti {
    position: relative;
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

        
         .iti__selected-flag{
            padding: 0px 6px 0px 3px!important;
         }

            .iti--allow-dropdown{
            width: 0px!important;
        }
</style>
	
 

 
    <script>
      
      
        // official_phone_number 


        var input1 = document.querySelector("#officialC"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        var cCode = document.querySelector("#officialC"); 
        // alert(cCode.value);
        if (cCode.value == "") {
        cCodeR.value = "+91";
        } 
       
        // initialise plugin
        var EmpinitialCountry = input1.getAttribute('data-initial-country'); 
        var iti1 = window.intlTelInput(document.querySelector("#officialC"), { 
            initialCountry: EmpinitialCountry,
            separateDialCode: true,
            hiddenInput: "emp_country_code",
        });
        

        $("form").submit(function() {
            var relationCode = document.getElementsByClassName('iti__selected-dial-code')[0].innerHTML;
            // alert(relationCode)  
            $("input[name='official_phone_number[emp_country_code]'").val(relationCode);
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

        // official_phone_number end
       
    </script>  
@endsection


