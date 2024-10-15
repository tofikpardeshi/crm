@include('layouts.header')

<body>

    <div id="wrapper">

         @include('layouts.topbar')

         @include('layouts.sidebar')


        <div class="content-page">
            <div class="content">
            
            <div class="mt-2">
            		
            		@if (Session::has('mySearch'))
                        @php
                            $isNumNotFound = session('mySearch');
                            $numberNotFound = trim(substr($isNumNotFound, 0, strpos($isNumNotFound, ' ')));
                        @endphp
                        <div class="alert alert-danger alert-dismissible d-flex">
                            <h5> {{ Session::get('mySearch') }}
                                <a href="{{ route('leads-index') }}"><button class="btn btn-danger">Back</button></a>
                                {{-- <a href="{{ url('create-leads') }}">
                                        <span>{{ $numberNotFound }}
                                        </span>
                                    </a>   --}}
                            </h5>  
                        </div>
                    @endif
                    
                     @if (session()->has('rwa'))
                        <div class="alert alert-danger text-center" id="rwa">
                            {{ session()->get('rwa') }} </div>
                    @endif
                    
            		
                    @if (session()->has('NoSearch'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('NoSearch') }} </div>
                @endif
                </div>

                @yield('dynamic_page')

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer" style="background-color: #e57113 !important">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            2022 - 2023 &copy; HOMENTS PRIVATE LIMITED <a href="">Homents</a> 
                        </div>
<!--                        <div class="col-md-6">
                            {{-- <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript:void(0);">About Us</a>
                                <a href="javascript:void(0);">Help</a>
                                <a href="javascript:void(0);">Contact Us</a>
                            </div> --}}
                        </div>-->
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->
       @include('layouts.footer')

</body>



  

