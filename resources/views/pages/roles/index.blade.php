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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Homents</a></li>
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Role</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible text-center">
                        <h5>{{ Session::get('success') }}</h5>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                {{-- <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form> --}}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                    @can('Create')
                                        <a type="button" class="btn btn-danger waves-effect waves-light mb-2 btn-darkblue"
                                            href="{{ route('roles_create') }}">Add Role</a>
                                    @endcan


                                </div>
                            </div><!-- end col-->


                        </div>

                        <div class="table-responsive">
                            @if (Session::has('delete'))
                                <div class="alert alert-danger alert-dismissible">
                                    <h5>{{ Session::get('delete') }}</h5>
                                </div>
                            @endif
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Role Access</th>
                                        <th>Permission</th>
                                        <th>Created Date</th>
                                        <th>Created By</th>
                                        <th>Last Updated Date</th>
                                        <th>Updated By</th>
                                        <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>

                                            <td>{{ $role->name }}</td>

                                            <td class=" justify-content-between">
                                                @php
                                                    $RolePermissions = DB::table('permissions')
                                                        ->where('name', '!=', 'Create')
                                                        ->where('name', '!=', 'Update')
                                                        ->where('name', '!=', 'View')
                                                        ->where('name', '!=', 'History-Update')
                                                        ->where('name', '!=', 'Lead Reports')
                                                        ->where('name','!=','Employee Leads Reports') 
                                                        ->where('name', '!=', 'Employee Reports')
                                                        ->where('name', '!=', 'Project Reports')
                                                        ->where('name','!=','Developer Reports')
                                                        ->where('name','!=','Deal Confirm Reports')
                                                        ->where('name','!=','Location Reports')
                                                        ->where('name','!=','Common Pool Reports')
                                                        ->where('name','!=','Builder Reports')
                                                        ->where('name','!=','Login Reports')
                                                        ->where('name', '!=', 'History-View')
                                                        ->where('name', '!=', 'Bulk Reports')
                                                        ->get();
                                                    $roleDetails = App\Models\Role::find($role->id);
                                                @endphp
                                                    @foreach ($RolePermissions as $key => $permission)
                                                        <input class="bg-info" type="checkbox" name="role_access[]"
                                                            value="{{ $permission->name }}" onclick="return false;"
                                                            @if ($roleDetails->permissions->contains($permission->id)) checked  @else disabled @endif>

                                                        {{ $permission->name }}  

                                                        @if ($key == 4)
                                                            <div>
                                                                <br>
                                                            </div>
                                                            
                                                        @else
                                                            
                                                        @endif
                                                
                                                    @endforeach
                                                 

                                    </td>

                                    @php
                                        $roleDetails = App\Models\Role::find($role->id);
                                        $crud = DB::table('permissions')
                                            ->where('name', 'Create')
                                            ->orWhere('name', 'Update')
                                            ->orWhere('name', 'View')
                                            ->orWhere('name', 'History-Update')
                                            ->orWhere('name','Lead Reports')
                                            ->orWhere('name','Employee Leads Reports') 
                                            ->orWhere('name','Employee Reports')
                                            ->orWhere('name','Project Reports')
                                            ->orWhere('name','Developer Reports')
                                            ->orWhere('name','Deal Confirm Reports')
                                            ->orWhere('name','Location Reports')
                                            ->orWhere('name','Common Pool Reports')
                                            ->orWhere('name','Builder Reports')
                                            ->orWhere('name','Login Reports')
                                            ->orWhere('name', 'History-View')
                                            ->orWhere('name', 'Bulk Reports') 
                                            ->get();
                                    @endphp
                                    <td>
                                        @foreach ($crud as $permission)
                                            <input class="bg-info" type="checkbox" name="permission[]"
                                                value="{{ $permission->name }}" onclick="return false;"
                                                @if ($roleDetails->permissions->contains($permission->id)) checked  @else disabled @endif>

                                            {{ $permission->name }} 
                                        @endforeach

                                    </td>

                                    <td>{{ $role->created_at }}</td>

                                    @php
                                        $createBYName = DB::table('users')
                                            ->where('id', $role->created_by)
                                            ->first();
                                        
                                        $updatedBYName = DB::table('users')
                                            ->where('id', $role->updated_by)
                                            ->first();
                                        
                                    @endphp

                                    @if ($createBYName == null)
                                        <td></td>
                                    @else
                                        <td>{{ $createBYName->name }}</td>
                                    @endif


                                    <td>{{ $role->updated_at }}</td>

                                    @if ($updatedBYName == null)
                                        <td></td>
                                    @else
                                        <td>{{ $updatedBYName->name }}</td>
                                    @endif


                                    <td>
                                        @can('View')
                                            <a href="{{ url('role-details/' . $role->id) }}">
                                                <button
                                                    class=" waves-effect waves-light   font-weight-semibold btn btn-primary text-white"
                                                    data-toggle="modal" data-target="#custom-modal">Details
                                                </button>
                                            </a>
                                        @endcan

                                        @can('Update')
                                            <a href="{{ url('edit-role/' . $role->id) }}" class="action-icon"> <i
                                                    class="mdi mdi-square-edit-outline text-info"></i></a>
                                        @endcan
                                        {{-- <a href="{{ url('role-delete/'.$role->id) }}"
                                                
                                                class="action-icon"> <i
                                                    class="mdi mdi-delete text-danger"></i></a> --}}
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $roles->links('pagination::bootstrap-4') }}
                        </ul> --}}
                    </div> <!-- end card-body-->

                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection


