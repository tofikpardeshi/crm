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
                            <li class="breadcrumb-item active">Update Role</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Update Role</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('role-index') }}">
                                        <button class="btn btn-danger">Back</button>
                                    </a>
                                </div>
                                <form action="{{ route('update-role', $role->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if (Session::has('success'))
                                                <div class="alert alert-success alert-dismissible">
                                                    <h5>{{ Session::get('success') }}</h5>
                                                </div>
                                            @endif
                                            <label for="example-select">Role Name <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ $role->name }}" name="addRole"
                                                class="form-control" placeholder="Add Role">
                                            @error('addRole')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <br>
                                            <label for="example-select mt-2">Role Access <span
                                                    class="text-danger">*</span></label>

                                            @php
                                                $RolePermissions = DB::table('permissions')
                                                    ->where('name', '!=', 'Create')
                                                    ->where('name', '!=', 'Update')
                                                    ->where('name','!=','Lead Reports')
                                                    ->where('name','!=','Employee Leads Reports') 
                                                    ->where('name','!=','Employee Reports')
                                                    ->where('name','!=','Project Reports')
                                                    ->where('name', '!=', 'View')
                                                    ->where('name','!=','Developer Reports')
                                                    ->where('name','!=','Deal Confirm Reports')
                                                    ->where('name','!=','Location Reports')
                                                    ->where('name','!=','Common Pool Reports')
                                                    ->where('name','!=','Builder Reports')
                                                    ->where('name','!=','Login Reports')
                                                    ->where('name','!=','Trail Logs') 
                                                    ->where('name', '!=', 'History-Update')
                                                    ->where('name', '!=', 'History-View')
                                                    ->where('name', '!=', 'Bulk Reports') 
                                                    ->get();

                                                    $selected = []; // Initialize the $selected array
                                                $roleDetails = App\Models\Role::find($role->id);
                                                
                                                
                                                foreach ($rolePermissions as $rolePermission) {
                                                    $selected[] = $rolePermission->permission_id;
                                                }
                                                
                                            @endphp

                                            <select class="form-control selectpicker" name="role_access[]"
                                                id="role_access" multiple >
                                                @foreach ($RolePermissions as $permissions)
                                                    <option value="{{ $permissions->id }}"
                                                        {{ in_array($permissions->id, $selected) ? 'selected' : '' }}>
                                                        {{ $permissions->name }}
                                                    </option>
                                                @endforeach





                                            </select>
                                            @error('role_access')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <br>
                                            <label>Permissions <span class="text-danger">*</span></label>
                                            <div class=" custom-checkbox">
                                                @php
                                                    $roleDetails = App\Models\Role::find($role->id);
                                                    $crud = DB::table('permissions')
                                                        ->where('name', 'Create')
                                                        ->orWhere('name', 'Update') 
                                                        ->orWhere('name', 'View')
                                                        ->orWhere('name', 'History-Update')
                                                        ->orWhere('name', 'History-View')
                                                        ->orWhere('name','Lead Reports')
                                                        ->orWhere('name','Employee Leads Reports') 
                                                        ->orWhere('name','Developer Reports')
                                                        ->orWhere('name','Employee Reports')
                                                        ->orWhere('name','Project Reports')
                                                        ->orWhere('name','Deal Confirm Reports')
                                                        ->orWhere('name','Location Reports')
                                                        ->orWhere('name','Login Reports')
                                                        ->orWhere('name','Common Pool Reports')
                                                        ->orWhere('name','Builder Reports') 
                                                        ->orWhere('name','Bulk Reports')  
                                                        ->get();
                                                @endphp
                                                <td>
                                                    @foreach ($crud as $permission)
                                                        <input class="bg-info" type="checkbox" name="permission[]"
                                                            value="{{ $permission->name }}"
                                                            @if ($roleDetails->permissions->contains($permission->id)) checked  @else @endif>

                                                        {{ $permission->name }} <br>
                                                    @endforeach

                                                </td>
                                            </div>
                                            @error('permission')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary ml-2 my-2">Update Role</button>
                                    </div>
                                </form>
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

        
            .iti__flag{
                display: none;
            }
</style>

<script>
     $('#role_access').select2({
            placeholder: "Select", 
        });
</script>
 
@endsection


