    @extends('main')

    @section('dynamic_page') 
        <div class="row">
            <div class="col-lg-12 my-3">
                <div class="card">
                    <div class="container">
                        <div class="d-flex justify-content-between">
                            <h2 class="page-title mt-3 ">Project Information</h2>
                            <div>
                                <a type="button" class="clipboard btn btn-primary waves-effect waves-light  mr-1"
                                    href="#">Copy Link</a>
                                <a type="button" class="btn btn-danger  m-2" href="{{ route('project-index') }}">Back</a>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2 ">Project Name: 
                                    <strong>{{ $projectDetails->project_name }}</strong> 
                                    <a href="https://www.google.com/search?q={{ $projectDetails->project_name }}" target="_blank">
                                        <i class="mdi mdi-google"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Registration Email: 
                                    @if ($projectDetails->email == null)
                                        {{"N/A"}}
                                    @else
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to={{ $projectDetails->email }}"
                                            target="_blank">
                                            <strong class="text-muted">{{ $projectDetails->email }} </strong>
                                            <i class="mdi mdi-email mr-1"></i>
                                        </a>
                                    @endif 
                                    
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @php
                                        $trimNumber = trim($projectDetails->contact_number);
                                        if ($projectDetails->contant_country_code ==null) {
                                            $emp_country_code= array('1' =>'', );
                                        } else {
                                            $country_code= explode('+',trim($projectDetails->contant_country_code));
                                            // dd($country_code[1]);
                                        } 

                                        $trimNumberalt = trim($projectDetails->contact_number);
                                        if ($projectDetails->alt_country_code ==null) {
                                            $emp_country_code= array('1' =>'', );
                                        } else {
                                            $country_code_alt= explode('+',trim($projectDetails->alt_country_code));
                                            // dd($country_code[1]);
                                        } 
                                    
                                @endphp
                                <div class="my-2">Registration Contact Number:
                                    @if ($projectDetails->contact_number == null)
                                        {{"N/A"}}
                                    @else
                                        <a class="text-muted" href="tel:{{$projectDetails->contant_country_code.$projectDetails->contact_number }}" > 
                                        {{ Auth::user()->roles_id == 1 ? $projectDetails->contant_country_code.$projectDetails->contact_number : substr_replace($projectDetails->contant_country_code. $projectDetails->contact_number, '*********', 0, 9) }}
                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code[1].$projectDetails->contact_number }}"
                                            target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>
                                        <a href="https://www.google.com/search?q={{ $projectDetails->contact_number }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                    </a> 
                                    @endif 
                                    
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Alternate Contact Number:
                                @if ($projectDetails->alternate_contact_number == null)
                                        {{"N/A"}}
                                    @else
                                    <a class="text-muted" href="tel:{{$projectDetails->alt_country_code. $projectDetails->alternate_contact_number }}" > 
                                        {{ Auth::user()->roles_id == 1 ? $projectDetails->alt_country_code. $projectDetails->alternate_contact_number : substr_replace( $projectDetails->alt_country_code.$projectDetails->alternate_contact_number, '*********', 0, 9) }}

                                        <a href="https://api.whatsapp.com/send/?phone={{ $country_code_alt[1].$projectDetails->contact_number }}"
                                            target="_blank">
                                                <i class="mdi mdi-whatsapp" aria-hidden="true"></i>
                                            </a>

                                        <a href="https://www.google.com/search?q={{ $projectDetails->alternate_contact_number }}" target="_blank">
                                            <i class="mdi mdi-google"></i>
                                        </a>
                                    </a> 
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">
                                    @php
                                        $projectCategory = DB::table('projects')
                                            ->join('category', 'projects.project_category', '=', 'category.id')
                                            ->select('category.category_name')
                                            ->where('projects.project_category', $projectDetails->project_category)
                                            ->first();
                                    @endphp
                                    Category: <strong>{{ $projectCategory->category_name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Sector: <strong>{{ $projectDetails->sector }}</strong></div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Location: <strong>{{ $projectDetails->location }}</strong></div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @php
                                    $space = $projectDetails->project_type;
                                    $spacebetweens = explode(',', $space);
                                @endphp

                                <div class="my-2">Project Type: <strong>
                                        @foreach ($spacebetweens as $item)
                                            {{ $item }} &nbsp;
                                        @endforeach
                                    </strong>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Rera Number: <strong>{{ $projectDetails->rera_number }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Name of Developer:
                                    @php
                                        $nameofDevelopers = DB::table('projects')
                                            ->join('name_of_developers', 'projects.name_of_developers', '=', 'name_of_developers.id')
                                            ->select('name_of_developers.name_of_developer')
                                            ->where('projects.name_of_developers', $projectDetails->name_of_developers)
                                            ->first();
                                    @endphp
                                    @if ($nameofDevelopers == null)
                                        N/A
                                    @else
                                        <strong>{{ $nameofDevelopers->name_of_developer }}</strong>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->total_no_of_units == null)
                                    <div class="my-2">No. of Units: <strong> N/A </strong>
                                    </div>
                                @else
                                    <div class="my-2">No. of Units: <strong>{{ $projectDetails->total_no_of_units }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->total_no_of_occupied_units == null)
                                    <div class="my-2">No. of Occupied Units:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-2">No. of Occupied Units:
                                        <strong>{{ $projectDetails->total_no_of_occupied_units }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->total_no_of_unoccupied_units == null)
                                    <div class="my-2">No. of UnOccupied Units:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-2">No. of UnOccupied Units:
                                        <strong>{{ $projectDetails->total_no_of_unoccupied_units }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->total_occupancy == null)
                                    <div class="my-2">Total Occupancy %: <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-2">Total Occupancy %:
                                        <strong>{{ $projectDetails->total_occupancy }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->project_launch_date == null)
                                    <div class="my-2">Project Launch Date:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-2">Project Launch Date:
                                        <strong>{{ \Carbon\Carbon::parse($projectDetails->project_launch_date)->format('jS F Y') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->project_completion_date == null)
                                    <div class="my-2">Project Completion Date:
                                        <strong>N/A</strong>
                                    </div>
                                @else
                                    <div class="my-2">Project Completion Date:
                                        <strong>{{ \Carbon\Carbon::parse($projectDetails->project_completion_date)->format('jS F Y') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">  
                                  @if ($projectDetails->project_website_link == null)
                                <div class="my-2">Project Website Link:
                                    <strong>N/A</strong>
                                </div>
                            @else
                                <div class="my-2">Project Website Link:
                                    @php 
                                    $websiteLink = $projectDetails->project_website_link; // Remove the extra '$$' here
                                
                                    // Add protocol if missing
                                    if (!preg_match('~^(?:f|ht)tps?://~i', $websiteLink)) {
                                        $websiteLink = 'http://' . $websiteLink;
                                    } 
                                    @endphp
                                    <a href="{{ $websiteLink }}" target="_blank"> 
                                        Link
                                    </a> 
                                </div>
                            @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->project_fb_group_link == null)
                                    <div class="my-2">Additonal Info Link:
                                        <strong>{{ $projectDetails->project_fb_group_link }}</strong>
                                    </div>
                                @else
                                    <div class="my-2">Additonal Info Link:
                                        @php 
                                    $websiteLink = $projectDetails->project_fb_group_link; // Remove the extra '$$' here
                                
                                    // Add protocol if missing
                                    if (!preg_match('~^(?:f|ht)tps?://~i', $websiteLink)) {
                                        $websiteLink = 'http://' . $websiteLink;
                                    } 
                                    @endphp
                                    <a href="{{ $websiteLink }}" target="_blank"> 
                                        Link
                                    </a> 
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Project Status:
                                    @php
                                        $projectStatus = DB::table('projects')
                                            ->join('project_status', 'projects.project_status_id', '=', 'project_status.id')
                                            ->select('project_status.status_name')
                                            ->where('projects.project_status_id', $projectDetails->project_status_id)
                                            ->first();
                                    @endphp
                                    @if ($projectStatus == null)
                                        N/A
                                    @else
                                        <strong>{{ $projectStatus->status_name }}</strong>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">


                                @if ($projectDetails->size_of_apartment == null)
                                    <div class="my-2">Size of Apartment:
                                        <strong>{{ 'N/A' }}</strong>
                                    </div>
                                @else
                                    <div class="my-2">Size of Apartment:
                                        <strong>{{ $projectDetails->size_of_apartment }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                @if ($projectDetails->price_of_apartment == null)
                                    <div class="my-2">Price of Apartment:
                                        <strong>{{ 'N/A' }}</strong>
                                    </div>
                                @else
                                    <div class="my-2 text-justify">Price of Apartment:
                                        {!!$projectDetails->price_of_apartment ?? 'N/A'!!}
                                    {{-- <strong>
                                    {{ strip_tags(str_replace(array("&#39;", "&quot;","&nbsp;"), 
                                                array("'", '"',' '), $projectDetails->price_of_apartment)) }} 
                                    </strong> --}}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="my-2">Upload Document:
                                    @if ($projectDetails->project_image == null)
                                        <strong>{{ 'N/A' }}</strong>
                                    @else
                                        <a href="{{ asset('/backend/project/' . $projectDetails->project_image) }}" download>
                                            <span> {{ $projectDetails->project_image }} </span>
                                        </a>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4 col-12">
                                @php
                                    $ProjectCreatedBy = DB::table('users')
                                    ->where('id', $projectDetails->created_by)
                                    ->first();
                                @endphp
                                <div class="my-2">Created By:
                                    {{ $ProjectCreatedBy ? $ProjectCreatedBy->name : null }}
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-lg-4 col-12"> 
                                <div class="my-2">Project Create Date:
                                    {{ \Carbon\Carbon::parse($projectDetails->created_at)->format('d-M-Y H:i') }}
                                </div>
                            </div>
                            
                            {{-- <div class="col-md-4 col-lg-4 col-12"> 
                                <div class="my-2">CC Mail: {{$projectDetails->project_cc_mail ?? 'N/A'}}</div>
                            </div> --}}

                            {{-- <div class="col-md-4 col-lg-4 col-12"> 
                                <div class="my-2 " >Project Plan Details: {!!$projectDetails->project_plan_details ?? 'N/A'!!}</div>
                            </div>  --}}
                        </div>
                        <div class="row d-flex justify-content-between mx-auto">
                            <h2 class="page-title ml-1">Project History</h2>
                            <div class="d-flex justify-content-between">
                                @if (count($projectHistorys) == 0)
                                    <a type="button" class="btn btn-danger  m-2" href="{{ route('project-index') }}">Back</a>
                                @else
                                    <div>
                                        {{-- <a href="{{ url('project-export', $projectID) }}">
                                            <button class="btn btn-info">Download</button>
                                        </a> --}}
                                    </div>
                                @endif
                            </div>
                            <table class="table table-responsive  table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Project Status</th>
                                        <th style="max-width:160px">Project Type</th>
                                        <th>No. of Units</th>
                                        <th>No. of Occupied Units</th>
                                        <th>No. of UnOccupied Units</th>
                                        <th>Total Occupancy %</th>
                                        <th>Updated By</th>
                                        <th>Price of Apartment</th>
                                        <th>Project Plan Details</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectHistorys as $projectHistory)
                                        @php
                                            $projectCategory = DB::table('category')
                                                ->join('project_historys', 'project_historys.project_category', '=', 'category.id')
                                                ->where('category.id', $projectHistory->project_category)
                                                ->select('category.*')
                                                ->first();
                                            
                                            $NameOfDeveloper = DB::table('name_of_developers')
                                                ->join('project_historys', 'project_historys.name_of_developers', '=', 'name_of_developers.id')
                                                ->where('name_of_developers.id', $projectHistory->name_of_developers)
                                                ->select('name_of_developers.*')
                                                ->first();
                                            
                                            $projectStatus = DB::table('project_historys')
                                                ->join('project_status', 'project_historys.project_status', '=', 'project_status.id')
                                                ->select('project_status.status_name')
                                                ->where('project_historys.project_status', $projectHistory->project_status)
                                                ->first();
                                        @endphp
                                        <tr>

                                            <td>{{ \Carbon\Carbon::parse($projectHistory->created_at)->format('d-M-Y H:i') }}
                                            </td>
                                            <td>{{ $projectStatus->status_name ?? 'N/A' }} </td>
                                            <td style="white-space:normal; min-width:160px; max-width:160px;" >{{ $projectHistory->project_type ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                {{ $projectHistory->total_no_of_units ? $projectHistory->total_no_of_units . ' Units' : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $projectHistory->total_no_of_occupied_units ? $projectHistory->total_no_of_occupied_units . ' Units' : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $projectHistory->total_no_of_unoccupied_units ? $projectHistory->total_no_of_unoccupied_units . ' Units' : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $projectHistory->total_occupancy ? $projectHistory->total_occupancy . '%' : 'N/A' }}
                                            </td>
                                            <td>
                                                @php
                                                    $ProjectUpdatedUserName = DB::table('users')
                                                        ->where('id', $projectHistory->created_by)
                                                        ->first();
                                                @endphp
                                                {{ $ProjectUpdatedUserName->name }}
                                            </td> 
                                            <td class="text-wrap text-justify"> 
                                                <div class="text-content">
                                                    @php
                                                        $projectDetailsPrice = $projectHistory->price_of_apartment ;
                                                        $wordsPrice = str_word_count($projectDetailsPrice, 2);
                                                        $limitedContentPrice = \Str::limit($projectDetailsPrice, 200);
                                                    @endphp
                                                    <span class="limited-text">{!! $limitedContentPrice ?? "N/A" !!}</span>
                                                    @if (count($wordsPrice) > 25)
                                                        <span class="hidden-text-price" style="display: none;">{!! substr($projectDetailsPrice, strlen(strip_tags($limitedContentPrice))) !!}</span>
                                                        <a href="#" class="read-more-price">Read more</a>
                                                    @endif
                                                </div> 
                                            </td>
                                            <td class="text-wrap text-justify">
                                                <div class="text-content">
                                                    @php
                                                        $projectDetails = $projectHistory->project_plan_details;
                                                        $words = str_word_count($projectDetails, 2);
                                                        $limitedContent = \Str::limit($projectDetails, 200);
                                                    @endphp
                                                    <span class="limited-text">{!! $limitedContent ?? "N/A" !!}</span>
                                                    @if (count($words) > 25)
                                                        <span class="hidden-text" style="display: none;">{!! substr($projectDetails, strlen(strip_tags($limitedContent))) !!}</span>
                                                        <a href="#" class="read-more">Read more</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> <!-- end card-body-->

                        {{-- {{ $leadstatushistory->links(); }} --}}

                    </div> <!-- end card-->
                </div> <!-- end col --> 
            </div>
        </div>
    @endsection

        @section('scripts')
            <script>
                var $temp = $("<input>");
                var $url = $(location).attr('href');
                $('.clipboard').on('click', function() {
                    $("body").append($temp);
                    $temp.val($url).select();
                    document.execCommand("copy");
                    $temp.remove();
                    //$("p").text("URL copied!");
                })
    
                
            </script>
            <script>
                $(document).ready(function(){
                    $('.read-more').click(function(e) {
                        e.preventDefault(); // Prevent the default link behavior
                        var hiddenText = $('.hidden-text');
                        hiddenText.toggle();
                
                        if (hiddenText.is(':visible')) {
                            $(this).text('Read less');
                        } else {
                            $(this).text('Read more');
                        }
                    });
                }); 
                
                </script>

                <script>
                      $(document).ready(function(){
                    $('.read-more-price').click(function(e) {
                        e.preventDefault(); // Prevent the default link behavior
                        var hiddenText = $('.hidden-text-price');
                        hiddenText.toggle();
                
                        if (hiddenText.is(':visible')) {
                            $(this).text('Read less');
                        } else {
                            $(this).text('Read more');
                        }
                    });
                }); 
                </script>
        @endsection



