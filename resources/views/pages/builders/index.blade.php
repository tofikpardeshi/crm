@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Builder</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Builder</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                {{-- <form class="form-inline" method="get" 
                                action="{{ url('search-employee') }}">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" name="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form> --}}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                             @can('Developer Reports') 
                                            <a href="{{url('builder-excel')}}"  class="btn btn-info waves-effect waves-light  ">
                                                Builder Reports 
                                            </a> 
                                    @endcan
                                        @can('Create')
                                        <a type="button" class="btn btn-success waves-effect waves-light btn-darkblue"
                                        href="{{ route('create-builder-view') }}">Add New</a>
                                        @endcan
                                   
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            @if (Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('success') }}</h5>
                                    </div>
                                @endif
                                @if (Session::has('delete'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <h5 class="text-center">{{ Session::get('delete') }}</h5>
                                    </div>
                                @endif
                            
                            <table class="table table-centered table-nowrap table-hover mb-0" id="demo-foo-filtering">
                                <thead>
                                    <tr>
                                        {{-- <th>Builder Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Value</th>
                                        <th>Project Assign</th>
                                        <th>Designation</th> --}}
                                        {{-- <th>Created Date</th> --}}
                                        {{-- <th style="width: 82px;">Action</th> --}}
                                        <th style="width: 82px;">Action</th>
                                        <th>Builder/CP Team Names</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Alt Contact Number</th>
                                        <th>Builder/CP/Individual</th>
                                        <th>Name of Developer</th>
                                        <th>Project Assign</th>
                                        <th>Designation</th>
                                        <th>Delate</th> 
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($builders as $builder)
                                    <tr onmousedown='return false;' onselectstart='return false;' data-placement="top" title="Do not copy sensitive data"
                                    >
                                        
                                    <td> 
                                        @can('Update')
                                        <a href="{{ url('edit-builder/'.$builder->id) }}" class="action-icon btn btn-warning btn-xs text-light mr-2"> 
                                            <i class="mdi mdi-square-edit-outline">
                                                </i></a>
                                        @endcan 

                                        <a class="btn" href="{{ url('builder-details/'.$builder->id) }}" target="_blank">
                                            <img style="width:20px; margin-bottom:2px"
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                        </a>
                                    </td>
                                        <td>
                                            <a href="{{ url('builder-details/'.$builder->id) }}" class="text-muted" target="_blank">
                                            {{ $builder->team_name ? $builder->team_name : 'N/A' }}
                                            </a>
                                        </td>
                                        
                                        @if ($builder->team_email)
                                        <td>{{ $builder->team_email}}
                                            <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to={{ $builder->team_email }}"
                                                target="_blank"> 
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td>{{ 'N/A'}}</td>
                                        @endif
                                        
                                        
                                        <td>
                                            @if ($builder->team_phone_number )
                                            <a class="text-muted" href="tel:{{ $builder->team_phone_number }}" > 
                                                {{ Auth::user()->roles_id == 1 ? $builder->team_phone_number : substr_replace($builder->team_phone_number, '******', 0, 6) }} 
                                            </a>
                                            <a href="https://api.whatsapp.com/send/?phone={{'91'}}{{$builder->team_phone_number }}" target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>
                                            @else
                                                {{ 'N/A' }}
                                            @endif 
                                        </td>  
                                        <td>
                                            @if ($builder->alternate_contact_number_team )
                                            <a class="text-muted" href="tel:{{ $builder->alternate_contact_number_team }}" > 
                                                {{ Auth::user()->roles_id == 1 ? $builder->alternate_contact_number_team : substr_replace($builder->alternate_contact_number_team, '******', 0, 6) }} 
                                            </a>
                                            <a href="https://api.whatsapp.com/send/?phone={{'91'}}{{$builder->alternate_contact_number_team }}" target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>
                                            @else
                                                {{ 'N/A' }}
                                            @endif 
                                        </td> 
                                        @php
                                            $BuilderValue = DB::table('teams')
                                            ->join('builders','teams.builder_id','=','builders.id')
                                            ->where('builders.id',$builder->builder_id)
                                            ->select('builders.name')
                                            ->first(); 
                                        @endphp
                                            
                                            <td>{{ $BuilderValue->name ?? 'N/A' }}</td>
                                           
                                            
                                            @php
                                                 $selected = explode(',',$builder->project_id);
                                                 $nameofDeveloper = DB::table('teams')
                                                    ->join('name_of_developers', 'teams.name_of_developer', '=', 'name_of_developers.id')
                                                    ->where('name_of_developers.id', $builder->name_of_developer)
                                                    ->select('name_of_developers.name_of_developer')
                                                    ->first();
                                            @endphp 
                                            
                                            <td> {{ $nameofDeveloper->name_of_developer ?? "N/A" }}</td>
                                        
                                            <td class="text-wrap"> 
                                               
                                                  @php
                                                     $s = ''; 
                                                @endphp
                                                @foreach ($selected as $item)
                                                @php  
                                                     $name = DB::table('projects')->where('id',$item)->first();
                                                    // dd($name->project_name);
                                                @endphp 
                                                @if ($name == null)
                                                    {{ "N/A" }}
                                                @else
                                                {{ $s.$name->project_name }}
                                                @php
                                                    $s = ', ';
                                                @endphp
                                                @endif 
                                                @endforeach  
                                            </td>  

                                        <td>{{ $builder->designation_name }}</td> 
                                        
                                        <td> @if (Auth::user()->roles_id == 1)
                                            <a  
                                          href="{{ url('builder-delete/'.$builder->id) }}" class="action-icon btn btn-danger btn-xs text-light"> 
                                             <i class="mdi mdi-delete"></i>
                                         </a>   
                                         @endif</td>
                                        
                                     </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>

                         {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{ $builders->links('pagination::bootstrap-4'); }}
                        </ul>    --}}

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('scripts')

<style>
    #demo-foo-filtering_length{
        display: none;
    }
</style>
 
<script>
     $('#demo-foo-filtering').dataTable( {
         lengthMenu: [
             [100,75, 50,25,  -1],
             [100,75, 50,25, 'All'],
         ],
        //  processing: true, 
     });

    
</script>

@endsection


