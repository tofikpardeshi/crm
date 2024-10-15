@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Homents</a></li>
                            <li class="breadcrumb-item active">Location</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Location</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card-box">
                    @if (Session::has('location'))
                    <div class="alert alert-success alert-dismissible text-center">
                        <h5>{{ Session::get('location') }}</h5>
                    </div>
                @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible text-center">
                            <h5>{{ Session::get('success') }}</h5>
                        </div>
                    @endif
                    <form method="post" action="{{ route('add-location') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <label for="simpleinput">Location
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="simpleinput" class="form-control">
                                @error('location')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <button name="submit" value="submit" type="submit"
                                    class="btn btn-primary waves-effect waves-light mt-3">Add
                                    Location</button>
                            </div>

                        </div>
                    </form>



                </div> <!-- end card-box-->
            </div> <!-- end col -->
      
            <div class="col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="card-box">
                @can('Location Reports')
                        <div class="d-flex justify-content-end">
                            <a href="{{url('download-excel')}}"  class="btn btn-info waves-effect waves-light mt-3">
                                Location Reports 
                            </a>
                        </div>  
                    @endcan 
                    <div class="table-responsive mt-3">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Location Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locations as $location)
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal-{{ $location->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    {{-- <h5 class="modal-title" id="exampleModalLabel">Update Status</h5> --}}
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <form method="post"
                                                                action="{{ url('location-update') }}">
                                                                @csrf


                                                                <input type="hidden" name="updatelocationID"
                                                                    value="{{ $location->id }}">

                                                                <div class="form-group mb-3">

                                                                    <label>Update Location</label>
                                                                    <input type="text" name="locationUpdate" 
                                                                    class="form-control"
                                                                    value="{{ $location->location }}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button name="submit" value="submit" type="submit"
                                                                        class="btn btn-primary waves-effect waves-light">Update Location</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <tr>
                                        <td>{{ $location->location }}</td>
                                        <td>
                                            <a href="{{ url('employee-assing-comoon-poll/' . $location->id) }}"
                                                class="action-icon" data-toggle="modal"
                                                value="{{ $location->id }}"
                                                data-target="#exampleModal-{{ $location->id }}">
                                                <i class="mdi mdi-square-edit-outline">
                                                </i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $locations->links('pagination::bootstrap-4'); }}
                    </ul> --}}
                </div>
            </div>
        </div>


    </div> <!-- container -->
@endsection
