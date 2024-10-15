@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Bulk upload</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Bulk Upload</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                        href="{{ route('bulk-upload') }}">Back</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="row mt-5">

                            <div class="col-md-12 col-xl-12">

                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('add-location') }}">
                                    @csrf
                                    <div class="row py-4">
                                        <div class="col-lg-4 overflow-auto">
                                            <label for="example-select">Assign Employee <span
                                                    class="text-danger">*</span></label>
                                            <select name="assign_employee" class="selectpicker " data-style="btn-light">
                                                @foreach ($employees as $employee)
                                                <option value="{{ $employee->employee_name }}">{{ $employee->employee_name }}</option>
                                                @endforeach 
                                            </select>
                                            @error('location')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="simpleinput">Lead Generate
                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="bulk_upload" id="simpleinput" class="form-control">
                                            @error('bulk_upload')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <button name="submit" value="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light mt-3  btn-block">Bulk
                                                upload</button>
                                        </div>

                                    </div>
                                </form>

                            </div> <!-- end col -->
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->


    </div>
    <!-- end row -->

    </div> <!-- container -->
@endsection
