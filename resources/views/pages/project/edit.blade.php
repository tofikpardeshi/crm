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
                            <li class="breadcrumb-item active">Project</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Project</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-end">
                            <a type="button" class="btn btn-danger waves-effect waves-light  "
                                href="{{ url('project') }}">Back</a>
                        </div> --}}
                        <div class="row">
                            <div class="col-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('update-project', encrypt($project->id)) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex justify-content-end">
                                        <a type="button" class="m-1 btn btn-danger waves-effect waves-light btn-darkblue "
                                            href="{{ url('create-project') }}">Add New</a>

                                        <a type="button" class="m-1 btn btn-danger waves-effect waves-light "
                                            href="{{ url('project') }}">Back</a>
 
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Project Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="project_name" id="simpleinput"
                                                    placeholder="Project Name" class="form-control"
                                                    value="{{ $project->project_name }}">
                                                @error('project_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Registration Email </label>
                                                <input type="email" name="email" id="example-email" name="example-email"
                                                    class="form-control" placeholder="Email" value="{{ $project->email }}">
                                                {{-- @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror --}}
                                            </div>
                                        </div>

                                        {{-- <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">CC Email</label>
                                                <input type="email" name="project_cc_mail" id="project_cc_mail"
                                                    name="project_cc_mail" class="form-control" placeholder="Add CC Mail"
                                                    value="{{ $project->project_cc_mail }}" multiple> 
                                            </div>
                                        </div> --}}

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Registration Contact Number </label>
                                                <div class="d-flex">
                                                    <input type="text" placeholder="Country code"
                                                        name="contact_number[projectCCode]" id="contactNumberCode"
                                                        class="form-control" value="{{ $project->contant_country_code }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                    <input type="text" placeholder="Contact Number"
                                                        name="contact_number[main]" id="contactNumber" class="form-control"
                                                        value="{{ $project->contact_number }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        style="margin-left: 61px!important; z-index:1!important">
                                                </div>
                                                @error('contact_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Alternate Contact Number </label>
                                                <div class="d-flex">
                                                    <input type="tel" placeholder="Country code"
                                                        name="alternate_contact_number[AltCountryCode]"
                                                        id="alt_country_code" class="form-control"
                                                        value="{{ $project->alt_country_code }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                    <input type="tel" placeholder="Contact Number"
                                                        name="alternate_contact_number[altmain]"
                                                        id="alternate_contact_number" class="form-control"
                                                        value="{{ $project->alternate_contact_number }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        style="margin-left: 61px!important; z-index:1!important">
                                                </div>
                                                @error('alternate_contact_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Category <span
                                                        class="text-danger">*</span></label>
                                                <select class="selectpicker" data-style="btn-light" name="category"
                                                    id="category">
                                                    <option value="" selected>Select Category</option>
                                                    @foreach ($categorys as $item)
                                                        @if ($item->id == $project->project_category)
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->category_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="Sector">Area <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" name="sector" id="Sector"
                                                        class="form-control" placeholder="Enter your Sector"
                                                        value="{{ $project->sector }}">
                                                </div>
                                                @error('sector')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="location">Location <span class="text-danger">*</span></label>
                                                <select name="location" class="selectpicker" data-style="btn-light"
                                                    id="location" data-live-search="true">
                                                    @foreach ($locations as $locations)
                                                        @if ($locations->location == $project->location)
                                                            <option value="{{ $locations->location }}" selected>
                                                                {{ $locations->location }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $locations->location }}">
                                                                {{ $locations->location }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                @php
                                                    $selected = explode(',', $project->project_type);
                                                @endphp
                                                <label for="example-select">Project Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="project_type[]" class="selectpicker" data-style="btn-light"
                                                    id="project_type" multiple data-live-search="true">
                                                    @foreach ($projectTypes as $projectType)
                                                        <option value="{{ $projectType->project_type }}"
                                                            {{ in_array($projectType->project_type, $selected) ? 'selected' : '' }}>
                                                            {{ $projectType->project_type }}</option>
                                                        {{-- @if ($projectType->project_type == $project->project_type)
                                                            <option value="{{ $projectType->project_type }}" selected>
                                                                {{ $projectType->project_type }}</option>
                                                        @endif
                                                        <option value="{{ $projectType->project_type }}">
                                                            {{ $projectType->project_type }}</option> --}}
                                                    @endforeach
                                                </select>
                                                @error('project_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Rera Number</label>
                                                <input type="text" name="rera_number" class="form-control"
                                                    value="{{ $project->rera_number }}">
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            @php
                                                $builders = DB::table('name_of_developers')
                                                    ->orderBy('name_of_developer')
                                                    ->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select">Name of Developer </label>
                                                <select name="name_of_developer" id="name_of_developers"
                                                    class="selectpicker" data-style="btn-light"
                                                    onchange="isBuilderSelected(this);">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($builders as $builder)
                                                        @if ($project->name_of_developers == $builder->id)
                                                            <option value="{{ $builder->id }}" selected>
                                                                {{ $builder->name_of_developer }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $builder->id }}">
                                                                {{ $builder->name_of_developer }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">No. of Units</label>
                                                        <input type="number" name="total_no_of_units"
                                                            class="form-control"
                                                            value="{{ $project->total_no_of_units }}" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">No. of Occupied Units</label>
                                                        <input type="number" name="total_no_of_occupied_units"
                                                            class="form-control"
                                                            value="{{ $project->total_no_of_occupied_units }}"
                                                            min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">No. of UnOccupied Units</label>
                                                        <input type="number" name="total_no_of_unoccupied_units"
                                                            class="form-control"
                                                            value="{{ $project->total_no_of_unoccupied_units }}"
                                                            min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">Total Occupancy %</label>
                                                        <input type="text" name="total_occupancy" class="form-control"
                                                            value="{{ $project->total_occupancy }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Project Launch Date</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="date" id="datepicker" name="project_launch_date"
                                                        class="form-control" value={{ $project->project_launch_date }}>
                                                </div>
                                                @error('date_of_birth')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Project Completion Date</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="date" id="datepicker" name="project_completion_date"
                                                        class="form-control"
                                                        value={{ $project->project_completion_date }}>
                                                </div>
                                                @error('date_of_birth')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Project Website Link</label>
                                                <input type="text" name="project_website_link" class="form-control"
                                                    value="{{ $project->project_website_link }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Additonal Info Link</label>
                                                <input type="text" name="project_fb_group_link" class="form-control"
                                                    value="{{ $project->project_fb_group_link }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            @php
                                                $projectStatus = DB::table('project_status')->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select">Project Status</label>
                                                <select name="project_status" id="project_status" class="selectpicker"
                                                    data-style="btn-light">
                                                    <option value="" selected>Select Statuts</option>
                                                    @foreach ($projectStatus as $pStatus)
                                                        @if ($project->project_status_id == $pStatus->id)
                                                            <option value="{{ $pStatus->id }}" selected>
                                                                {{ $pStatus->status_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $pStatus->id }}">
                                                                {{ $pStatus->status_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('project_status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Size of Apartment</label>
                                                <input type="text" name="size_of_apartment" class="form-control"
                                                    value="{{ $project->size_of_apartment }}">
                                            </div>
                                        </div> 

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Price of Apartment</label>
                                                <textarea id="ckplot2" class="ckeditor" name="price_of_apartment">{{ $project->price_of_apartment }} </textarea>

                                                {{-- <input type="text" name="price_of_apartment" class="form-control"
                                                    value="{{ $project->price_of_apartment }}"> --}}
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="simpleinput">Project Plan Details</label>
                                                <textarea id="ckplot3" class="ckeditor" name="project_plan_details">
                                                    {{ $project->project_plan_details }} 
                                                </textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="contactNumber">Upload Document</label>
                                            <div class="form-group mb-3 d-flex">
                                                <div class="input-group input-group-merge">
                                                    <input name="project_image" type="file" />
                                                </div>
                                                <span class="">{{ $project->project_image }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button name="submit" value="submit" type="submit"
                                        class="  btn btn-primary waves-effect waves-light">
                                        Update</button>
                                    </div>
                                    
                            </div>

                        </div>
                    </div>
                </div>


            </div>

            <hr>

        </div>


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
    <script src="https://cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
    <script>
        CKEDITOR.replace(['ckplot2', 'ckplot3'], {
            toolbar: [{
                    name: 'document',
                    groups: ['mode', 'document', 'doctools'],
                    items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
                },
                // { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                // { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                // '/',
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-',
                        'RemoveFormat'
                    ]
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',
                        'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',
                        '-', 'BidiLtr', 'BidiRtl', 'Language'
                    ]
                },
                // // { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                // // { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                // '/',
                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                // { name: 'others', items: [ '-' ] },
                // { name: 'about', items: [ 'About' ] }
            ]
        });
    </script>
    <style>
      

        .iti {
            position: relative;
            display: block !important;
        }
    </style>
    <style>
        .bootstrap-select .dropdown-menu {
            max-height: 400px !important
        }

        .bootstrap-select .dropdown-menu .inner {
            max-height: 400px !important;
            overflow-y: auto !important
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

        .iti--allow-dropdown {
            width: 0px !important;
        }
    </style>
    <script>
        $('#name_of_developers').select2({
            // selectOnClose: true
        });
        $('#project_type').select2({
            placeholder: "Select Project",
            // selectOnClose: true
        });
        $('#location').select2({
            selectOnClose: true
        });

        $('#category').select2({
            placeholder: "Select",
            minimumResultsForSearch: Infinity

        });
        $('#project_status').select2({
            // placeholder: "Select Status",
            minimumResultsForSearch: Infinity

        });
        $('#designation').select2({
            // placeholder: "Select Designation",
            minimumResultsForSearch: Infinity
        });
        $('#builder').select2({
            // placeholder: "Select",
            minimumResultsForSearch: Infinity
        });
    </script>

    <script>
        var input = document.querySelector("#contactNumberCode"),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        var cCode = document.querySelector("#contactNumberCode");
        //alert(cCode.value);
        if (cCode.value == "") {
            cCode.value = "+91";
        }

        // initialise plugin
        var iti = window.intlTelInput(document.querySelector("#contactNumberCode"), {
            initialCountry: cCode.value,
            separateDialCode: true,
            hiddenInput: "projectCCode",
        });


        $("form").submit(function() {
            var ProjectGetCode = document.getElementsByClassName('iti__selected-dial-code')[0].innerHTML;
            //   alert(ProjectGetCode)  
            $("input[name='contact_number[projectCCode]'").val(ProjectGetCode);

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


        // alt_contact_number


        var input1 = document.querySelector("#alt_country_code"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin 
        var cCodeAlt = document.querySelector("#alt_country_code");
        if (cCodeAlt.value == "") {
            cCodeAlt.value = "+91";
        }

        var itialt = window.intlTelInput(document.querySelector("#alt_country_code"), {
            initialCountry: cCodeAlt.value,
            separateDialCode: true,
            hiddenInput: "AltCountryCode",
        });


        var my_div_relation = document.getElementsByClassName("iti__selected-dial-code");
        my_div_relation[1].setAttribute("id", "project_alt_code");

        $("form").submit(function() {

            var ProjectGetCodeAlt = document.getElementById('project_alt_code').innerHTML;

            $("input[name='alternate_contact_number[AltCountryCode]'").val(ProjectGetCodeAlt);
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
    </script>
@endsection

