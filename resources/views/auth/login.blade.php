{{-- @extends('main') --}}


@section('dynamic_page')

 @include('layouts.header')

 <body class="authentication-bg authentication-bg-pattern" style="background-image: url('/assets/images/BGLogin.webp'); background-size: cover; background-repeat: no-repeat; background-position: center center;">

        <div class="account-pages my-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body ">
                                
                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        @php
                                            $logo = DB::table('settings')->first();
                                            // dd($logo);
                                        @endphp
                                        <a href="#" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <h2 class="text-primary font-weight-bold text-center mt-3">{{ $logo->site_name }}</h2>
                                                   <img src="{{ url('') }}/public/assets/images/homents.png" alt="" height="100px">   

                                                {{--  <img style="margin-top: 0px;" src="{{ $logo->site_logo  }}">  --}}
                                                
                                            </span>
                                        </a> 
                                        {{-- <a href="#" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="{{ url('') }}/assets/images/logo-light.png" alt="" height="22">
                                            </span>
                                        </a> --}}
                                    </div>
                                    <p class="text-muted " style="margin-top: 20px;">Enter your email address and password to access admin panel.</p>
                                </div>
                                @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }} </div>
                                @endif
                                @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }} </div>
                                @endif
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" name="email" type="email" id="emailaddress" placeholder="Enter your email">
                                        @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="password" id="password"
                                                class="form-control" placeholder="Enter your password" value={{old('password')}}>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div> 
                                        </div>
                                        {{-- <div class="input-group input-group-merge">
                                            <input  type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div> --}}
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" name="remember_me">
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group mb-3">
                                        <p class="text-justify"><strong>Attention:</strong> Unauthorized access to this CRM system is prohibited and subject to legal action. By proceeding with log-in, you agree to abide by the authorized usage policy and consent to monitoring. Any unauthorized attempt to access, steal, or misuse information will be vigorously pursued under applicable laws and may result in criminal or civil penalties.</p>
                                    </div>


                                    <div class="form-group mb-0 text-center">
                                        <button name="submit" value="submit" class="btn btn-primary btn-block" type="submit"> Log In </button>
                                    </div>

                                </form>

                              

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p> <a href="{{ route('forgot-password') }}" class="ml-1" style="color: black">Forgot your password?</a></p>
                                <!--<p class="text-white-50">Don't have an account? <a href="auth-register.html" class="text-white ml-1"><b>Sign Up</b></a></p>-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
	@include('layouts.footer')
        @section('dynamic_page')
        
        
  
