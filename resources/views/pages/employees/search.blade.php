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
                            <li class="breadcrumb-item active">Employees</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Employees</h4>
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
                                action="{{ url('search-lead') }}">
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
                                        href="{{ url('employees') }}">Back</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Employees Role</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="table-user">

                                                @if (File::exists($employee->emplayees_photo))
                                                    <img src="{{ $employee->emplayees_photo }}" alt="table-user"
                                                        class="mr-2 rounded-circle" controls preload="none" />
                                                @else
                                                    <img src="{{ url('') }}/assets/images/users/no.png"
                                                        alt="table-user" class="mr-2 rounded-circle" controls
                                                        preload="none" />
                                                @endif

                                                <a href="javascript:void(0);"
                                                    class="text-body font-weight-semibold">{{ $employee->employee_name }}</a>
                                            </td>
                                            <td>
                                                <a class="text-muted" href="tel:{{ $employee->personal_phone_number	 }}" >{{ $employee->personal_phone_number	 }}</a> 
                                            </td>
                                            <td>
                                                {{ $employee->email }}
                                            </td>
                                            <td>
                                                {{ $employee->name }}
                                            </td>
                                            @if ($employee->leaving_date == null)
                                            <td>Active</td>
                                            @else
                                                <td>
                                                    @if (\Carbon\Carbon::now()->diffInSeconds($employee->leaving_date, false) > 0)
                                                          {{ "Active" }}
                                                    @else
                                                        <div class="text-danger">
                                                            {{ "Inactive" }}
                                                        </div>
                                                    @endif

                                                </td>
                                            @endif
                                            
                                            <td>
                                                {{ $employee->created_at }}
                                            </td>
                                            <td>
                                                <a href="{{ url('/edit-employee/'.$employee->id) }}" class="action-icon"> 
                                                    <i class="mdi mdi-square-edit-outline">
                                                        </i></a>
                                                        
                                                 <a  
                                                 href="{{ url('employee-export',$employee->id) }}" class="action-icon"> 
                                                    <i class="mdi mdi-download"></i>
                                                </a> 
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{-- {{ $employees->links('pagination::bootstrap-4'); }} --}}
                        </ul>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
