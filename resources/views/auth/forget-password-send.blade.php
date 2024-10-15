    @include('layouts.header')

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">

                                @php
                                $logo = DB::table('settings')->first();
                                // dd($logo);
                            @endphp

                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="#" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <h2 class="text-primary font-weight-bold text-center mt-3">{{ $logo->site_name }}</h2>
                                                
                                                <img style="margin-top: -40px;" src="{{ $logo->site_logo  }}"> 
                                            </span>
                                        </a> 

                                        <a href="#" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <h2 class="text-primary font-weight-bold text-center mt-3">{{ $logo->site_name }}</h2>
                                                {{-- <img src="{{ url('') }}/assets/images/logo-light.png" alt=""
                                                    height="22"> --}}
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your email address 
                                        access homents panel.</p>
                                </div>
                                @if (session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('error') }} </div>
                                @endif
                                <form action="{{ route('ResetPasswordPost') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" name="email" type="email" id="emailaddress"
                                            placeholder="Enter your email">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input autocomplete="OFF" type="password" name="password" id="password"
                                                class="form-control" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <input autocomplete="OFF" type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control" placeholder="Enter your confirmation password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @error('password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-group mb-3">
                                         <div class="custom-control custom-checkbox">
                                             <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                             <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                         </div>
                                     </div> --}}

                                    <div class="form-group mb-0 text-center">
                                        <button name="submit" value="submit" class="btn btn-primary btn-block"
                                            type="submit"> Reset Password </button>
                                    </div>

                                </form>



                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        {{-- <div class="row mt-3">
                             <div class="col-12 text-center">
                                 <p> <a href="{{ route('forgot-password') }}" class="text-white-50 ml-1">Forgot your password?</a></p>
                                 <!--<p class="text-white-50">Don't have an account? <a href="auth-register.html" class="text-white ml-1"><b>Sign Up</b></a></p>-->
                             </div> <!-- end col -->
                         </div> --}}
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <?php //include_once('../../footer.php');
        ?>
