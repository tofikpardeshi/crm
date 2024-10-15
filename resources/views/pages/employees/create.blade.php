@extends('main')


@section('dynamic_page')

<style>
    .bootstrap-select .dropdown-menu{max-height: 400px !important}
    .bootstrap-select .dropdown-menu .inner{max-height: 400px !important; overflow-y: auto !important}
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
                    <h4 class="page-title">Create Employee</h4>
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
                                <form action="{{ route('create-employee') }}" enctype="multipart/form-data" method="post">
                                    @csrf


                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Employee ID<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="employeeID" class="form-control"
                                                    autocomplete="off" placeholder="Empolyee ID" value="{{ old('employeeID') }}">
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
                                                    autocomplete="off" placeholder="Name" value="{{ old('employee_name') }}">
                                                @error('employee_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Full Name</label>
                                                <input type="text" name="full_emp_name" class="form-control"
                                                    autocomplete="off" placeholder="Name" value="{{ old('full_emp_name') }}"> 
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Official Email
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" name="official_email" id="example-email"
                                                    class="form-control" placeholder="Email" autocomplete="off"
                                                    value="{{ old('official_email') }}">
                                                @error('official_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Personal Email</label>
                                                <input type="email" name="personal_email" id="example-email"
                                                    class="form-control" placeholder="Personal Email" autocomplete="off"
                                                    value={{ old('personal_email') }}>
                                                {{-- @error('personal_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror   --}}
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" name="main_password" id="password"
                                                        class="form-control" placeholder="Enter your password"
                                                        value="{{ old('main_password') }}"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                        title="The password must be of at least eight characters, including at least one number and includes both lower and uppercase letters and special characters.">
                                                    <div class="input-group-append" data-password="false">
                                                        <div class="input-group-text">
                                                            <span class="password-eye"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @error('main_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="password">Confirm Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" name="confirm_password" id="password"
                                                        class="form-control" placeholder="Enter your password"
                                                        value="{{ old('confirm_password') }}"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                        title="The password must be of at least eight characters, including at least one number and includes both lower and uppercase letters and special characters.">
                                                    <div class="input-group-append" data-password="false">
                                                        <div class="input-group-text">
                                                            <span class="password-eye"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('confirm_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Role <span
                                                        class="text-danger">*</span></label>
                                                <select name="role_id" class="form-control" id="example-select">
                                                    <option value="" selected>Select Role</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ collect(old('role_id'))->contains($role->id) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Phone-Number">Official Phone Number</label>
                                                <input  type="tel" id="official" placeholder="Official Phone Number" name="official_phone_number[official_cc]" class="form-control"
                                                    value="{{ old('official_phone_number.official_cc') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    {{-- pattern="[1-9]{1}[0-9]{9}" --}}
                                                    >
                                                @error('official_phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Phone-Number">Personal Phone Number </label>
                                                <input type="text" name="personal_phone_number" placeholder="Personal Phone Number" class="form-control"
                                                    value="{{ old('personal_phone_number') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}">
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
                                                <select class="selectpicker" data-style="btn-light" name="department"
                                                id="department">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($departments as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('department'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->department_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Aadhaar Number</label>
                                                <input type="text" name="addhar_number" id="addhar_number"
                                                    class="form-control" placeholder="Aadhaar Number" value="{{ old('addhar_number') }}">

                                                @error('addhar_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="pan-number">PAN Number</label>
                                                <input type="text" name="pan_Number" placeholder="PAN Number" class="form-control"
                                                    value="{{ old('pan_Number') }}">
                                                @error('pan_Number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Date Of Joining</label>
                                                <input type="date" name="date_joining" class="form-control"
                                                    value="{{ old('date_joining') }}">
                                                @error('date_joining')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">

                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">
                                                    Last working Date
                                                </label>
                                                <input type="date" name="leaving_date" class="form-control"
                                                    value="{{ old('leaving_date') }}">
                                                @error('leaving_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            @php
                                                $dob = Carbon\Carbon::now()
                                                    ->subYears(18)
                                                    ->format('Y-m-d');
                                                $weddingAnniversary = Carbon\Carbon::now()->format('Y-m-d');
                                                // echo $weddingAnniversary;
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Date of Birth</label>
                                                <input type="date" name="date_of_brith" class="form-control"
                                                    max="{{ $dob }}" value="{{ old('date_of_brith') }}">
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
                                                    value="{{ old('marriage_anniversary') }}">
                                                @error('marriage_anniversary')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Current Address</label>
                                                <input type="text" name="current_address" placeholder="Current Address" class="form-control"
                                                    value="{{ old('current_address') }}">

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Permanent Address</label>
                                                <input type="text" name="permanent_address" placeholder="Permanent Address" class="form-control"
                                                    value="{{ old('permanent_address') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Education Background</label>
                                                <input type="text" name="education_background" placeholder="Education Background" class="form-control"
                                                    value="{{ old('education_background') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Blood Group
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="blood_group" class="selectpicker" id="blood_group"
                                                data-live-search="true">
                                                    <option value="" selected>Select Blood Group</option>
                                                    @foreach ($bloobgroups as $bloobgroup)
                                                        <option value="{{ $bloobgroup->name }}"
                                                            {{ collect(old('blood_group'))->contains($bloobgroup->name) ? 'selected' : '' }}>
                                                            {{ $bloobgroup->name }}
                                                        </option>
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
                                                <input type="text" name="emergeny_contact_name" placeholder="Emergency Contact Name" class="form-control"
                                                    value="{{ old('emergeny_contact_name') }}">
                                                @error('emergeny_contact_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_name">Emergency Contact Number</label>
                                                <input type="text" name="emergeny_contact_number" placeholder="Emergency Contact Number" class="form-control"
                                                    value="{{ old('emergeny_contact_number') }}"
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
                                                        <option value="{{ $relation->name }}"
                                                            {{ collect(old('relationship'))->contains($relation->name) ? 'selected' : '' }}>
                                                            {{ $relation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Location<span
                                                        class="text-danger">*</span></label>
                                                <select name="employee_location[]" id="location"
                                                    class="selectpicker form-control" multiple > 
                                                    @foreach ($locations as $location)
                                                    
                                                        <option value="{{ $location->id }}"
                                                            {{ collect(old('employee_location'))->contains($location->id) ? 'selected' : '' }}>
                                                            {{ $location->location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('employee_location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Phone-Number">Picture</label>
                                            <div class="dropzone-previews" id="file-previews"></div>

                                            <div class="fallback">
                                                <input class="form-control" name="photo" type="file" />
                                            </div>
                                        </div>

                                        <div class="col-lg-4 ">
                                            <label for="Phone-Number">Lead Assignment</label> <br>
                                            <div class="checkbox checkbox-success checkbox-circle mb-2 d-block">
                                                <input id="checkbox-2" name="lead_assignment" type="checkbox"
                                                    value="1" {{ old('lead_assignment') == '1' ? 'checked' : '' }}>
                                                <label for="checkbox-2">
                                                    Lead Assignment
                                                </label>
                                            </div>
                                        </div>
                                    </div>




                                    <button name="submit" type="submit" class=" mt-2 btn btn-primary"
                                        value="submit">Add
                                        Now</button>

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
    .iti__flag{
                display: none;
            }
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

        
            .iti__flag{
                display: none;
            }
</style>

<script>
     $('#location').select2({
            placeholder: "Select", 
        });
</script>
    <script>
      
        // official_phone_number 


        var input1 = document.querySelector("#official"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        
        // initialise plugin
        var iti1 = window.intlTelInput(document.querySelector("#official"), {
            initialCountry: "In",
            separateDialCode: true,
            hiddenInput: "cc",
        });

        $("form").submit(function() {
            var full_number1 = iti1.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='official[cc]'").val(full_number1);
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

