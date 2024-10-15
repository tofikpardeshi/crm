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

                    </div>
                    <h4 class="page-title">{{ $buyersellername }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg-8">
                                {{-- <form class="form-inline" method="get" action="{{ url('search-lead') }}">
                                    <div class="form-group ">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" name="search" class="form-control" id="inputPassword2"
                                            placeholder="Search...">
                                    </div>
                                </form> --}}


                            </div>
                            <div class="col-sm-4">
                                <div class="text-sm-right">
                                    {{-- <button type="button" class="btn btn-success waves-effect waves-light mb-2 mr-1"><i
                                            class="mdi mdi-cog"></i></button> --}}
                                    <a type="button" class="btn btn-danger waves-effect waves-light mb-2"
                                        href="{{ url('project') }}">Back</a>
                                </div>
                            </div><!-- end col-->
                        </div>



                    </div> <!-- end card-body-->
                </div> <!-- end card-->

                @foreach ($projectBuyerSeller as $item)
                    <div class="card-box mb-2">
                        <div class="row align-items-center">
                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }} </div>
                            @endif
                            <div class="col-sm-3">
                                <div class="media">
                                    {{-- @if (File::exists($projectTeams->project_image))
                                        <img src="{{ '/'.$projectTeams->project_image }}" alt="table-user"
                                            class="d-flex align-self-center mr-3 rounded-circle" controls preload="none" height="64"/>
                                    @else
                                        <img src="{{ url('') }}/assets/images/users/no.png" alt="table-user"
                                            class="d-flex align-self-center mr-3 rounded-circle" controls preload="none" height="64"/>
                                    @endif --}}

                                    <div class="media-body">
                                        <h4 class="mt-0 mb-2 font-15">{{ $projectTeams->project_name }}</h4>
                                        <p class="mb-1"><b>Location:</b> {{ $projectTeams->location }}</p>
                                        @php
                                            $category = DB::table('category')
                                                ->join('projects', 'category.id', '=', 'projects.project_category')
                                                ->where('category.id', $projectTeams->project_category)
                                                ->select('category.category_name')
                                                ->first();
                                            
                                            // print_r($category);
                                            
                                        @endphp
                                        @if ($category == null)
                                            <p class="mb-0"><b>Category:</b> {{ 'N/A' }}
                                            </p>
                                        @else
                                            <p class="mb-0"><b>Category:</b> {{ $category->category_name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                @if ($projectTeams->email == null)
                                    <p class="mb-1 mt-3 mt-sm-0">
                                        <i class="mdi mdi-email mr-1"></i>
                                        N/A
                                    </p>
                                @else
                                    <p class="mb-1 mt-3 mt-sm-0">
                                        <i class="mdi mdi-email mr-1"></i>
                                        {{ $projectTeams->email }}
                                    </p>
                                @endif

                                @if ($projectTeams->contact_number == null)
                                    <p class="mb-0"><i class="mdi mdi-phone-classic mr-1"></i>
                                        N/A</p>
                                @else
                                    <p class="mb-0"><i class="mdi mdi-phone-classic mr-1"></i>
                                        {{ $projectTeams->contact_number }}</p>
                                @endif

                            </div>
                            <div class="col-sm-3">
                                <div class="text-center mt-3 mt-sm-0 d-flex">

                                    {{-- <div class="badge font-14 bg-soft-info text-info p-1"> --}}
                                    <p class="mb-1 text-capitalize"><b>Lead Name:</b>
                                        {{ $item->lead_name }}</p>

                                    {{-- </div>   --}}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="text-sm-right">
                                    <a href="{{ url('lead-status/' . encrypt($item->id)) }}" class="action-icon">
                                        <img style="width:20px; margin-bottom:2px"
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                    </a>

                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row -->
                        <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                            {{-- {{ $projectTeams->links('pagination::bootstrap-4'); }} --}}
                        </ul>
                    </div> <!-- end card-box-->
                @endforeach

            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection


@section('scripts')
    {{-- modal in latavel --}}
    {{-- <script>
        $(document).ready(function() {
            $(document).on('click', '.updateStatus', function() {
                var lead_id = $(this).val();
                // alert(lead_id);

                $('#lead_id').val(lead_id)
                $('#exampleModal').modal('show');


            })
        })
    </script> --}}
    {{-- modal in latavel End --}}
@endsection
