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
                            <li class="breadcrumb-item active">Role Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Role Details</h4>
                    <div class="table-responsive">
                        @if (Session::has('delete'))
                        <div class="alert alert-danger alert-dismissible">
                            <h5>{{ Session::get('delete') }}</h5>
                        </div>
                    @endif
                        
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end my-2">
                            <a href="{{ route('role-index') }}">
                            <button class="btn btn-danger">Back</button>
                            </a>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12 table-responsive">
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
                                            {{-- <th style="width: 82px;">Action</th>  --}}
                                            </tr>
                                    </thead>
                                    <tbody>
                                         
                                        <tr> 
                                            <td>{{ $roleDetails->name }}</td>
                                                
                                            <td>
                                                @php
                                                    $RolePermissions = DB::table('permissions')
                                                        ->where('name', '!=', 'Create')
                                                        ->where('name', '!=', 'Update')
                                                        ->where('name', '!=', 'View')
                                                        ->where('name','!=', 'Employee Reports')
                                                        ->where('name','!=', 'Project Reports')
                                                        ->where('name','!=', 'Developer Reports')
                                                        ->where('name','!=','Deal Confirm Reports')
                                                        ->where('name','!=','Location Reports')
                                                        ->where('name','!=','Common Pool Reports')
                                                        ->where('name','!=','Builder Reports')
                                                        ->where('name','!=','Login Reports')
                                                        ->where('name', '!=', 'History-View')
                                                        ->where('name','!=', 'History-Update')
                                                        ->where('name', '!=', 'History-Update')
                                                        ->where('name', '!=', 'History-View')
                                                        ->where('name', '!=', 'Bulk Reports') 
                                                        ->get();
                                                    $roleDetails = App\Models\Role::find($roleDetails->id);
                                                @endphp

                                                @foreach ($RolePermissions as $permission)
                                                    <input class="bg-info" type="checkbox" name="role_access[]"
                                                        value="{{ $permission->name }}" onclick="return false;"
                                                        @if ($roleDetails->permissions->contains($permission->id)) checked  @else disabled @endif>

                                                    {{ $permission->name }}
                                                @endforeach

                                            </td>


                                            @php
                                                $roleDetails = App\Models\Role::find($roleDetails->id); 
                                            @endphp
                                            @php
                                                $crud = DB::table('permissions')
                                                    ->where('name', 'Create')
                                                    ->orWhere('name', 'Update')
                                                    ->orWhere('name', 'View')
                                                    ->orWhere('name','Project Reports')
                                                    ->orWhere('name','Lead Reports')
                                                    ->orWhere('name','Employee Reports')
                                                    ->orWhere('name','Developer Reports')
                                                    ->orWhere('name','Employee Reports')
                                                    ->orWhere('name','Project Reports')
                                                    ->orWhere('name','Deal Confirm Reports')
                                                    ->orWhere('name','Location Reports')
                                                    ->orWhere('name','Login Reports')
                                                    ->orWhere('name','Common Pool Reports')
                                                    ->orWhere('name','Builder Reports') 
                                                    ->orWhere('name', 'History-Update')
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

                                            <td>{{ $roleDetails->created_at }}</td>

                                            @php
                                                $createBYName = DB::table('users')
                                                    ->where('id', $roleDetails->created_by)
                                                    ->first();
                                                
                                                $updatedBYName = DB::table('users')
                                                    ->where('id', $roleDetails->updated_by)
                                                    ->first();
                                                
                                            @endphp

                                            @if ($createBYName == null)
                                                <td></td>
                                            @else
                                                <td>{{ $createBYName->name }}</td>
                                            @endif


                                            <td>{{ $roleDetails->updated_at }}</td>

                                            @if ($updatedBYName == null)
                                                <td></td>
                                            @else
                                                <td>{{ $updatedBYName->name }}</td>
                                            @endif
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card--> 
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row -->

    </div> <!-- container -->
@endsection


