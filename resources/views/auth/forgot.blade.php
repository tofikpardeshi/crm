    

 @include('layouts.header')
 
    <body class="authentication-bg authentication-bg-pattern" style="background-image: url('/assets/images/BGLogin.webp'); background-size: cover; background-repeat: no-repeat; background-position: center center;">
 
         <div class="account-pages mt-5 mb-5">
             <div class="container">
                 <div class="row justify-content-center">
                     <div class="col-md-8 col-lg-6 col-xl-5">
                         <div class="card bg-pattern">
 
                             <div class="card-body p-4">
                                 
                                 <div class="text-center w-75 m-auto">
                                     <div class="auth-logo">
                                        @php
                                        $logo = DB::table('settings')->first();
                                        // dd($logo);
                                    @endphp
                                         <a href="#" class="logo logo-dark text-center">
                                             <span class="logo-lg">
                                                <h2 class="text-primary font-weight-bold text-center mt-3">{{ $logo->site_name }}</h2>
                                                  
                                                <img style="margin-top: -40px;" src="{{ $logo->site_logo  }}">  
                                                 
                                             </span>
                                         </a>
                     
                                         <a href="#" class="logo logo-light text-center">
                                             <span class="logo-lg">
                                                <h2 class="text-primary font-weight-bold text-center mt-3">{{ $logo->site_name }}</h2>
                                                 {{-- <img src="{{ url('') }}/assets/images/logo-light.png" alt="" height="22"> --}}
                                             </span>
                                         </a>
                                     </div> 
                                     <p class="text-muted mb-4 mt-3">Enter your email address access homents panel.</p>
                                 </div>
                                 @if(session()->has('error'))
                                 <div class="alert alert-danger">
                                     {{ session()->get('error') }} </div>
                                 @endif
                                 @if(session()->has('message'))
                                 <div class="alert alert-success">
                                     {{ session()->get('message') }} </div>
                                 @endif
                                 <form action="{{ route('forgot-password-store') }}" method="POST">
                                     @csrf
                                     <div class="form-group mb-3">
                                         <label for="emailaddress">Email Address</label>
                                         <input class="form-control" name="email" type="email" id="emailaddress" placeholder="Enter your email">
                                         @error('email')
                                         <small class="text-danger">{{ $message }}</small>
                                         @enderror
                                     </div>  
                                     <div class="form-group mb-0 text-center">
                                         <button name="submit" value="submit" class="btn btn-primary btn-block" type="submit"> Reset Password  </button>
                                     </div>
 
                                 </form>
 
                               
 
                             </div> <!-- end card-body -->
                         </div>
                         <!-- end card -->
 
                         <div class="row mt-3">
                             <div class="col-12 text-center">
                                  <p> <a href="{{ url("/") }}" class="text-white-50 ml-1">Go Back</a></p>  
                                 {{-- <p class="text-white-50">Don't have an account? <a href="auth-register.html" class="text-white ml-1"><b>Sign Up</b></a></p>  --}}
                             </div> <!-- end col -->
                         </div>
                         <!-- end row -->
 
                     </div> <!-- end col -->
                 </div>
                 <!-- end row -->
             </div>
             <!-- end container -->
         </div>
         <!-- end page -->
  
   