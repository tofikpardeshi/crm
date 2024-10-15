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
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                    <h4 class="page-title"> @if (Auth::user()->roles_id == 1) Admin Profile  @else Employee Profile  @endif </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
 {{-- profile page --}}


        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }} </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible text-center">
                                <h5>{{ Session::get('success') }}</h5>
                            </div>
                        @endif
                        <h4 class="mb-3 header-title">Profile Details</h4>

                        <form class="form-horizontal" method="post" action="{{ route('update-profile') }}"
                            enctype="multipart/form-data">
                            @csrf


                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                                            class="form-control" id="first_name" placeholder="Enter Name" required 
                                            oninvalid="this.setCustomValidity('Pls pick these details from the Employee records automatically.')"
                                            oninput="this.setCustomValidity('')" @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email" class=" col-form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                                            class="form-control" id="email" placeholder="email" required
                                            oninvalid="this.setCustomValidity('Pls pick these details from the Employee records automatically.')"
                                            oninput="this.setCustomValidity('')"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="example-password" class=" col-form-label">Aadhar Number</label>
                                        <input type="text" name="addhar_number" id="addhar_number" class="form-control"
                                            value="{{ $employeeData['addhar_number'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>

                                        @error('addhar_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="pan-number">PAN Number</label>
                                        <input type="text" name="pan_Number" class="form-control"
                                            value="{{ $employeeData['pan_Number'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        @error('pan_Number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    @php
                                    $dob = Carbon\Carbon::now()
                                        ->subYears(18)
                                        ->format('Y-m-d'); 
                                    @endphp
                                    <div class="form-group">
                                        <label for="Date of Brith">Date of Brith</label>
                                        <input type="date" name="date_of_brith" class="form-control"
                                            value="{{ $employeeData['date_of_brith'] }}"  max="{{ $dob }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        @error('date_of_brith')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-password">Address</label>
                                        <input type="text" name="current_address" id="address" class="form-control"
                                            value="{{ $employeeData['current_address'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>

                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="Date of Brith">Education Background</label>
                                        <input type="text" name="education_background" class="form-control"
                                            value="{{ $employeeData['education_background'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        @error('education_background')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="Blood Group">Blood Group</label>
                                        <input type="text" name="blood_group" class="form-control"
                                            value="{{ $employeeData['blood_group'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        @error('blood_group')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="Emergeny_Contact_Number ">
                                            Emergency Contact Number
                                        </label>
                                        <input type="text" name="emergeny_contact_name" class="form-control"
                                            value="{{ $employeeData['emergeny_contact_name'] }}"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            pattern="[1-9]{1}[0-9]{9}" maxlength="10"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        @error('emergeny_contact_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="Personal-Email">Personal Email</label>
                                        <input type="email" name="personal_email" class="form-control"
                                            value="{{ $employeeData['personal_email'] }}"
                                            @if (Auth::user()->roles_id == 1)  @else disabled @endif>

                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="profilePicture" class="col-form-label">Profile Picture</label>
                                        <input type="file" name="profile_pic" id=""  @if (Auth::user()->roles_id == 1)  @else disabled @endif>
                                        <div class="dropzone-previews mt-3" id="file-previews"></div>

                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-3">
                                        <img id="image" src="{{ url('') }}/assets/images/users/no.png" alt="Picture"
                                            class="img-fluid">
                                    </div>
                                </div> --}}

                            {{--</div>
                            @if (Auth::user()->roles_id == 1)  
                            <button name="submit" value="submit" type="submit"
                                class="btn btn-primary waves-effect waves-light">Update
                                Profile</button>
                            @else 
                            
                            @endif 
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div> --}}

        {{-- profile page end --}}


        <!-- start page title -->
        
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            @php 
                            $locations = App\Models\Location::all();
                        @endphp
                            @if (\Auth::user()->roles_id == 1 )
                            <a type="button" href="{{ url('edit-employee/' . encrypt($employee->id)) }}" class="btn btn-danger waves-effect waves-light mr-2" >Edit
                            </a> 
                            @endif
                           

                            <a type="button" class="btn btn-danger waves-effect waves-light  "
                                href="{{ url('/leads') }}">Back</a> 
                            
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
                                                    class="form-control" autocomplete="off" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="control-label" for="simpleinput">Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="employee_name" class="form-control"
                                                    value="{{ $employee->employee_name }}" autocomplete="off" readonly> 
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Official Email
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" value="{{ $employee->email }}" name="official_email"
                                                    id="example-email" name="example-email" class="form-control"
                                                    placeholder="Official Email" autocomplete="off" readonly> 
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Personal Email</label>
                                                <input type="email" value="{{ $employee->personal_email }}"
                                                    name="personal_email" id="example-email" class="form-control"
                                                    placeholder="Email" autocomplete="off" readonly> 
                                            </div>
                                        </div>



                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Role <span class="text-danger">*</span></label>
                                                <select name="role_id" class="form-control" id="example-select" disabled>
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
                                                <input type="text" name="official_phone_number" class="form-control"
                                                    value="{{ $employee->official_phone_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}"
                                                    readonly> 
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
                                                    pattern="[1-9]{1}[0-9]{9}" maxlength="10"
                                                    readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                @php
                                                    $departments = DB::table('departments')->get();
                                                @endphp

                                                <label for="example-email">Department</label>
                                                <select class="selectpicker" data-style="btn-light" name="department" disabled>
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
                                                    class="form-control" value="{{ $employee->addhar_number }}" readonly> 
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="pan-number">PAN Number</label>
                                                <input type="text" name="pan_Number" class="form-control"
                                                    value="{{ $employee->pan_Number }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Date Of Joining</label>
                                                <input type="date" name="date_joining" class="form-control"
                                                    value="{{ $employee->date_joining }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label>
                                                    Last working Date
                                                </label>
                                                <input type="date" name="leaving_date" class="form-control"
                                                    value="{{ $employee->leaving_date }}" readonly> 
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
                                                    max="{{ $dob }}" value="{{ $employee->date_of_brith }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Marriage Anniversary</label>
                                                <input type="date" name="marriage_anniversary" class="form-control"
                                                    max="{{ $weddingAnniversary }}"
                                                    value="{{ $employee->marriage_anniversary }}" readonly>  
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Current Address</label>
                                                <input type="text" name="current_address" id="address"
                                                    class="form-control" value="{{ $employee->current_address }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="example-password">Permanent Address</label>
                                                <input type="text" name="permanent_address" id="address"
                                                    class="form-control" value="{{ $employee->permanent_address }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Date of Brith">Education Background</label>
                                                <input type="text" name="education_background" class="form-control"
                                                    value="{{ $employee->education_background }}" readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Blood Group<span
                                                        class="text-danger">*</span></label>
                                                <select name="blood_group" class="selectpicker" id="blood_group"
                                                    data-live-search="true" disabled>
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
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_name">Emergency Contact Name</label>
                                                <input type="text" name="emergeny_contact_name" class="form-control"
                                                    value="{{ $employee->emergeny_contact_name }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="Emergeny_Contact_name">Emergency Contact Number</label>
                                                <input type="text" name="emergeny_contact_number" class="form-control"
                                                    value="{{ $employee->emergeny_contact_number }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    pattern="[1-9]{1}[0-9]{9}"
                                                    readonly> 
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Relationship</label>
                                                <select name="relationship" class="form-control" id="example-select"  disabled>
                                                     
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
                                                <select name="employee_location[]" class="form-control selectpicker"
                                                    id="location" multiple data-live-search="true" disabled>
                                                   
                                                    @foreach ($locations as $location)
                                                        @if (in_array($location->id, $selected))
                                                            <option id="empLocation" value="{{ $location->id }}"
                                                                @if ($isEmpLeadNotAssign == null) selected
                                                            @else
                                                            selected disabled @endif>
                                                                {{ $location->location }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $location->id }}">
                                                                {{ $location->location }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($isEmpLeadNotAssign == null)
                                                @else
                                                    <input type="hidden" name="employee_location[]"
                                                        value="{{ $employee->employee_location }}">
                                                @endif

                                                <input type="hidden" name="animal" value="cat" />
                                                

                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Login Status </label>
                                                <select name="loginStatus" class="form-control selectpicker"
                                                    id="example-select" disabled>
                                                    <option value="1"
                                                        {{ $employee->login_status == 1 ? 'selected' : '' }}>Active</option>

                                                    <option value="0"
                                                        {{ $employee->login_status == 0 ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="example-select">Choose Image</label>
                                            <div class="dropzone-previews " id="file-previews"></div>

                                            <div class="fallback">
                                                <input class="form-control" name="photo" type="file" disabled/>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-success checkbox-circle mb-2 mt-4">
                                                <input id="checkbox-2" name="lead_assignment" type="checkbox"
                                                    value="1"
                                                    {{ $employee->lead_assignment == 1 ? ' checked' : '' }} disabled>
                                                <label for="checkbox-2">
                                                    Lead Assignment
                                                </label>
                                                <input id="checkbox-3" class="mr-2" name="organisationLeave"
                                                    type="checkbox" value="1"
                                                    {{ $employee->organisation_leave == 1 ? ' checked' : '' }} disabled>
                                                <label for="checkbox-3">
                                                    Leave Organisation
                                                </label>
                                            </div>
                                        </div>
                                    </div> 
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

        <!-- end row -->

        <div class="row">

            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">

                        <h4 class="mb-3 header-title">Change Password</h4>

                        <form class="form-horizontal" action="{{ route('change-password') }}" method="post">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="inputEmail3" class="col-3 col-form-label">Old Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-9">
                                    <input type="password" name="old_password" class="form-control" id="oldPassword"
                                        placeholder="Old Password">
                                    @error('old_password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword3" class="col-3 col-form-label">New Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-9">
                                    <input type="password" name="new_password" class="form-control"
                                        id="inputnewPassword3" placeholder="New Password"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="New password uppercase and lowercase letter, and at least 8 or more characters">
                                    @error('new_password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword5" class="col-3 col-form-label">Re Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-9">
                                    <input type="password" name="confirm_password" class="form-control"
                                        id="inputPassword5" placeholder="Retype Password"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="Re Password same as new password uppercase and lowercase letter, and at least 8 or more characters">
                                    @error('confirm_password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row">
                                <div class="col-9">
                                    <button type="submit" name="submit" value="submit"
                                        class="btn btn-primary waves-effect waves-light">Change Password</button>
                                </div>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>

    </div> <!-- container -->
@endsection

