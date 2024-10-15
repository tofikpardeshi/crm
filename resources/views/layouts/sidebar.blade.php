<style>
    /* .left-side-menu {background-color: #FF8500 !important} */
     /* .sidebar-menu>ul {list-style-type: none} 
    .text-white {
    color: #fff !important; */
/* } */
</style>
      


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="h-100" data-simplebar>

                    <!-- User box -->
                    <div class="user-box text-center">
                        <img src="assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme"
                            class="rounded-circle avatar-md">
                        <div class="dropdown">
                            <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                                data-toggle="dropdown">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu user-pro-dropdown">

                                <!-- item-->
                                <a href="{{ route('admin-profile') }}" class="dropdown-item notify-item">
                                    <i class="fe-user mr-1"></i>
                                    <span>My Account</span>
                                </a>

                                <!-- item-->
                                <a href="{{ route('admin-setting') }}" class="dropdown-item notify-item">
                                    <i class="fe-settings mr-1"></i>
                                    <span>Settings</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="fe-lock mr-1"></i>
                                    <span>Lock Screen</span>
                                </a>

                                <!-- item-->
                                {{-- <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                                    <i class="fe-log-out mr-1"></i>
                                    <span>Logout</span>
                                </a> --}}

                            </div>
                        </div>
                        <p class="text-muted">Admin Head</p>
                    </div>

                    @php
                         $role_id =  Auth::user()->roles_id;
                            $rolePermission = DB::table('roles')->where('id',$role_id)
                          ->select("roles.name")->first();

                          $user  = Auth::user()->getRoleNames();
                          $permission = Auth::user()->getPermissionsViaRoles();
                          $user->contains($permission);
                        //   dd($permission);
                        //    $permission = Spatie\Permission\Models\Permission::table()->get();
                              //dd($user);
                    @endphp 
                        
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        

                        <ul id="side-menu">
 

                           @can('Leads')
