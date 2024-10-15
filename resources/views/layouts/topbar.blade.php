<style>
    .navbar-custom {background-color: #e57113 !important}
    .notif-clear {color:#7e57c2 !important}
    .is-notification{padding: 0 0 5px 0 !important}
    .is-notification > h5{padding: 5px 15px}
    .is-notification > a{padding: 5px 15px !important; position: relative}
    .is-notification > a span{margin-top: 0;  position: absolute;  right: 15px;  top: 0;}
    .notification-list .notify-item {
    /* padding: 8px 15px !important; */
}
</style>
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">


<!--             <li class="dropdown d-inline-block d-lg-none global-search">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-search noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                    <form class="p-3" method="get" action="{{ url('is-leads-number-exist') }}">
                        <input  type="search" name="lead_search" class="form-control" placeholder="Global Search..."
                                id="top-search" autocomplete="off" pattern="[1-9]{1}[0-9]{9}" oninvalid="setCustomValidity('Please Enter Valid Mobile Number ')"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); this.setCustomValidity('')"
                                required>
                    </form>
                </div>
            </li>  -->

            <li class="">
                <form class="app-search" method="get" action="{{ url('is-leads-number-exist') }}">
                    <div class="app-search-box dropdown">
                        <div class="input-group">
                            {{--<input type="search" name="lead_search" class="form-control" placeholder="Global Search..."
                              id="top-search" autocomplete="off" pattern="[1-9]{1}[0-9]{9}" oninvalid="setCustomValidity('Please Enter Valid Mobile Number ')"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); this.setCustomValidity('')" 
                                required>--}}
                                 <input type="search" name="lead_search" class="form-control" placeholder="Global Search..."
    id="top-search" autocomplete="off" pattern="[0-9]*"
     oninput="this.value = this.value.replace(/^(\+91|0|91)/, '').replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" style="color:white !important" required>
                            {{-- <div class="input-group-append">
                                <button class="btn" type="submit">
                                    <i class="fe-search"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </form>
            </li>

            {{-- <li class="dropdown d-none d-lg-inline-block " style="margin-top: 18px;"> 
                <form class="form-inline" method="get" action="{{ url('is-leads-number-exist') }}">
                    <div class="form-group ">
                        <label for="inputPassword2" class="sr-only">Search</label>
                        <input type="search" name="lead_search" class="form-control" id="inputPassword2"
                            placeholder="Search...">
                    </div>
                </form>
            </li>  --}}


            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                    href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>






            {{-- <li class="dropdown d-none d-lg-inline-block topbar-dropdown">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ url('') }}/assets/images/flags/us.jpg" alt="user-image" height="16">
                </a>
                <div class="dropdown-menu dropdown-menu-right">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ url('') }}/assets/images/flags/germany.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ url('') }}/assets/images/flags/italy.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ url('') }}/assets/images/flags/spain.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{ url('') }}/assets/images/flags/russia.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Russian</span>
                    </a>

                </div>
            </li> --}}

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    <span class="badge badge-danger rounded-circle noti-icon-badge">
                        {{ Auth::user()->unreadNotifications->take(50)->sortByDesc('created_at')->count() }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg" style="border-width: 0 1px 1px 1px;border-color: #7e57c2;border-style: solid;">

                    <!-- item-->
                    @php
                        //  $next_days = now()->subDays(3);
                        // $employees = DB::table('employees')->whereMonth('date_of_brith', $next_days->month)
                        // ->whereDay('date_of_brith',$next_days->day)->get();
                        
                        $employees = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_brith) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_of_brith)')
                            ->orderByRaw('DAYOFYEAR(date_of_brith)')
                            ->get();
                        
                        $employeesbirthDayCount = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_brith) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_of_brith)')
                            ->orderByRaw('DAYOFYEAR(date_of_brith)')
                            ->count();
                        
                        // $next_days = now()->subDays(3);
                        // $employeesCount = DB::table('employees')->whereMonth('date_of_brith', $next_days->month)
                        // ->whereDay('date_of_brith',$next_days->day)->count();
                        
                        // $next_days = now()->subDays(3);
                        // $employeesWeddingAnniversary = DB::table('employees')->whereMonth('marriage_anniversary', $next_days->month)
                        // ->whereDay('marriage_anniversary',$next_days->day)->get();
                        
                        $employeesWeddingAnniversary = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(marriage_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(marriage_anniversary)')
                            ->orderByRaw('DAYOFYEAR(marriage_anniversary)')
                            ->where('organisation_leave',0)
                            ->get();
                        
                        $employeesWeddingAnniversaryCount = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(marriage_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(marriage_anniversary)')
                            ->orderByRaw('DAYOFYEAR(marriage_anniversary)')
                            ->count();

                        $BuilderBirthdayCount = App\Models\Team::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_birth) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_of_birth)')
                            ->orderByRaw('DAYOFYEAR(date_of_birth)')
                            ->count();

                        $BuilderBirthdayGet = App\Models\Team::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_birth) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_of_birth)')
                        ->orderByRaw('DAYOFYEAR(date_of_birth)')
                        ->get();

                        $BuilderWeddingAnniversaryCount = App\Models\Team::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                            ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                            ->count();

                        $BuilderWeddingAnniversaryGet = App\Models\Team::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                        ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                        ->get();
                        
                        // $next_days = now()->subDays(3);
                        // $employeesWeddingAnniversaryCount = DB::table('employees')->whereMonth('marriage_anniversary', $next_days->month)
                        // ->whereDay('marriage_anniversary',$next_days->day)->count();
                        
                        // $next_days = now()->subDays(3);
                        // $employeesWorkAnniversary = DB::table('employees')->whereMonth('date_joining', $next_days->month)
                        // ->whereDay('date_joining',$next_days->day)->get();
                        
                        $employeesWorkAnniversary = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_joining) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_joining)')
                        ->where('organisation_leave',0)
                        ->orderByRaw('DAYOFYEAR(date_joining)')
                        ->get();
                        
                        $employeesWorkAnniversaryCount = App\Models\Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_joining) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_joining)')
                            ->orderByRaw('DAYOFYEAR(date_joining)')
                            ->where('organisation_leave',0)
                            ->count();
                        
                        // $next_days = now()->subDays(3);
                        // $employeesWorkAnniversaryCount = DB::table('employees')->whereMonth('date_joining', $next_days->month)
                        // ->whereDay('date_joining',$next_days->day)->count();
                        
                        if (Auth::user()->roles_id == 1) {
                            $isCustomerWedding = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                                ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                                ->get();
                        
                            $isCustomerWeddingCount = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                                ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                                ->count();
                        } else {
                            $empID = App\Models\Employee::where('user_id', Auth::user()->id)->first();
                        
                            $isCustomerWedding = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                                ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                                ->where('assign_employee_id', $empID->id)
                                ->get();
                        
                            $isCustomerWeddingCount = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(wedding_anniversary) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(wedding_anniversary)')
                                ->orderByRaw('DAYOFYEAR(wedding_anniversary)')
                                ->where('assign_employee_id', $empID->id)
                                ->count();

                             
                        }
                        
                        if (Auth::user()->roles_id == 1) {
                            $isCustomerdob = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(dob)')
                                ->orderByRaw('DAYOFYEAR(dob)')
                                ->get();
                        
                            $isCustomerdobCount = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(dob)')
                                ->orderByRaw('DAYOFYEAR(dob)')
                                ->count();
                        } else {
                            $empID = App\Models\Employee::where('user_id', Auth::user()->id)->first();
                        
                            $isCustomerdob = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(dob)')
                                ->orderByRaw('DAYOFYEAR(dob)')
                                ->where('assign_employee_id', $empID->id)
                                ->get();
                        
                            $isCustomerdobCount = App\Models\Lead::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(dob)')
                                ->orderByRaw('DAYOFYEAR(dob)')
                                ->where('assign_employee_id', $empID->id)
                                ->count();
                        }
                        
                    @endphp

                    

                    <div class="dropdown-item noti-title" style="padding:5px 15px">
                        <h5 class="m-0">
                            <span class="float-right">
                                @if (Auth::user()->notifications()->count())
                                    <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                @endif
                            </span>
                            Notification
                        </h5>
                    </div>


                    <div class="noti-scroll" style="max-height: 500px" data-simplebar>

                        <div class="dropdown-item noti-title is-notification">

                        
                            @if ($isCustomerWeddingCount > 0)
                            <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Customer’s Wedding Anniversary
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $isCustomerWeddingCount }}
                                </span>
                            </h5>
                            
                                @foreach ($isCustomerWedding as $CustomerWedding)
                                    @php
                                        $CustomerWeddingdate = Carbon\Carbon::parse($CustomerWedding->wedding_anniversary)->format('m-d');
                                        // print_r($date);
                                        $CustomerWeddingnow = Carbon\Carbon::now()->format(' jS, F');
                                        //print_r($CustomerWeddingnow);
                                        $CustomerWeddingYearNow = \Carbon\Carbon::parse($CustomerWedding->wedding_anniversary);
                                        //print_r($CustomerWeddingYearNow);
                                        $CustomerWeddingdiffYears = \Carbon\Carbon::now()->diffInYears($CustomerWeddingYearNow);
                                        
                                        // $locale = 'en_US';
                                        // $nf = new \NumberFormatter($locale, \NumberFormatter::ORDINAL);
                                        // $isCustomerWeddingYears = $nf->format($CustomerWeddingdiffYears);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " 
                                    style="background:#ffffff; padding:12px ;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                        @if ($CustomerWeddingdate == $CustomerWeddingnow) 
                                        <p class="text-capitalize text-muted mb-0 user-msg" 
                                        style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                       
                                            {{ $CustomerWedding->lead_name }} ’s Wedding Anniversary is on {{ \Carbon\Carbon::parse($CustomerWedding->wedding_anniversary)->format( ' jS F'); }}
                                        </p>
                                         @else
                                        @endif 
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#128145;</span>
                                        {{-- @if ($CustomerWeddingdate == $CustomerWeddingnow) --}}
                                            <small class="text-muted text-wrap text-capitalize">
                                                {{ $CustomerWedding->lead_name }}’s {{ $CustomerWeddingdiffYears }} Wedding Anniversary is Today {{ \Carbon\Carbon::parse($CustomerWedding->wedding_anniversary)->format( ' jS F') }} . Don’t forget to wish your customer today.</small>
                                        {{-- @else
                                        @endif --}}
    
                                    </a>
                                @endforeach 
                            @else
                            
                            @endif
                           
                            @if ($isCustomerdobCount > 0)
                            <h5 class="m-0 customer-birthday">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Customer’s Birthday
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $isCustomerdobCount }}
                                </span>
                            </h5> 
                                @foreach ($isCustomerdob as $Customerdob)
                                    @php
                                        $CustomerBirthgdate = Carbon\Carbon::parse($Customerdob->dob)->format('m-d');
                                        // print_r($date);
                                        $CustomerBirthgdatenow = Carbon\Carbon::now()->format('m-d');
                                        $CustomerBirthgdateNow = \Carbon\Carbon::parse($Customerdob->dob);
                                        $CustomerBirthgdateYears = \Carbon\Carbon::now()->diffInYears($CustomerBirthgdateNow);
                                        
                                      
                                        
                                    @endphp
                                    <a href="{{ url('lead-status/' . encrypt($Customerdob->id)) }}" class="dropdown-item notify-item active " 
                                    style="background:#ffffff; padding:12px 0px; margin-bottom: 0; padding-bottom: 0; font-size: 12px"
                                    target="blank"> 
                                    @if ($CustomerBirthgdate != $CustomerBirthgdatenow)
                                        <p class=" text-muted mb-0 user-msg" style="margin-left:0!important; margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            {{ $Customerdob->lead_name }} ’s Birthday is on {{ 
                                                \Carbon\Carbon::parse($Customerdob->dob)->format( ' jS F'); }} 
                                                @if ($CustomerBirthgdate == $CustomerBirthgdatenow)
                                        </p> 
                                           
                                        @endif
                                        @else
                                        <small class="text-muted text-wrap">
                                            {{ $Customerdob->lead_name }}’s Birthday is Today  {{ \Carbon\Carbon::parse($Customerdob->dob)->format( ' jS F'); }} . Don’t forget to wish your customer today.
                                        </small> 
                                        <a class="float-right" style="font-size: 20px;  margin-top: -5px;" href="https://api.whatsapp.com/send/?phone={{ str_replace([' ', '+'],'',$Customerdob->country_code).$Customerdob->contact_number }}"
                                            target="_blank">
                                            <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                        </a>
                                        
                                        @endif
                                         </p>  
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; margin-bottom: 0; padding-bottom: 0; font-size: 12px">&#127874;
                                        </span>
                                      
    
                                    </a>
                                @endforeach 
                            @else
                                
                            @endif
                           
                            @if ($employeesWorkAnniversaryCount > 0)
                            <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark" >
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Employee Work Anniversary
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $employeesWorkAnniversaryCount }}
                                </span>
                            </h5>
     
                                @foreach ($employeesWorkAnniversary as $WorkAnniversary)
                                    @php
                                        $employeesWorkAnniversarydate = Carbon\Carbon::parse($WorkAnniversary->date_joining)->format('m-d');
                                        // print_r($date);
                                        $employeesWorkAnniversarydatenow = Carbon\Carbon::now()->format('m-d');
                                        $employeesWorkAnniversarygdateNow = \Carbon\Carbon::parse($WorkAnniversary->date_joining);
                                        $employeesWorkAnniversarydateYears = \Carbon\Carbon::now()->diffInYears($employeesWorkAnniversarygdateNow);
                                        
                                        // $locale = 'en_US';
                                        // $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
                                        // $isCustomerworkYears = $nf->format($employeesWorkAnniversarydateYears);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                        {{-- @if ($employeesWorkAnniversarydate != $employeesWorkAnniversarydatenow) --}}
                                        <p class="text-muted mb-0 user-msg" style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            {{ $WorkAnniversary ? $WorkAnniversary->employee_name : "N/A" }} ’s Work Anniversary is on
                                            {{ \Carbon\Carbon::parse($WorkAnniversary->date_joining)->format( ' jS F'); }}
                                        </p>
                                        {{-- @endif --}}
                                       
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#127881;</span>
                                        @if ($employeesWorkAnniversarydatenow == $employeesWorkAnniversarydate)
                                            <small class="text-muted text-wrap">
                                                {{ $WorkAnniversary->employee_name }}’s Work Anniversary is Today {{ \Carbon\Carbon::parse($WorkAnniversary->date_joining)->format( ' jS F'); }}. Keep doing the good work champ.
                                            </small>


                                            <a class="float-right" style="font-size: 20px;  margin-top: -5px;" href="https://api.whatsapp.com/send/?phone={{ str_replace('+','',$WorkAnniversary->emp_country_code).$WorkAnniversary->official_phone_number }}"
                                                target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>
                                        @else
                                        @endif
                                        {{-- <small class="text-muted">{{ $diff }}</small> --}}
                                    </a>
                                @endforeach 
                            @else
                                
                            @endif
                            
                             @if ($BuilderBirthdayCount > 0) 
                                <h5 class="m-0">
                                    Builders Happy Birthdays
                                    <span class="badge badge-danger rounded-circle ">
                                        {{ $BuilderBirthdayCount }}
                                    </span>
                                </h5> 
                             
                                @foreach ($BuilderBirthdayGet as $BuilderBirthday) 
                                @php
                                $BuilderBirthDate = Carbon\Carbon::parse($BuilderBirthday->date_of_brith)->format('m-d');
                                // print_r($date);
                                $BuilderBirthNow = Carbon\Carbon::now()->format('m-d');
                                $BuilderbirthdateNow = \Carbon\Carbon::parse($BuilderBirthday->date_of_brith);
                                $BuilderBirthDateYears = \Carbon\Carbon::now()->diffInYears($BuilderbirthdateNow);

                                $BuilderName = DB::table('builders')->where('id',$BuilderBirthday->builder_id )
                                ->select('name')->first();

                                $NameofDevelopers =  DB::table('name_of_developers') 
                                ->where('id',$BuilderBirthday->name_of_developer )
                                ->select('name_of_developer')->first(); 
                                
                            @endphp
                             
                            
                                     <a href="{{ url('builder-details/'.$BuilderBirthday->id) }}" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                       <p class="text-capitalize text-muted mb-0 user-msg" style="margin-left:0!important; margin-bottom: 0; padding-bottom: 0; font-size: 12px"> 
                                            @if ($BuilderBirthDate == $BuilderBirthNow)  
                                              <small class="text-muted text-wrap" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $BuilderBirthday->team_name }}’s ({{$BuilderName ? $BuilderName->name : 'N/A'}} - {{ $NameofDevelopers ?
                                                $NameofDevelopers->name_of_developer : 'N/A' }}) Birthday is today. Don’t forget to wish. 
                                                </small>
                                            @else
                                        @endif
                                        </p>
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; margin-bottom: 0; padding-bottom: 0; font-size: 12px">&#127874;</span>
                                        
                                      
                                        
                                        {{-- <small class="text-muted">{{ $diff }}</small> --}}
                                    </a>
                                @endforeach  
                                 
                             @endif


                             @if ($BuilderWeddingAnniversaryCount > 0) 
                                <h5 class="m-0">
                                    Builder’s Wedding Anniversary
                                    <span class="badge badge-danger rounded-circle ">
                                        {{ $BuilderWeddingAnniversaryCount }}
                                    </span>
                                </h5> 
                             
                                @foreach ($BuilderWeddingAnniversaryGet as $BuilderWeddingAnniversary) 
                                @php
                                $BuilderBirthDate = Carbon\Carbon::parse($BuilderWeddingAnniversary->wedding_anniversary)->format('m-d');
                                // print_r($date);
                                $BuilderBirthNow = Carbon\Carbon::now()->format('m-d');
                                $BuilderbirthdateNow = \Carbon\Carbon::parse($BuilderWeddingAnniversary->wedding_anniversary);
                                $BuilderBirthDateYears = \Carbon\Carbon::now()->diffInYears($BuilderbirthdateNow);
                                $BuilderName = DB::table('builders')->where('id',$BuilderWeddingAnniversary->builder_id )
                                ->select('name')->first();

                                $NameofDevelopers =  DB::table('name_of_developers') 
                                ->where('id',$BuilderWeddingAnniversary->name_of_developer )
                                ->select('name_of_developer')->first(); 
                                
                                @endphp
                                     <a href="#" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                        {{-- <a href="{{ url('builder-details/'.$BuilderBirthday->id) }}" class="text-capitalize text-muted mb-0 user-msg" --}}
                                            <a href="{{ url('builder-details/'.$BuilderWeddingAnniversary->id) }}" class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important">
                                            {{-- {{ $BuilderWeddingAnniversary->team_name }} ’s Wedding Anniversary is on
                                            {{ \Carbon\Carbon::parse($BuilderWeddingAnniversary->wedding_anniversary)->format( ' jS F'); }} --}}
                                        </a>
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#127874;</span>
                                        @if ($BuilderBirthDate == $BuilderBirthNow)
                                        {{-- <a href="{{ url('builder-details/'.$BuilderBirthday->id) }}" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">  --}}
                                             <a class="text-capitalize text-muted mb-0 user-msg"
                                             style="margin-left:0!important" href="{{ url('builder-details/'.$BuilderWeddingAnniversary->id) }}" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"
                                                >
                                            <small class="text-muted text-wrap">
                                                {{ $BuilderWeddingAnniversary->team_name }}’s ({{$BuilderName ? $BuilderName->name : 'N/A'}} - {{ $NameofDevelopers ?
                                                    $NameofDevelopers->name_of_developer : 'N/A' }}) Wedding Anniversary is today. Don’t forget to wish. </small>
                                        </a>
                                        @else
                                        @endif
                                        {{-- <small class="text-muted">{{ $diff }}</small> --}}
                                    </a>
                                @endforeach  
                                 
                             @endif
                        
                             
                            {{-- birthday function remove here --}}
                            
                        </div>

                        <div class="dropdown-item noti-title is-notification" style="border-bottom: 1px solid #ddd;">

                        
                            @if ($isCustomerWeddingCount > 0)
                            <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Customer’s Wedding Anniversary
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $isCustomerWeddingCount }}
                                </span>
                            </h5>
                            
                                @foreach ($isCustomerWedding as $CustomerWedding)
                                    @php
                                        $CustomerWeddingdate = Carbon\Carbon::parse($CustomerWedding->wedding_anniversary)->format('m-d');
                                        // print_r($date);
                                        $CustomerWeddingnow = Carbon\Carbon::now()->format(' jS, F');
                                        //print_r($CustomerWeddingnow);
                                        $CustomerWeddingYearNow = \Carbon\Carbon::parse($CustomerWedding->wedding_anniversary);
                                        //print_r($CustomerWeddingYearNow);
                                        $CustomerWeddingdiffYears = \Carbon\Carbon::now()->diffInYears($CustomerWeddingYearNow);
                                        
                                        // $locale = 'en_US';
                                        // $nf = new \NumberFormatter($locale, \NumberFormatter::ORDINAL);
                                        // $isCustomerWeddingYears = $nf->format($CustomerWeddingdiffYears);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " 
                                    style="background:#ffffff; padding:12px 0px; margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                        <p class="text-capitalize text-muted mb-0 user-msg" 
                                        style="margin-left:0!important">
                                            {{ $CustomerWedding->lead_name }} ’s Wedding Anniversary is on
                                            {{ \Carbon\Carbon::parse($CustomerWedding->wedding_anniversary)->format( ' jS F'); }}
                                        </p>
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#128145;</span>
                                        @if ($CustomerWeddingdate == $CustomerWeddingnow)
                                            <small class="text-muted text-wrap text-capitalize">
                                                {{ $CustomerWedding->lead_name }}’s Wedding
                                                Anniversary is Today. Wish them well</small>
                                        @else
                                        @endif
    
                                    </a>
                                @endforeach 
                            @else
                            
                            @endif
                           
                            @if ($isCustomerdobCount > 0)
                            {{-- <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count()) 
                                    @endif
                                </span>
                                Customer’s Birthday
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $isCustomerdobCount }}
                                </span>
                            </h5>  --}}
                                @foreach ($isCustomerdob as $Customerdob)
                                    @php
                                        $CustomerBirthgdate = Carbon\Carbon::parse($Customerdob->dob)->format('m-d');
                                        // print_r($date);
                                        $CustomerBirthgdatenow = Carbon\Carbon::now()->format('m-d');
                                        $CustomerBirthgdateNow = \Carbon\Carbon::parse($Customerdob->dob);
                                        $CustomerBirthgdateYears = \Carbon\Carbon::now()->diffInYears($CustomerBirthgdateNow);
                                        
                                    @endphp
                                    {{-- <a href="#" class="dropdown-item notify-item active " 
                                    style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px"> 
                                        <p class="text-capitalize text-muted mb-0 user-msg" style="margin-left:0!important">
                                            {{ $Customerdob->lead_name }} ’s Birthday is on {{ 
                                                \Carbon\Carbon::parse($Customerdob->dob)->format( ' jS F'); }}
                                        </p>
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#127874;</span>
                                        @if ($CustomerBirthgdate == $CustomerBirthgdatenow)
                                            <small class="text-muted text-wrap">
                                                {{ $Customerdob->lead_name }}’s Birthday is Today. Wish them well
                                            </small>
                                        @else
                                        @endif
    
                                    </a> --}}
                                @endforeach 
                            @else
                                
                            @endif
                           
                            @if ($employeesWeddingAnniversaryCount > 0)
                            <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Employee Work Anniversary
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $employeesWeddingAnniversaryCount }}
                                </span>
                            </h5>
     
                                @foreach ($employeesWorkAnniversary as $WorkAnniversary)
                                    @php
                                        $employeesWorkAnniversarydate = Carbon\Carbon::parse($WorkAnniversary->date_joining)->format('m-d');
                                        // print_r($date);
                                        $employeesWorkAnniversarydatenow = Carbon\Carbon::now()->format('m-d');
                                        $employeesWorkAnniversarygdateNow = \Carbon\Carbon::parse($WorkAnniversary->date_joining);
                                        $employeesWorkAnniversarydateYears = \Carbon\Carbon::now()->diffInYears($employeesWorkAnniversarygdateNow);
                                        
                                        // $locale = 'en_US';
                                        // $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
                                        // $isCustomerworkYears = $nf->format($employeesWorkAnniversarydateYears);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                        {{-- @if ($employeesWorkAnniversarydate != $employeesWorkAnniversarydatenow) --}}
                                        <p class=" text-muted mb-0 user-msg" style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            {{ $WorkAnniversary ? $WorkAnniversary->employee_name : "N/A" }} ’s Work Anniversary is on
                                            {{ \Carbon\Carbon::parse($WorkAnniversary->date_joining)->format( ' jS F'); }}
                                        </p>
                                        {{-- @endif --}}
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#127881;</span>
                                        @if ($employeesWorkAnniversarydatenow == $employeesWorkAnniversarydate)
                                            <small class="text-muted text-wrap">
                                                {{ $WorkAnniversary->employee_name }}’s 
                                                Work Anniversary is Today{{ \Carbon\Carbon::parse($employeesWorkAnniversarydateYears)->format( 'jS'); }}. Keep doing the good work champ.
                                            </small>
                                        @else
                                        @endif
                                        {{-- <small class="text-muted">{{ $diff }}</small> --}}
                                    </a>
                                @endforeach 
                            @else
                                
                            @endif
                            
                            @if ($employeesWeddingAnniversaryCount > 0)
                            <h5 class="m-0">
                                Employee Wedding Anniversary
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $employeesWeddingAnniversaryCount }}
                                </span>
                            </h5>
     
                                @foreach ($employeesWeddingAnniversary as $WeddingAnniversary)
                                    @php
                                        $employeesWeddingAnniversarydate = Carbon\Carbon::parse($WeddingAnniversary->marriage_anniversary)->format('m-d');
                                        // print_r($date);
                                        $employeesWeddingAnniversarydatenow = Carbon\Carbon::now()->format('m-d');
                                        $employeesWeddingAnniversarydateNow = \Carbon\Carbon::parse($WeddingAnniversary->marriage_anniversary);
                                        $employeesWeddingAnniversarydateYears = \Carbon\Carbon::now()->diffInYears($employeesWeddingAnniversarydateNow);
                                        

                                         

                                        // $locale = 'en_US';
                                        // $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
                                        // $isemployeesWeddingAnniversaryYear = $nf->format($employeesWeddingAnniversarydateYears);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Wedding Anniversary  </p>  --}}
                                        <p class="text-muted text-wrap">
                                            {{ $WeddingAnniversary->employee_name }}’s 
                                          
                                            Wedding Anniversary is on {{Carbon\Carbon::parse($WeddingAnniversary->marriage_anniversary)->format( ' jS F');}}
                                        </p>
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#128145;</span>
    
                                        @if ($employeesWeddingAnniversarydatenow == $employeesWeddingAnniversarydate)
                                        <small class="text-muted text-wrap">
                                            {{ $WeddingAnniversary->employee_name }}’s 
                                            @if ($employeesWeddingAnniversarydateYears % 100 >= 11 && $employeesWeddingAnniversarydateYears % 100 <= 13)
                                                {{ $employeesWeddingAnniversarydateYears }}th
                                            @else
                                                @if ($employeesWeddingAnniversarydateYears % 10 == 1)
                                                    {{ $employeesWeddingAnniversarydateYears }}st
                                                @elseif ($employeesWeddingAnniversarydateYears % 10 == 2)
                                                    {{ $employeesWeddingAnniversarydateYears }}nd
                                                @elseif ($employeesWeddingAnniversarydateYears % 10 == 3)
                                                    {{ $employeesWeddingAnniversarydateYears }}rd
                                                @else
                                                    {{ $employeesWeddingAnniversarydateYears }}th
                                                @endif
                                            @endif
                                             Wedding Anniversary is Today  {{ Carbon\Carbon::parse($WeddingAnniversary->marriage_anniversary)->format( ' jS F'); }}. 
                                            Happy Wedding Anniversary from Homents Family, don’t forget to treat us.
                                        </small>
                                        

                                                <a class="float-right" style="font-size: 20px;  margin-top: -5px;" href="https://api.whatsapp.com/send/?phone={{ str_replace('+','',$WeddingAnniversary->emp_country_code).$WeddingAnniversary->official_phone_number }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                        @else
                                        @endif
     
                                    </a>
                                @endforeach 
                            @else
                                
                            @endif
                            
                            @if ($employeesbirthDayCount > 0)
                            <h5 class="m-0">
                                <span class="float-right">
                                    @if (Auth::user()->notifications()->count())
                                        {{-- <a href="{{ route('clear-all-notification') }}" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    @endif
                                </span>
                                Employee's Birthdays
                                <span class="badge badge-danger rounded-circle ">
                                    {{ $employeesbirthDayCount }}
                                </span>
                            </h5>
     
                                @foreach ($employees as $employeeBithday)
                                    @php
                                        $employeesbirthdate = Carbon\Carbon::parse($employeeBithday->date_of_brith)->format('m-d');
                                        // print_r($date);
                                        $employeesbirthnow = Carbon\Carbon::now()->format('m-d');
                                        $employeesbirthdateNow = \Carbon\Carbon::parse($employeeBithday->date_of_brith);
                                        $employeesbirthdateYears = \Carbon\Carbon::now()->diffInYears($employeesbirthdateNow);
                                        
                                    @endphp
                                    <a href="#" class="dropdown-item notify-item active " style="background:#ffffff; padding:12px 0px;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{-- <p class="text-capitalize  ">Employee Birthday </p>  --}}
                                          {{-- @if ($employeesbirthdate == $employeesbirthnow) --}}
                                        <p class="text-capitalize text-muted mb-0"
                                            style="margin-left:0!importan;"
                                         >
                                            {{ $employeeBithday->employee_name }} ’s Birthday is on
                                            {{ \Carbon\Carbon::parse($employeeBithday->date_of_brith)->format( ' jS F'); }}
                                        </p>
                                         {{-- @endif   --}}
                                        <span class="text-success float-right"
                                            style="font-size: 20px;  margin-top: -5px; ">&#127874;</span>
                                        @if ($employeesbirthdate == $employeesbirthnow)
                                            <small class="text-muted text-wrap" >
                                                {{ $employeeBithday->employee_name }}’s Birthday is Today {{ \Carbon\Carbon::parse($employeeBithday->date_of_brith)->format(' jS F') }}. 
                                                Happy Birthday from Homents Family, don’t forget to treat us. 
                                                </small>

                                                <a class="float-right" style="font-size: 20px;  margin-top: -32px;" href="https://api.whatsapp.com/send/?phone={{ str_replace('+','',$employeeBithday->emp_country_code).$employeeBithday->official_phone_number }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                                </a>
                                                
                                        @else
                                        @endif
                                        {{-- <small class="text-muted">{{ $diff }}</small> --}}
                                    </a>
                                @endforeach 
                            @else
                                
                            @endif
                            
                        </div>

                        @php
                            $user = Auth::user();

                            // Retrieve the latest 50 notifications for the user
                            $notifications = $user->notifications()->latest()->take(50)->get();
                        @endphp

                        @foreach ($notifications as $notification)
                            @php
                                $date = Carbon\Carbon::parse($notification->created_at);
                                $now = Carbon\Carbon::now();
                                $diff = Carbon\Carbon::parse($notification->created_at)->format('d-m-Y');
                                $diffTime = Carbon\Carbon::parse($notification->created_at)->format('H:s');
                                
                                $employeeNotication = Carbon\Carbon::now()
                                    ->subDays(3)
                                    ->format('Y-m-d');
                                // print_r($employeeNotication );
                                
                                $emName = DB::table('employees')
                                    ->where('user_id', $notification->data['EmployeeName'])
                                    ->first();
                                
                                $emDOB = DB::table('employees')
                                    ->where('user_id', $notification->data['EmployeeName'])
                                    //->where( 'date_of_brith', '>', Carbon\Carbon::now()->subDays(3)->format('Y-m-d'))
                                    ->first();
                                
                                // print_r($emDOB->date_of_brith);
                                
                                $today = Carbon\Carbon::now();
                                $leadNotification = DB::table('leads')
                                    ->where('id', $notification->data['leadsID'])
                                    ->first(); 

                                $leadNotificationHis = DB::table('lead_status_histories')
                                    ->where('lead_id', $notification->data['leadsID'])
                                    ->count(); 

                                    
                                    $rwaName = DB::table('users')->where('id',isset($notification->data['co_follow_up']))->first();

                                    
                                    $currentEMP = DB::table('employees')->where('id',$notification->data['current_empid'])->first();
                                
                            @endphp
                             @if ($leadNotification == null)
                                
                            @else 
                            @if ($notification->data['leads_status'] == 1 || $leadNotification->next_follow_up_date >= $today  && $notification->data['leads_status'] == 5 && !isset($notification->data['co_follow_up']))
                            @if($leadNotificationHis > 1)
                            @if ($notification->read_at == null)
                            <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                class="dropdown-item notify-item active " style="background:#ffffff;  margin-bottom:0; padding-bottom:0; font-size:12px">
                                <span class="text-warning float-right"
                                    style="font-size: 40px;  margin-top: -20px; ">•</span>
                                    
                                <p class="text-capitalize  " style=" margin-bottom:0; padding-bottom:0; font-size:12px">Lead Updated By : {{ $currentEMP ? $currentEMP->employee_name : "" }} </p>

                                <p class="text-capitalize text-muted mb-0 user-msg"
                                    style="margin-left:0!important; margin-bottom:0; padding-bottom:0; font-size:12px">
                                    {{ $notification->data['lead_name'] }} |
                                    {{ $notification->data['property_requirement'] }} |
                                    {{ $notification->data['location'] }} |
                                    {{ $notification->data['number_of_units'] }} |
                                    {{ $notification->data['budget'] }}
                                </p>
                                <small class="text-muted">{{ $diff }}</small>
                                <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                    <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                        <span class="">clear</span>  
                                    </a>
                                    <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                </div> 
                            </a>
                        @else
                            <div class="d-flex justify-content-end">
                                <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                    class="dropdown-item notify-item active " style="background:#eceff1; margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                    <span class="text-warning float-right"
                                        style="font-size: 40px;  margin-top: -20px; ">•</span>
                                    <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> New Lead : {{ $currentEMP->employee_name }} </p>

                                    <p class="text-capitalize text-muted mb-0 user-msg"
                                        style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{ $notification->data['lead_name'] }} |
                                        {{ $notification->data['property_requirement'] }} |
                                        {{ $notification->data['location'] }} |
                                        {{ $notification->data['number_of_units'] }} |
                                        {{ $notification->data['budget'] }}
                                    </p>
                                    <small class="text-muted">{{ $diff }}</small>
                                    <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                        <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                            <span class="">clear</span>  
                                        </a>
                                        <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                    </div>
                                </a>


                                {{-- <a href="{{ route('read-single-notification-clear',$notification->id) }}" class="dropdown-item notify-item active notif-clear" style="background:#fff; border-bottom: 1px solid #ddd;"> 
                        <span class="">clear</span>
                    </a> --}}
                            </div>
                        @endif
                            @else
 @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff;  margin-bottom:0; padding-bottom:0; font-size:12px">
                                        <span class="text-warning float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            
                                        <p class="text-capitalize  " style=" margin-bottom:0; padding-bottom:0; font-size:12px"> New Lead : {{ $currentEMP ? $currentEMP->employee_name : "" }} </p>

                                        <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important; margin-bottom:0; padding-bottom:0; font-size:12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                        <small class="text-muted">{{ $diff }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div> 
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1; margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                            <span class="text-warning float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> New Lead : {{ $currentEMP->employee_name }} </p>

                                            <p class="text-capitalize text-muted mb-0 user-msg"
                                                style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $notification->data['lead_name'] }} |
                                                {{ $notification->data['property_requirement'] }} |
                                                {{ $notification->data['location'] }} |
                                                {{ $notification->data['number_of_units'] }} |
                                                {{ $notification->data['budget'] }}
                                            </p>
                                            <small class="text-muted">{{ $diff }}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>


                                        {{-- <a href="{{ route('read-single-notification-clear',$notification->id) }}" class="dropdown-item notify-item active notif-clear" style="background:#fff; border-bottom: 1px solid #ddd;"> 
                                <span class="">clear</span>
                            </a> --}}
                                    </div>
                                @endif
                            @endif
                               

                                @elseif(isset($notification->data['co_follow_up']) && $notification->data['co_follow_up'] == Auth::user()->id)
                                @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff; padding-bottom: 0 !important;">
                                        <span class="text-danger float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                        <p class="text-capitalize  text-wrap" style="margin-bottom:0; font-size:12px"> 
                                            {{ $currentEMP->employee_name . ' Made Follow-Up Buddy '. Auth::user()->name }}
                                        </p>

                                        <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important; margin-bottom:0; font-size:12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                        <small class="text-muted">{{ $diff }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1;margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                            <span class="text-danger float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $currentEMP->employee_name . ' Made Follow-Up Buddy '. $rwaName->name }}
                                            </p>

                                            <p class="text-capitalize text-muted mb-0 user-msg"
                                                style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $notification->data['lead_name'] }} |
                                                {{ $notification->data['property_requirement'] }} |
                                                {{ $notification->data['location'] }} |
                                                {{ $notification->data['number_of_units'] }} |
                                                {{ $notification->data['budget'] }}
                                            </p>
                                            <small class="text-muted">{{ $diff }}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                
                            @elseif($notification->data['leads_status'] == 15)
                            @php
                                    // $priEmpname = DB::table('employees')->where('id',$notification->data['privios_emp'])->first();

                                    $currentEMP = DB::table('employees')
                                    ->where('id',$notification->data['current_empid'])
                                    ->first();  
                                @endphp
                            @if ($notification->read_at == null)
                            <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                class="dropdown-item notify-item active " style="background:#ffffff; margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                <span class="text-danger float-right"
                                    style="font-size: 40px;  margin-top: -20px; ">•</span>
                                <p class="text-capitalize  text-wrap" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> 
                                    
                                    {{ $currentEMP->employee_name }}’s Booking for {{ $notification->data['lead_name'] }} has been cancelled. Continue to follow-up to save your booking. </p>

                                {{-- <p class="text-capitalize text-muted mb-0 user-msg"
                                    style="margin-left:0!important">
                                    {{ $notification->data['lead_name'] }} |
                                    {{ $notification->data['property_requirement'] }} |
                                    {{ $notification->data['location'] }} |
                                    {{ $notification->data['number_of_units'] }} |
                                    {{ $notification->data['budget'] }}
                                </p> --}}
                                <small class="text-muted">{{ $diff }}</small>
                                <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                    <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                        <span class="">clear</span>  
                                    </a>
                                    <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                </div>
                            </a>
                        @else
                            <div class="d-flex justify-content-end">
                                <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                    class="dropdown-item notify-item active " style="background:#eceff1; margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                    <span class="text-danger float-right"
                                        style="font-size: 40px;  margin-top: -20px; ">•</span>
                                    <p class="text-capitalize text-wrap" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> Next Follow-Up Time Lapsed : {{ $currentEMP->employee_name }}</p>

                                    <p class="text-capitalize text-muted mb-0 user-msg"
                                        style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        {{ $notification->data['lead_name'] }} |
                                        {{ $notification->data['property_requirement'] }} |
                                        {{ $notification->data['location'] }} |
                                        {{ $notification->data['number_of_units'] }} |
                                        {{ $notification->data['budget'] }}
                                    </p>
                                    <small class="text-muted">{{ $diff." ".$diffTime}}</small>
                                    <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                        <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                            <span class="">clear</span>  
                                        </a>
                                        <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                    </div>
                                </a>
                                </a>
                            </div>
                        @endif

                            @elseif($leadNotification->next_follow_up_date < $today && $notification->data['leads_status'] == 5 )
                                @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff; padding-bottom: 0 !important;">
                                        <span class="text-danger float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                        <p class="text-capitalize  text-wrap" style="margin-bottom:0; font-size:12px"> Next Follow-Up Time Lapsed  : {{ $currentEMP->employee_name ?? "" }}</p>

                                        <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important; margin-bottom:0; font-size:12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                        <small class="text-muted">{{ $diff." ".$diffTime }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1;margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                            <span class="text-danger float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> Next Follow-Up Time Lapsed </p>

                                            <p class="text-capitalize text-muted mb-0 user-msg"
                                                style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $notification->data['lead_name'] }} |
                                                {{ $notification->data['property_requirement'] }} |
                                                {{ $notification->data['location'] }} |
                                                {{ $notification->data['number_of_units'] }} |
                                                {{ $notification->data['budget'] }}
                                            </p>
                                            <small class="text-muted">{{ $diff." ".$diffTime}}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                @elseif($notification->data['privios_emp'] != $notification->data['current_empid'])
                                @php
                                    $priEmpname = DB::table('employees')->where('id',$notification->data['privios_emp'])->first();

                                    $currentEMP = DB::table('employees')->where('id',$notification->data['current_empid'])->first();
                                @endphp
                                @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff; margin-bottom: 0; padding-bottom: 0; font-size: 12px  border-bottom: 1px solid #ddd;">
                                        <span class="text-success float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                        <p class="text-capitalize text-wrap " style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            Lead Assigned Changed by {{ $currentEMP->employee_name ?? "N/A" }} to {{$priEmpname->employee_name ?? "N/A" }} :
                                            {{ $notification->data['lead_name'] }}</p>
                                            <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                        <small class="text-muted">{{ $diff }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1;margin-bottom: 0; padding-bottom: 0; font-size: 12px ">
                                            <span class="text-success float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            <p class="text-capitalize text-wrap" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">  
                                                Lead Assigned Changed by {{ $currentEMP->employee_name }} to {{$priEmpname->employee_name}} :
                                                {{ $notification->data['lead_name'] }}</p>

                                                <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                            <small class="text-muted">{{ $diff }}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>

                                    </div>
                                @endif

                            @elseif($notification->data['leads_status'] == 14)
                            
                                @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        <span class="text-success float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                        <p class="text-capitalize text-wrap " style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> 
                                            Congratulations {{ $emName ? $emName->employee_name : "N/A"}} for the booking of  {{ $notification->data['lead_name'] }}. Keep up the good work.Congratulations 
                                           </p>
                                            <span class="text-success float-right"
                                                   style="font-size: 20px;  margin-top: -5px; ">&#127881;</span>
                                        <small class="text-muted">{{ $diff }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1; margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            <span class="text-success float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                                <p class="text-capitalize text-wrap " style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> 
                                                    Congratulations {{ $emName ? $emName->employee_name : "N/A"}} for the booking of  {{ $notification->data['lead_name'] }}. Keep up the good work.Congratulations
                                                    
                                                   </p>
                                                    <span class="text-success float-right"
                                                   style="font-size: 20px;  margin-top: -5px; ">&#127881;</span>
                                            <small class="text-muted">{{ $diff }}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>

                                    </div>
                                @endif
                            @else
                                @if ($notification->read_at == null)
                                    <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                        class="dropdown-item notify-item active " style="background:#ffffff;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                        <span class="text-success float-right"
                                            style="font-size: 40px;  margin-top: -20px; ">•</span>
                                            @if ($emName ==null)
                                            <p class="text-capitalize" style=" margin-bottom:0; padding-bottom:0; font-size:12px"> Next Follow Up Notification </p>
                                            
                                        @else
                                        @if (isset($notification->data['message']) != null)
                                            <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px">   
                                                {{  $notification->data['message'] }}
                                            </p>
                                        @else
                                            {{  isset($notification->data['messageUpdateBy']) ? $notification->data['messageUpdateBy'] : ""  }}
                                        @endif
                                        @endif


                                        <p class="text-capitalize text-muted mb-0 user-msg"
                                            style="margin-left:0!important; margin-bottom:0; padding-bottom:0; font-size:12px">
                                            {{ $notification->data['lead_name'] }} |
                                            {{ $notification->data['property_requirement'] }} |
                                            {{ $notification->data['location'] }} |
                                            {{ $notification->data['number_of_units'] }} |
                                            {{ $notification->data['budget'] }}
                                        </p>
                                        <small class="text-muted">{{ $diff }}</small>
                                        <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                            <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                <span class="">clear</span>  
                                            </a>
                                            <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}"
                                            class="dropdown-item notify-item active " style="background:#eceff1;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                            <span class="text-success float-right"
                                                style="font-size: 40px;  margin-top: -20px; ">•</span>
                                                @if ($emName ==null)
                                                <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> Lead Status Updated by 
                                                
                                                    {{"NA"}} </p>
                                                
                                            @else
                                            <p class="text-capitalize" style="margin-bottom: 0; padding-bottom: 0; font-size: 12px"> Lead Status Updated by 
                                                
                                                {{ $emName->employee_name  }} </p>
                                            @endif

                                            <p class="text-capitalize text-muted mb-0 user-msg"
                                                style="margin-left:0!important;margin-bottom: 0; padding-bottom: 0; font-size: 12px">
                                                {{ $notification->data['lead_name'] }} |
                                                {{ $notification->data['property_requirement'] }} |
                                                {{ $notification->data['location'] }} |
                                                {{ $notification->data['number_of_units'] }} |
                                                {{ $notification->data['budget'] }}
                                            </p>
                                            <small class="text-muted">{{ $diff }}</small>
                                            <div  class="dropdown-item notify-item active notif-clear  " style="background:#fff; border-bottom: 1px solid #ddd;  margin-bottom:0; padding-top:0 !important; font-size:11px">
                                                <a href="{{ route('read-single-notification-clear',$notification->id) }}">  
                                                    <span class="">clear</span>  
                                                </a>
                                                <span class="float-right">{{ $date->format('d-M-Y H:i') }}</span>
                                            </div>
                                        </a>

                                        {{-- <a href="{{ route('read-single-notification-clear',$notification->id) }}" class="dropdown-item notify-item active notif-clear"  style="background:#fff; border-bottom: 1px solid #ddd; margin-bottom: 0; padding-top: 0 !important; font-size: 11px"> 
                                <span class="">clear</span>
                                </a> --}}
                                    </div>
                                @endif
                            @endif
                            @endif
                        @endforeach

                    </div>

                    <!-- All-->
                    {{-- <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all
                        <i class="fe-arrow-right"></i>
                    </a> --}}

                </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    @if (File::exists(Auth::user()->profile_picture))
                        <img src="{{ '/' . Auth::user()->profile_picture }}" alt="table-user"
                            class="mr-0 rounded-circle" controls preload="none" />
                    @else
                        <img src="{{ url('') }}/assets/images/users/no.png" alt="table-user"
                            class="mr-0 rounded-circle" controls preload="none" />
                    @endif

                    <span class="pro-user-name ml-1">
                        {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('admin-profile') }}" class="dropdown-item notify-item"
                    @if (Auth::user()->roles_id != 1) style="display:none;" @endif>
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('admin-setting') }}" class="dropdown-item notify-item"
                    @if (Auth::user()->roles_id  != 1) style="display:none;" @endif>
                        <i class="fe-settings"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                     {{-- <a href="{{ route('lock-screen') }}" class="dropdown-item notify-item">
                        <i class="fe-lock"></i>
                        <span>Lock Screen</span>
                    </a> --}}

                   
                        <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout"></i>
                            <span> Logout </span>
                        </a>

                    {{-- <div class="dropdown-divider"></div> --}}

                    <!-- item-->
                    {{-- <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a> --}}

                </div>
            </li>



        </ul>

        <!-- LOGO -->
        <div class="logo-box d-none d-sm-block">
            <a href="{{ route('leads-index') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ url('') }}/assets/images/logo-sm.png" alt="" height="22">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{ url('') }}/assets/images/logo-dark.png" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>
            @php
                $logo = DB::table('settings')->first();
                // dd($logo);
            @endphp
            <a href="{{ route('leads-index') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <h4 class="text-white font-weight-bold text-center mt-3">{{ $logo->site_name }}</h4>
                    {{-- <img src="{{ url('') }}/assets/images/logo-sm.png" alt="" height="22">   --}}
                </span>
                <span class="logo-lg">
                    <h3 class="text-white font-weight-bold text-center mt-3">{{ $logo->site_name }}</h3>
                    {{-- <img src="{{ url('') }}/assets/images/logo-light.png" alt="" height="20">  --}}
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>


        </ul>
        
         <!-- LOGO -->
<!--        <div class="logo-box d-block d-sm-none" style="width: 144px !important">
            <a href="{{ route('leads-index') }}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ url('') }}/assets/images/logo-sm.png" alt="" height="22">
                     <span class="logo-lg-text-light">UBold</span> 
                </span>
                <span class="logo-lg">
                    <img src="{{ url('') }}/assets/images/logo-dark.png" alt="" height="20">
                     <span class="logo-lg-text-light">U</span> 
                </span>
            </a>-->
            @php
                $logo = DB::table('settings')->first();
                // dd($logo);
            @endphp
<!--            {{-- <a href="{{ route('leads-index') }}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <h4 class="text-white font-weight-bold text-center mt-3" style="margin-top: 1.7rem !important">{{ $logo->site_name }}</h4>
                      <img src="{{ url('') }}/assets/images/logo-sm.png" alt="" height="22">  
                </span>
                <span class="logo-lg">
                    <h3 class="text-white font-weight-bold text-center mt-3" style="margin-top: 1.7rem !important">{{ $logo->site_name }}</h3>
                     <img src="{{ url('') }}/assets/images/logo-light.png" alt="" height="20">  
                </span>
            </a> --}}
        </div>-->
        
<!--        <div class="clearfix"></div>-->
    </div>
</div>
