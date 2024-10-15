@extends('main')




@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('leads') }}">Home</a></li>
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
                                @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                                @endif 

                                <form method="post" action="{{ route('toolbar-create') }}" >
                                    @csrf 
                                    <div class="row"> 
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-select"> Project<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="project_type" id="example-project_type" class="form-control"
                                                        placeholder="project_type" value="{{ old('project_type') }}"
                                                        autocomplete="off" required>
                                                {{-- <select name="project_type" id="projects" 
                                                class="selectpicker" data-style="btn-light"
                                                autocomplete="off">
                                                <option value="" selected>Select</option>
                                                    @foreach ($projectTypes as $projectType)
                                                        <option value="{{ $projectType->id }}"
                                                            {{ collect(old('project_type'))->contains($projectType->project_name) ? 'selected' : '' }}>
                                                            {{ $projectType->project_name }}</option>
                                                    @endforeach
                                                </select> --}}
                                                @error('project_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div> 
 

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-email">Website<span
                                                    class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="website" id="example-website" class="form-control"
                                                    placeholder="Website" value="{{ old('website') }}"
                                                    autocomplete="off"
                                                    >
                                                @error('website')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div> 


                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="example-email">User Name<span
                                                    class="text-danger"></span>
                                                </label>
                                                <input type="text" name="email" id="example-email" class="form-control"
                                                    placeholder="User Name" value="{{ old('email') }}"
                                                    autocomplete="off"
                                                    >
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="password">Password <span class="text-danger"></span></label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" name="password" id="password"
                                                        class="form-control" placeholder="Enter your password"
                                                        value="{{ old('password') }}"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                                                        >
                                                    <div class="input-group-append" data-password="false">
                                                        <div class="input-group-text">
                                                            <span class="password-eye"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div> 

                                    </div>  
                                    <button name="submit" type="submit" class=" mt-2 btn btn-primary"
                                        value="submit">Submit</button>
                                </form>
                            </div>
                        </div>
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
    <script>
        $('#projects').select2({
            placeholder: "Select Project",
            // selectOnClose: true
        });  
        <script>  
@endsection