<!--		             <li  class="d-xl-none">
		                 <a class="active"  >
		                     {{-- <i class="mdi mdi-poll"></i>
		                     <span> Leads </span> --}}
		                 </a>
		                 <a class="active"  >
		                    {{-- <i class="mdi mdi-poll"></i>
		                    <span> Leads </span> --}}
		                </a> 

		             </li>
		             <li  class="d-xl-none">
		                 <a class="active"  >
		                     {{-- <i class="mdi mdi-poll"></i>
		                     <span> Leads </span> --}}
		                 </a>
		                 <a class="active"  >
		                    {{-- <i class="mdi mdi-poll"></i>
		                    <span> Leads </span> --}}
		                </a> 

		             </li>-->
                 	@endcan

		         @can('Leads')
		             <li>
		                 <a class="active" href="{{ route('leads-index') }}">
		                     <i class="mdi mdi-poll"></i>
		                     <span> Leads </span>
		                 </a>
		             </li>
		         @endcan
                 
                            @if (Auth::user()->roles_id != 8)

                 	@can('Dashboard')
		        <li >
		            <a  href="{{ route('dashboard') }}">
		                <i class="mdi mdi-view-dashboard-outline"></i>
		                <span> Dashboard </span>
		            </a>
		        </li>
		        @endcan

		        
		         @can('Location Dashboard')
		         <li >
		            <a  href="{{ route('location-dashboard') }}">
		                <i class="mdi mdi-view-dashboard-outline"></i>
		                <span> Location Dashboard </span>
		            </a>
		        </li>
		        @endcan 
		        
		        
                 @endif
                 
                  @if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11)
                     
                 <li>
                    <a href="{{ route('employee-productivity') }}">
                        <i class="fa fa-industry"></i>
                        <span> Productivity </span>
                    </a>
                </li>
                 @endif


                            @can('Common Pool')

                            <li>
                                <a href="{{ route('common-pool') }}">
                                    <i class="fab fa-creative-commons"></i>
                                    <span> Common Pool </span>
                                </a>
                            </li>

                            @endcan


                            @can('Projects') 
                            <li>
                                <a href="{{ route('project-index') }}">
                                    <i class="mdi mdi-briefcase-check-outline"></i>
                                    <span> Projects </span>
                                </a>
                            </li> 

                            @endcan
                            
                        
                     {{-- @can('Developers') --}}
                     <li @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                         <a href="{{ route('developer-index') }}">
                             <i class="mdi mdi-briefcase-check-outline"></i>
                             <span > Developer </span>
                         </a>
                     </li>
                   {{-- @endcan --}}
               

                            @can('Builder/CP Team Names')
                            <li>
                                <a href="{{ route('create-builder-view') }}">
                                    <i class="mdi mdi-account-hard-hat"></i>
                                    <span> Builder/CP Team Names </span>
                                </a>
                            </li>
                            @endcan

                            @can('Bulk Upload')
                            <li>
                                <a href="{{ route('bulk-upload') }}">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    <span> Bulk Upload </span>
                                </a>
                            </li>

                            @endcan

                            @can('Employee')
                            <li>
                                <a href="{{ route('employees-index') }}">
                                    <i class="mdi mdi-account-multiple-outline"></i>
                                    <span> Manage User ID </span>
                                </a>
                            </li> 


                            <li>
                                <a href="{{ route('user-location') }}">
                                    {{-- <i class="mdi mdi-account-multiple-outline"></i> --}}
                                    <i class="fa fa-users" style="font-size:14px" aria-hidden="true"></i> 
                                    <span>Login Logs</span>
                                </a>
                            </li> 
                            @endcan
                            
                            <li>
                                <a href="{{ route('notification') }}">
                                    {{-- <i class="mdi mdi-account-multiple-outline"></i> --}}
                                    <i class="fa fa-bell" style="font-size:14px" aria-hidden="true"></i> 
                                    <span>Notification</span>
                                </a>
                            </li> 
                            
                            <li @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
				    <a href="{{ route('crm-toolbar') }}">
				        {{-- <i class="mdi mdi-account-multiple-outline"></i> --}} 
				        <i class="fa fa-toolbox" style="font-size:14px" aria-hidden="true"></i>
				        <span>CRM Links and Url </span>
				    </a>
		        </li>

                            @can('Booking Confirm')
                            <li>
                                <a href="{{ route('booking-index') }}">
                                    <i class="mdi mdi-book"></i>
                                    <span> Deal Confirmed </span>
                                </a>
                            </li> 
                            @endcan
                             
                             

                            @can('Roles')
                                 
                            <li>
                                <a href="{{ route('role-index') }}">
                                    <i class="mdi mdi-layers-outline"></i>
                                    <span> Roles </span>
                                </a>
                            </li>

                            @endcan

                            @can('Location') 
                            <li>
                                <a href="{{ Route('location') }}">
                                    <i class="mdi mdi-book-account-outline"></i>
                                    <span> Location </span>
                                </a>
                            </li>
                            @endcan
                            
                            {{-- @can('Common Pool') --}}
                      @if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11)
                            <li>
                                <a href="{{ route('global-search-details') }}">
                                    <i class="fa fa-globe"></i>
                                    <span> Trail Logs </span>
                                </a>
                            </li>
                            @endif
                 {{-- @endcan --}}
                            
                            @can('Settings') 
                            <li @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                                <a href="{{ route('admin-setting') }}">
                                    <i class="mdi mdi-cog"></i>
                                    <span> Settings </span>
                                </a>
                            </li>
                            @endcan
                            
                            <li @if (Auth::user()->roles_id == 10) style="display:none;" @endif>
                                <a href="{{ route('admin-profile') }}">
                                    <i class="mdi mdi-account-circle-outline"></i>
                                    <span> Profile </span>
                                </a>
                            </li> 

                            <li>
                                <a href="{{ url('logout') }}">
                                    <i class="mdi mdi-logout"></i>
                                    <span> Logout </span>
                                </a>
                            </li>


                            {{-- @endauth --}}
                           
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

           
