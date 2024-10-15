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
                            <li class="breadcrumb-item active">Builder</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Builder Team Name</h4>
                </div>
                <div class="form-group"> 
                    <a href="{{ url('builder') }}" class="btn btn-info waves-effect waves-light" target="_blank">
                        View Builder
                    </a> 
                </div> 
            </div>  
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('builder-create') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Name" name="team_name"
                                                        class="form-control" value="{{ old('team_name') }}">
                                                </div>
                                                @error('team_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Contact Number <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Contact Number"
                                                        name="team_phone_number" class="form-control"
                                                        value="{{ old('team_phone_number') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        pattern="[1-9]{1}[0-9]{9}">
                                                </div>
                                                @error('team_phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Alternate Contact Number</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Contact Number"
                                                        name="alternate_contact_number_team" id="contactNumber"
                                                        class="form-control"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        pattern="[1-9]{1}[0-9]{9}"
                                                        value="{{ old('alternate_contact_number_team') }}">
                                                </div>
                                                @error('alternate_contact_number_team')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber"> Sales Person Official Email </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Email" name="team_email"
                                                        class="form-control" value="{{ old('team_email') }}">
                                                </div>
                                                @error('team_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>



                                         <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber"> Saler Person Alt Email </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Saler Person Alt Email"
                                                        name="saler_person_alt_email" class="form-control"
                                                        value="{{ old('team_email') }}">
                                                </div>
                                                @error('saler_person_alt_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>  

                                        {{-- <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber"> CC Email </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="email" name="builder_cc_mail" class="form-control"
                                                    placeholder="CC Mail"
                                                    multiple
                                                        value={{ old('builder_cc_mail')}} 
                                                        >
                                                </div> 
                                            </div>
                                        </div>  --}}


                                        <div class="col-md-4">
                                            @php
                                                $builders = DB::table('name_of_developers')->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-email">Name of Developer 
                                                    {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                <select name="name_of_developer" id="name_of_developers"
                                                    data-style="btn-light" class="selectpicker"
                                                    onchange="yesnoCheck(this);" 
                                                    >
                                                    <option value="">Select</option>
                                                    @foreach ($builders as $builder)
                                                        <option value="{{ $builder->id }}"
                                                            {{ collect(old('name_of_developer'))->contains($builder->id) ? 'selected' : '' }}>
                                                            {{ $builder->name_of_developer }}</option>
                                                    @endforeach
                                                </select>
                                                @error('name_of_developer')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-3" id="projectAssingshow" 
                                           {{-- style="display: none"  --}}
                                            >
                                                <label for="example-email">Project Assign 
                                                     {{-- <span class="text-danger">*</span> --}}
                                                    </label>
                                                <select class="selectpicker" name="project[]" id="assignProject" multiple>
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
                                                <label for="example-email">Designation </label>
                                                <select class="selectpicker" data-style="btn-light" name="designation"
                                                id="designation">
                                                    <option value="" selected>Select Designation</option>
                                                    @foreach ($designations as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('designation'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->designation_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('designation')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Date of Birth</label>
                                                @php
                                                    $date = \Carbon\Carbon::parse()->format('d-M-Y');
                                                    
                                                    // echo $date;
                                                    
                                                    $dob = Carbon\Carbon::now()
                                                        ->subYears(18)
                                                        ->format('Y-m-d');
                                                    
                                                    $weddingAnniversary = Carbon\Carbon::now()->format('Y-m-d');
                                                    // echo $weddingAnniversary;
                                                @endphp
                                                <div class="input-group input-group-merge">
                                                    <input type="date" id="datepicker" name="date_of_birth"
                                                        max="{{ $dob }}" class="form-control"
                                                        value="{{ old('date_of_birth') }}">

                                                </div>
                                                @error('date_of_birth')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Wedding Anniversary </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="date" name="wedding_anniversary" class="form-control"
                                                        max="{{ $weddingAnniversary }}"
                                                        value="{{ old('wedding_anniversary') }}">
                                                </div>
                                                @error('wedding_anniversary')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                @php
                                                    $builders = DB::table('builders')->get();
                                                @endphp
                                                <label for="example-email">Builder/CP/Individual</label>
                                                <select class="selectpicker" data-style="btn-light" name="builder" id="builder">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($builders as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('builder'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Remark</span>
                                                </label>
                                                <textarea class="form-control" name="remark" id="exampleFormControlTextarea1" rows="4">{{ old('remark') }}</textarea>
                                                @error('remark')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>

                                </div>

                            <hr>






                            {{-- <div class="custom-control custom-switch">
                                        <input name="project_status" type="checkbox" class="custom-control-input"
                                            id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1">Activate</label>
                                    </div> --}}

                        </div>
                        <button name="submit" value="submit" type="submit"
                            class="mt-4 btn btn-primary waves-effect waves-light">Add Builder</button>

                        </form>
                    </div>
                </div>



            </div> <!-- end card-body-->

        </div>


    </div> <!-- end col -->

    </div>
    <!-- end row -->

    </div> <!-- container -->
@endsection

@section('scripts')
    <style>
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
        $('#assignProject').select2({
            placeholder: 'Select',
        });

        $('#name_of_developers').select2({
            //placeholder: 'Select',
        });

        $('#builder').select2({
            // placeholder: "Select",
            minimumResultsForSearch: Infinity
        });
        $('#designation').select2({
            // placeholder: "Select",
            minimumResultsForSearch: Infinity
        });
        


        //set a drop down list in a select field based on select before on laravel

        $(document).ready(function() {
            $('select[name="name_of_developer"]').on('change', function() {
                var NameOfDeveloper = $(this).val(); 
                if (NameOfDeveloper) {
                    $.ajax({
                        url: "{{ url('/search-by-name-deloper/') }}/" + NameOfDeveloper,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            if (data == "error") {
                                $('select[name="project[]"]').html(
                                    '<option value="">Selected Name of Developer has No Project Assign</option>'
                                ).attr("disabled", true);
                            } else {
                                $('select[name="project[]"]').html(
                                    '<option value="">Select Project Assign</option>').attr(
                                    "disabled", false);;
                                $.each(data, function(key, value) {
                                    $('select[name="project[]"]').append(
                                        '<option value="' + value.id + '">' + value
                                        .project_name + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('select[name="project[]"]').html(
                            '<option value="">First Select Name of Developer</option>')
                        .attr("disabled", false);
                }
            });
        });
        //end set a drop down list in a select field based on select before on laravel

        function yesnoCheck(that) {
            // alert("Hello");
            if (that.value == null) {
                document.getElementById("projectAssingshow").style.display = "none";
            } else {
                document.getElementById("projectAssingshow").style.display = "block";
            }
        };
    </script>
@endsection

