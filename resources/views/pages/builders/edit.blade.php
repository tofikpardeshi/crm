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
                    <h4 class="page-title">Edit Builder</h4>
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
                                href="{{ url('builder') }}">Back</a>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('update-builder', $BuilderDetails->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Name" name="team_name"
                                                        class="form-control" value="{{ $BuilderDetails->team_name }}">
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
                                                        value="{{ $BuilderDetails->team_phone_number }}"
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
                                                        value="{{ $BuilderDetails->alternate_contact_number_team }}">
                                                </div>
                                                @error('alternate_contact_number_team')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">
                                                    Sales Person Official Email
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" name="team_email" class="form-control"
                                                        value="{{ $BuilderDetails->team_email }}">
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
                                                    <input type="text" name="saler_person_alt_email" class="form-control"
                                                        value={{ $BuilderDetails->saler_person_alt_email }}>
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
                                                        value={{ $BuilderDetails->builder_cc_mail }} 
                                                        >
                                                </div> 
                                            </div>
                                        </div>  --}}

                                        <div class="col-md-4">
                                            @php
                                                $builders = DB::table('name_of_developers')->get();
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-email">Name of Developer </label>
                                                <select name="name_of_developer" id="name_of_developers"
                                                    data-style="btn-light" 
                                                    {{-- onchange="isBuilderSelected(this);" --}}
                                                    class="selectpicker">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($builders as $builder)
                                                        @if ($builder->id == $BuilderDetails->name_of_developer)
                                                            <option value="{{ $builder->id }}" selected>
                                                                {{ $builder->name_of_developer }}</option>
                                                        @else
                                                            <option value="{{ $builder->id }}">
                                                                {{ $builder->name_of_developer }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('name_of_developer')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            @php

                                                $selected = explode(',', $BuilderDetails->project_id);
                                                // dd($BuilderDetails->project_id);
                                                $DeveloperWiseProject = DB::table('projects')
                                                    ->where('name_of_developers', $BuilderDetails->name_of_developer)
                                                    ->get();
                                                
                                                //   dd($DeveloperWiseProject);
                                                
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="example-email">Project Assign </label>
                                                <select class="selectpicker" data-style="btn-light" name="project[]"
                                                    id="assignProject" multiple>
                                                    
                                                    @foreach ($projects as $item)
                                                        @if (in_array($item->id, $selected))
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->project_name }}</option>
                                                        @else  
                                                        @if ($item->name_of_developers == $BuilderDetails->name_of_developer)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->project_name }}</option>
                                                        @else
                                                            
                                                        @endif 
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
                                                <label for="example-email">Designation</label>
                                                <select class="selectpicker" data-style="btn-light" name="designation"
                                                id="designation">
                                                    <option>Select Designation</option>
                                                    @foreach ($designations as $item)
                                                        @if ($item->id == $BuilderDetails->designation)
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->designation_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->designation_name }}
                                                            </option>
                                                        @endif
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
                                                        value="{{ $BuilderDetails->date_of_birth }}">

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
                                                        value="{{ $BuilderDetails->wedding_anniversary }}">
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
                                                <select class="selectpicker" data-style="btn-light" name="builder"
                                                id="builder">
                                                    <option value="" selected>Select</option>
                                                    @foreach ($builders as $item)
                                                        @if ($item->id == $BuilderDetails->builder_id)
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-lg-12">
                                            <div class="form-group mb-3">
                                                <label for="example-select">Remark</span>
                                                </label>
                                                <textarea class="form-control" name="remark" id="exampleFormControlTextarea1" rows="4">{{ $BuilderDetails->remark }}</textarea>
                                                
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <hr>
                        </div>


                        <button name="submit" value="submit" type="submit"
                            class="mt-4 btn btn-primary waves-effect waves-light">
                            Update Builder
                        </button>

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
            placeholder: 'Select',
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
                // alert(categoryID);
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
                                        '<option value="' + value.id +
                                        '" class="selectpicker">' + value
                                        .project_name + '</option>');

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
    </script>
@endsection

