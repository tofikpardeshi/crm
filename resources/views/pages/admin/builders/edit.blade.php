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
                                <form method="post" action="{{ route('update-builder',$BuilderDetails->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Name" name="team_name"
                                                        class="form-control" 
                                                        value="{{ $BuilderDetails->team_name }}">
                                                </div>
                                                @error('team_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Email <span class="text-danger">*</span> </label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" placeholder="Sales Person Email" name="team_email"
                                                        class="form-control" 
                                                        value="{{ $BuilderDetails->team_email }}">
                                                </div>
                                                @error('team_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contactNumber">Sales Person Contact Number <span class="text-danger">*</span></label>
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
                                                <label for="example-email">Project Assign</label>
                                                <select class="selectpicker" data-style="btn-light"
                                                    name="project">
                                                    <option>Select Project</option>
                                                    @foreach ($projects as $item)
                                                    @if ($item->id == $BuilderDetails->project_id)
                                                    <option value="{{ $item->id }}" selected>
                                                        {{ $item->project_name }}
                                                    </option> 
                                                    @else
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->project_name }}
                                                    </option> 
                                                    @endif 
                                                    @endforeach 
                                                </select>
                                               
                                            </div>
                                            @error('project')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        </div>

                                        <div class="col-md-4"> 
                                            <div class="form-group mb-3">
                                                <label for="example-email">Designation</label>
                                                <select class="selectpicker" data-style="btn-light"
                                                    name="designation">
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
    <script></script>
@endsection
