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
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Role</h4>
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
                                <form action="{{ route('create-roles') }}" method="POST">
                                    @csrf   
                                    <div class="row">
                                       <div class="col-md-12">
                                       @if (Session::has('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <h5>{{ Session::get('success') }}</h5>
                                        </div>
                                    @endif
                                    <label for="example-select">Role Name<span class="text-danger">*</span></label>
                                        <input type="text" name="addRole" class="form-control  " placeholder="Add Role"> 
                                         
                                        @error('addRole')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <br>
                                        <label for="example-select">Role Access <span class="text-danger">*</span></label>
                                        <select class=" selectpicker" name="role_access[]" id="role_access" multiple >
                                            @foreach ($permissions as $permission)
                                                <option value="{{ $permission->name }}">{{ $permission->name  }}</option>
                                            @endforeach 
                                        </select>
                                        
                                        @error('role_access')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror 
                                        <br>
                                        <label for="example-select" style="margin-top: 20px;">Permissions <span class="text-danger">*</span></label>
                                        <br>
                                        @php
                                            $crud =  DB::table('permissions')
                                                ->where('name','Create')
                                                ->orWhere('name','Update')
                                                ->orWhere('name','View')
                                                ->orWhere('name','Project Reports')
                                                ->orWhere('name','Lead Reports')
                                                ->orWhere('name','Employee Leads Reports') 
                                                ->orWhere('name','Employee Reports')
                                                ->orWhere('name','Developer Reports')
                                                ->orWhere('name','Deal Confirm Reports')
                                                ->orWhere('name','Location Reports')
                                                ->orWhere('name','Common Pool Reports')
                                                ->orWhere('name','Builder Reports')
                                                ->orWhere('name','Login Reports')
                                                ->orWhere('name','History-Update')
                                                ->orWhere('name',' Trail Logs') 
                                                ->orWhere('name','History-View')
                                                ->orWhere('name','Bulk Reports') 
                                                ->get();
                                        @endphp
                                        @foreach($crud as $permission) 
                                        
                                            <tr>
                                                <td>
                                                    <input  type="checkbox" 
                                                    name="permission[]"
                                                    value="{{ $permission->id }}"
                                                    class='permission mt-2'> 
                                                </td> 
                                                 <td>{{ $permission->name }}</td> 
                                                <br>
                                                
                                            </tr> 
                                        @endforeach 
                                        </div>
                                        @error('permission')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror 
                                    </div> 
                                       </div>
        
                                        <button type="submit" class="btn btn-primary ml-2 my-2">Create</button>
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


