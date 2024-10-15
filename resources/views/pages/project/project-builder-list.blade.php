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
                            <li class="breadcrumb-item active">{{ $project->project_name }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $project->location }} > {{ $project->project_name }} </h4>
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
                                             
                                    @can('Create')
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2 mr-1 btn-darkblue "
                                        href="{{ route('create-builder-view') }}">Add New</a>
                                        
                                        <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                            href="{{ url('project') }}">Back</a>
                                    @endcan

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
                                        <th>Alt Contact Number</th>
                                        <th>Builder/CP/Individual</th>
                                        <th>Name of Developer</th>
                                        <th>Project Assign</th>
                                        <th>Designation</th>
                                        {{-- <th>Created Date</th> --}}
                                        <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buildersNameProjectWise as $builder)
                                        @php 
                                           $BuilderValue = DB::table('teams') 
                                                    ->join('builders', 'teams.builder_id', '=', 'builders.id')
                                                    ->where('builders.id', $builder->builder_id)
                                                    ->select('builders.name')
                                                    ->first();

                                                    // dd($BuilderValue);
                                                
                                                $nameofDeveloper = DB::table('teams')
                                                    ->join('name_of_developers', 'teams.name_of_developer', '=', 'name_of_developers.id')
                                                    ->where('name_of_developers.id', $builder->name_of_developer)
                                                    ->select('name_of_developers.name_of_developer')
                                                    ->first();
                                                
                                           
                                         $selected = explode(',', $builder->project_id);
                                         
                                        // dd($Project);
                                        @endphp
                                        <tr>
                                            
                                            <td>
                                                {{-- {{ in_array($builder->project_id, $selected) ?  $builder->team_name : $builder->team_name }} --}}
                                                {{ $builder->team_name }} 
                                            </td>
                                            <td>{{ $builder->team_email }}</td>

                                            <td>
                                                <a class="text-muted" href="tel:{{ $builder->team_phone_number }}">
                                                    {{-- {{ $builder->team_phone_number }} --}}
                                                    {{
                                                    Auth::user()->roles_id == 1 ? $builder->team_phone_number : substr_replace($builder->team_phone_number, '******', 0, 6)  }}
                                                </a>
                                            </td> 

                                            <td>
                                                @if ($builder->alternate_contact_number_team == null)
                                                   {{ "N/A"}}
                                                @else
                                                <a class="text-muted" href="tel:{{ $builder->alternate_contact_number_team }}">
                                                    {{-- {{ $builder->alternate_contact_number_team }} --}}
                                                    {{
                                                    Auth::user()->roles_id == 1 ? $builder->alternate_contact_number_team : substr_replace($builder->alternate_contact_number_team, '******', 0, 6)  }}
                                                </a>
                                                @endif
                                                
                                            </td> 
                                            @if ($BuilderValue == null)
                                                <td>N/A</td>
                                            @else
                                                <td>{{ $BuilderValue->name }}</td>
                                            @endif

                                            @if ($nameofDeveloper == null)
                                                <td>N/A</td>
                                            @else
                                                <td>{{ $nameofDeveloper->name_of_developer }}</td>
                                            @endif
                                            <td>{{ $project->project_name }}</td>

                                            <td>{{ $builder->designation_name }}</td>

                                            <td>
                                                @can('Update')
                                                    <a href="{{ url('edit-builder/' . $builder->id) }}" class="action-icon">
                                                        <i class="mdi mdi-square-edit-outline">
                                                        </i></a>
                                                @endcan


                                                {{-- <a  
                                             href="{{ url('builder-delete/'.$builder->id) }}" class="action-icon"> 
                                                <i class="mdi mdi-delete"></i>
                                            </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $buildersNameProjectWise->links('pagination::bootstrap-4'); }}
                        </ul> --}}

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

