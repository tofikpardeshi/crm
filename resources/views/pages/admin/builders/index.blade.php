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
                            <li class="breadcrumb-item active">Builder</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Builder</h4>
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
                                {{-- <form class="form-inline" method="get" 
                                action="{{ url('search-employee') }}">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" name="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form> --}}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                        href="{{ route('create-builder-view') }}">Add New</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                @if (Session::has('delete'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('delete') }}</h5>
                                    </div>
                                @endif
                            @if (session()->has('NoSearch'))
                                <div class="alert alert-danger text-center">
                                    {{ session()->get('NoSearch') }} </div>
                            @endif
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Builder Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Project Assign</th>
                                        <th>Designation</th>
                                        {{-- <th>Created Date</th> --}}
                                        <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($builders as $builder)
                                    <tr>
                                        <td>{{ $builder->team_name }}</td>
                                        <td>{{ $builder->team_email }}</td>
                                        
                                        <td>
                                            <a class="text-muted" href="tel:{{ $builder->team_phone_number }}" >
                                                {{ $builder->team_phone_number }}
                                            </a>
                                        </td>
                                        <td>{{ $builder->project_name }}</td>
                                        <td>{{ $builder->designation_name }}</td>
                                        <td>
                                            <a href="{{ url('edit-builder/'.$builder->id) }}" class="action-icon"> 
                                                <i class="mdi mdi-square-edit-outline">
                                                    </i></a>
                                                    
                                             <a  
                                             href="{{ url('builder-delete/'.$builder->id) }}" class="action-icon"> 
                                                <i class="mdi mdi-delete"></i>
                                            </a> 
                                        </td>
                                     </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>

                        <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $builders->links('pagination::bootstrap-4'); }}
                        </ul>  

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
