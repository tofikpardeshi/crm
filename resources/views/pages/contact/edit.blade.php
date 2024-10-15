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
                            <li class="breadcrumb-item active">Contacts</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Contact</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible text-center">
                                        <h5>{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                <form method="post" action="{{ route('update-contact',$contacts->id) }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="simpleinput">Name</label>
                                        <input type="text" value="{{ $contacts->name }}" name="name" id="simpleinput"
                                            class="form-control">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="example-email">Email</label>
                                        <input type="email" name="email" value="{{ $contacts->email }}" id="example-email"
                                            class="form-control" placeholder="Email">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="example-email">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control"
                                            placeholder="Contact Number" value="{{ $contacts->contact_number }}">
                                        @error('contact_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="example-email">Job title</label>
                                        <input type="text" name="job_title" class="form-control" placeholder="job title"
                                            value="{{ $contacts->job_title }}">
                                        @error('job_title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="example-select">Contact Type</label>
                                        <select name="contact_type" class="form-control" id="example-select">
                                            @foreach ($contactTypes as $contactType)
                                                @if ($contactType->contact_name == $contacts->contact_type)
                                                    <option value="{{ $contactType->contact_name }}" selected>
                                                        {{ $contactType->contact_name }}</option>
                                                @else
                                                    <option value="{{ $contactType->contact_name }}">
                                                        {{ $contactType->contact_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('contact_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        {{-- @php
                                            $roles = array('Admin','Manager','Lead','Operator')
                                        @endphp --}}
                                        <label for="example-select">Role</label>
                                        <select name="role_id" class="form-control" id="example-select">
                                            @foreach ($roles as $role)
                                                @if ($role->id == $contacts->role_id)
                                                    <option value="{{ $role->name }}" selected>{{ $role->name }}</option>
                                                @else
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1">Activate</label>
                                    </div>

                                    <button name="submit" value="submit" type="submit"
                                        class="btn btn-primary waves-effect waves-light mt-3">Edit Contact</button>


                                </form>
                            </div>
                        </div>


                    </div> <!-- end card-body-->

                </div> <!-- end card-->



            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
