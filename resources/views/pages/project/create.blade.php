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
                    <h4 class="page-title">Create Project</h4>
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
                                    <div class="alert alert-success alert-dismissible d-flex">
                                        <h5>{{ Session::get('success') }}</h5>

                                        <a href="{{ url('project-history/' . encrypt(\Session::get('projectID'))) }}">
                                            <button class="btn btn-success mx-1">Goto Details Page</button>
                                        </a>
                                    </div>
                                @endif


                                <form method="post" action="{{ route('project-create') }}" enctype="multipart/form-data">
                                    @csrf 
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Project Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="project_name" id="simpleinput"
                                                    placeholder="Project Name" class="form-control"
                                                    value="{{ old('project_name') }}">
                                                @error('project_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Registration Email
                                                </label>
                                                <input type="email" name="email" id="example-email" class="form-control"
                                                    placeholder="Email" value="{{ old('email') }}">
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        {{-- <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-email">CC Email</label>
                                                <input type="email" name="project_cc_mail" id="project_cc_mail" name="project_cc_mail"
                                                    class="form-control" placeholder="Add CC Mail" 
                                                    value="{{ old('project_cc_mail') }}" multiple> 
                                            </div>
                                        </div> --}}
                                        

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Registration Contact Number </label>
                                                <div class="input-group input-group-merge">
                                                    <input id="number" type="tel" placeholder="Contact Number" name="contact_number[main]" class="form-control" value="{{ old('contact_number.main') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        pattern="[1-9]{1}[0-9]{9}" >
                                                </div>
                                                @error('contact_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Alternate Contact Number </label>
                                                <div class="input-group input-group-merge">
                                                    <input id="alternate_contact_number" type="tel" placeholder="Contact Number"
                                                        name="alternate_contact_number[altmain]" class="form-control"
                                                        value="{{ old('alternate_contact_number.altmain') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        pattern="[1-9]{1}[0-9]{9}">
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
                                                        <option value="{{ $item->id }}"
                                                            {{ collect(old('category'))->contains($item->id) ? 'selected' : '' }}>
                                                            {{ $item->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="Sector">Area<span class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" name="sector" id="Sector" class="form-control"
                                                        placeholder="Enter your Sector" value="{{ old('sector') }}">
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
                                                    <option value="" selected>Select Project</option>
                                                    @foreach ($locations as $locations)
                                                        <option value="{{ $locations->location }}"
                                                            {{ collect(old('location'))->contains($locations->location) ? 'selected' : '' }}>
                                                            {{ $locations->location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('location')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Project Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="project_type[]" id="project_type" class="selectpicker"
                                                    data-style="btn-light" multiple data-live-search="true">
                                                    @foreach ($projectTypes as $projectType)
                                                        <option value="{{ $projectType->project_type }}"
                                                            {{ collect(old('project_type'))->contains($projectType->project_type) ? 'selected' : '' }}>
                                                            {{ $projectType->project_type }}</option>
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
                                                <input type="text" name="rera_number"  placeholder="Rera Number" class="form-control"
                                                    value="{{ old('rera_number') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            @php
                                                $builders = DB::table('name_of_developers')
                                                    ->orderBy('name_of_developer','ASC')
                                                    ->latest()
                                                    ->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-select">Name of Developer </label>
                                                <select name="name_of_developer" class="selectpicker" id="name_of_developers"
                                                    data-style="btn-light" >
                                                    <option value="" selected>Select</option>
                                                    @foreach ($builders as $builder)
                                                        <option value="{{ $builder->id }}"
                                                            {{ collect(old('name_of_developer'))->contains($builder->id) ? 'selected' : '' }}>
                                                            {{ $builder->name_of_developer }}</option>
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
                                                            class="form-control" placeholder="No. of Units" value="{{ old('total_no_of_units') }}"
                                                            min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">No. of Occupied Units</label>
                                                        <input type="number" placeholder="No. of Occupied Units" name="total_no_of_occupied_units"
                                                            class="form-control"
                                                            value="{{ old('total_no_of_occupied_units') }}"
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
                                                        <input type="number" placeholder="No. of UnOccupied Units" name="total_no_of_unoccupied_units"
                                                            class="form-control"
                                                            value="{{ old('total_no_of_unoccupied_units') }}"
                                                            min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="simpleinput">Total Occupancy %</label>
                                                        <input type="text" name="total_occupancy" placeholder="Total Occupancy %" class="form-control"
                                                            value="{{ old('total_occupancy') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Project Launch Date</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="date" id="datepicker" name="project_launch_date"
                                                        class="form-control" value={{ old('project_launch_date') }}>
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
                                                        class="form-control" value={{ old('project_completion_date') }}>
                                                </div>
                                                @error('date_of_birth')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Project Website Link</label>
                                                <input type="text" name="project_website_link" placeholder="Project Website Link" class="form-control"
                                                    value="{{ old('project_website_link') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Additonal Info Link</label>
                                                <input type="text" name="project_fb_group_link" placeholder="Project FB Group Link" class="form-control"
                                                    value="{{ old('project_fb_group_link') }}">
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
                                                        <option value="{{ $pStatus->id }}"
                                                            {{ collect(old('project_status'))->contains($pStatus->id) ? 'selected' : '' }}>
                                                            {{ $pStatus->status_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('project_status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="simpleinput">Size of Apartment</label>
                                                <input type="text" name="size_of_apartment" class="form-control" placeholder="60x90"
                                                    value="{{ old('size_of_apartment') }}">
                                            </div>
                                        </div>

                                         <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                               
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                              
                                            </div>
                                        </div>
                                       
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="simpleinput">Price of Apartment</label>
                                                    {{-- <input type="text" class="ckeditor"  name="price_of_apartment" value="{{ old('price_of_apartment') }}"> --}}
                                                    <textarea id="ckplot2" class="ckeditor"  name="price_of_apartment" >{{ old('price_of_apartment') }} </textarea>
                                                    {{-- <input type="text" name="price_of_apartment" class="form-control"
                                                        value="{{ old('price_of_apartment') }}"> --}}
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="simpleinput">Project Plan Details</label>
                                                    {{-- <input type="text" class="ckeditor"  name="price_of_apartment" value="{{ old('price_of_apartment') }}"> --}}
                                                    <textarea id="ckplot3" class="ckeditor"  name="project_plan_details" >{{ old('project_plan_details') }} </textarea>
                                                    {{-- <input type="text" name="price_of_apartment" class="form-control"
                                                        value="{{ old('price_of_apartment') }}"> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                    {{-- <label for="contactNumber">Project Logo</label>
                                                    <div class="input-group input-group-merge">
                                                        <input name="project_image" type="file" />
                                                    </div> --}}
                                                    <label for="contactNumber">Upload Document</label>
                                                    <div class="input-group input-group-merge">
                                                        <input name="project_image" type="file" />
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                   
                                                </div>
                                            </div>


                                        {{-- <div class="col-lg-4">
                                            <div class="form-group mb-3">
                                        <div class=" d-flex justify-content-end">
                                            <!-- mr-1 btn-darkblue  -->
                                            <button name="submit" value="submit" type="submit"
                                                class="m2-4 btn btn-primary waves-effect waves-light mr-1 btn-darkblue ">Add
                                                Project</button>
                                        </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                     
                            </div>

                            <hr>
                        </div>
                        <div class=" d-flex justify-content-end">
                                        <button name="submit" value="submit" type="submit"
                                            class="m2-4 btn btn-primary waves-effect waves-light mr-1 btn-darkblue ">Add
                                            Project</button>
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
<style>
    .iti__flag{
                display: none;
            }
            .iti {
    position: relative;
    display: block !important;
}
</style>
    <style>
        .bootstrap-select .dropdown-menu{max-height: 400px !important}
    .bootstrap-select .dropdown-menu .inner{max-height: 400px !important; overflow-y: auto !important}
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
     <script src="https://cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
     {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
     <script>
        CKEDITOR.replace( 'ckplot2', {
            toolbar: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
            // { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
            // { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            // '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            // // { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            // // { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
            // '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            // { name: 'others', items: [ '-' ] },
            // { name: 'about', items: [ 'About' ] }
        ]
        });

        CKEDITOR.replace( 'ckplot3', {
            toolbar: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
            // { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            // { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
            // { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            // '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            // // { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            // // { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
            // '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            // { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            // { name: 'others', items: [ '-' ] },
            // { name: 'about', items: [ 'About' ] }
        ]
        
        });
     </script>
    <script>
        $('#name_of_developers').select2({
            //placeholder: "Select",
            // selectOnClose: true

        }); 
        $('#category').select2({
            placeholder: "Select",
            minimumResultsForSearch: Infinity

        });
        $('#project_status').select2({
            placeholder: "Select Status",
            minimumResultsForSearch: Infinity

        });
        
        $('#project_type').select2({
            placeholder: "Select Project",
            // selectOnClose: true
        });
        $('#builder').select2({
            placeholder: "Select",
            minimumResultsForSearch: Infinity
        });
        $('#designation').select2({
            placeholder: "Select Designation",
            minimumResultsForSearch: Infinity
        });
        
        $('#location').select2({
            // selectOnClose: true
            placeholder: "Select Location",
        });
        // $('#project_status').select2({
        //     placeholder: "Select Status",
        //     // selectOnClose: true
        // });

        // function isBuilderSelected(that) { 
        //     if (that.value != "") {
        //         document.getElementById("isBuilderExist").style.display = "none";
        //     } else {
        //         document.getElementById("isBuilderExist").style.display = "block";
        //     }

        // }
 
    </script>

    <script>
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



        // alt_contact_number


        var input1 = document.querySelector("#alternate_contact_number"),
            errorMsg1 = document.querySelector("#error-msg-1"),
            validMsg1 = document.querySelector("#valid-msg-1");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["Invalid number, enter correct number.", "Invalid country code", "Enter correct number.",
            "Too long", "Invalid number, enter correct number."
        ];

        // initialise plugin
        // initialise plugin
        var iti1 = window.intlTelInput(document.querySelector("#alternate_contact_number"), {
            initialCountry: "In",
            separateDialCode: true,
            hiddenInput: "altfull",
        });

        $("form").submit(function() {
            var full_number1 = iti1.getNumber(intlTelInputUtils.numberFormat.E164);
            $("input[name='alternate_contact_number[altfull]'").val(full_number1);
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



