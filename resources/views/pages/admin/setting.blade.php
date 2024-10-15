@extends('main')


@section('dynamic_page')
    <div class="container-fluid">



        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Homents</a></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Admin Settings</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header card-header-primary justify-content-between d-flex">
                        <h4 class="card-title">Site Name | Change Role | Site Logo </h4>
                        @if (\Auth::user()->roles_id == 1)
                                <form action="{{ route('our_backup_database') }}" method="get">
                                    <button style="submit" class="btn btn-primary">Backup Database</button>
                                </form> 
                            @endif
                    </div>
                    <div class="card-body">
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <h5>{{ Session::get('success') }}</h5>
                            </div>
                        @endif
                        <form enctype="multipart/form-data" action="{{ route('setting') }}" method="post">
                            @csrf
                            @php
                                $setting = App\Models\Setting::where('user_id', Auth::user()->id)->first();
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Site Name <span
                                            class="text-danger">*</span></label>
                                        @if (empty($setting))
                                            <input type="text" name="site_name" value="{{ old('site_name') }}"
                                                class="form-control" >
                                                @error('site_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                
                                        @else
                                            <input type="text" name="site_name" value="{{ $setting->site_name }}"
                                                class="form-control" >
                                                @error('site_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Contact Number</label>
                                        @if (empty($setting))
                                            <input type="text" name="contact_number" value="{{ old('site_name') }}"
                                                class="form-control">
                                        @else
                                            <input type="text" name="contact_number"
                                                value="{{ $setting->contact_number }}" class="form-control">
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-select">Change Role</label>
                                        <select name="role_id" 
                                        class="selectpicker" data-live-search="true" id="example-select">
                                            @foreach ($roles as $role)
                                                @if (empty($setting))
                                                    <option value="{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </option>
                                                @else
                                                    @if ($role->id == $setting->role_id)
                                                        <option value="{{ $role->id }}" selected>
                                                            {{ $role->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Location</label>
                                        <select name="locations" class="selectpicker" data-live-search="true">
                                            @foreach ($locations as $locations)
                                                @if (empty($setting))
                                                    <option value="{{ $locations->location }}">
                                                        {{ $locations->location }}
                                                    </option>
                                                @else
                                                    @if ($locations->location == $setting->locations)
                                                        <option value="{{ $locations->location }}" selected>
                                                            {{ $locations->location }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $locations->location }}">
                                                            {{ $locations->location }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                         {{-- <label class="bmd-label-floating">Site Logo</label>

                                     <img src="" alt="app logo"
                                        class="rounded-circle" style="width:100px; height:100px;" >  
                                    <div class="custom-file">
                                        <input type="file" name="site_logo" class="custom-file-input" id="customFile"
                                            accept="image/*">
                                        <label class="custom-file-label" for="customFile">Site Logo</label>
                                    </div> --}}
                                </div>
                                <!--<div class="col-md-6">-->
                                <!--    <img src="" alt="app logo"-->
                                <!--        class="rounded-circle" style="width:100px; height:100px;">-->
                                <!--    <div class="custom-file">-->
                                <!--        <input type="file" name="favicon" class="custom-file-input" id="customFile"-->
                                <!--            accept="image/*">-->
                                <!--        <label class="custom-file-label" for="customFile">Favicon</label>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div><br>

                            <br>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button name="submit" value="submit" type="submit"
                                        class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
