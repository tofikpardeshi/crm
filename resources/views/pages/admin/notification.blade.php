@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
         
            <div class="col-12">
                
                
                <div class="col-12 d-flex justify-content-between">
               
                <div class="page-title-box">
                     
                    <h4 class="page-title">Notification</h4>
                     @if (session()->has('errorFilter'))
                        <div class="alert alert-danger mt-3 text-center" id="NoDataFound">
                            {{ session()->get('errorFilter') }} </div>
                    @endif
                </div>
 
            </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12">

		 

                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible text-center">
                        <h5>{{ Session::get('error') }}</h5>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible text-center">
                        <h5>{{ Session::get('success') }}</h5>
                    </div>
                @endif


                
                <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-end">
                         
                    <div class="card-body">
                        <div class="row"> 
                            @php 
                            $locations = DB::table('locations')->get();
                               $Employees = DB::table('employees')
                               ->select('employees.employee_name','employees.id')
                               ->orderBy('employee_name','asc')
                               ->get();
                               $ProjectName = DB::table('projects')
                               ->orderBy('project_name','asc')->get();
                               $CustomerTypes = DB::table('buyer_sellers')
                               ->select('name','id')
                               ->orderBy('name','asc')->get(); 
                               $employeeCoFollowUps = DB::table('employees')
                               ->select('employees.employee_name','employees.user_id')
                               ->orderBy('employee_name','asc')
                               ->get();   
                               $ChannelPartners= DB::table('users')
                               ->where('roles_id',10)
                               ->select('name','roles_id','id')
                               ->get();

                               $LeadStatus = DB::table('lead_statuses')
                                ->where('id','!=', 1)
                                ->where('id','!=', 2)
                                ->where('id','!=', 3)
                                ->where('id','!=', 4)
                                ->where('id','!=', 5)
                                ->where('id','!=', 6)
                                ->where('id','!=', 7)
                                ->where('id','!=', 14)
                                ->where('id','!=', 15)
                                ->where('id','!=', 17)
                                ->get();



                                $existingProjects = DB::table('projects')->select('project_name', 'id')->get()
                             @endphp
                               {{-- <div class="col-md-3 col-sm-6 col-6 mb-1">
                                 
                                    <label for=""> Employee</label> 
                                    <select id="filter-user" class="form-control" onchange="applyFilterUser()">
                                        <option value="">See All</option>
                                        @foreach ($Employees as $employee)
                                        
                                        <option value="{{ $employee->employee_name }}">
                                            {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for=""> Lead Status</label>
                                    <select id="filter-status" class="form-control" onchange="applyFilter()">
                                        <option value="">See All</option>
                                        @foreach ($LeadStatus as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for=""> Existing Property </label>
                                    <select id="existing-property" class="form-control" 
                                    onchange="ExistingApplyFilter()">
                                        <option value="">See All</option>
                                        @foreach ($existingProjects as $existingProject)
                                            <option value="{{ $existingProject->project_name }}">{{ $existingProject->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                
                                {{-- <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Date follow up</label>
                                    <td><input type="date" class="form-control" id="datefilterfrom"
                                            data-date-split-input="true" min="<?= //date('d-m-Y') ?>"
                                            onchange="FollowUpDateApplyFilter()"></td>
                                </div> --}}
                                {{-- <div class="col-md-3 col-sm-6 col-6 mb-1">
                                    <label for="">Creation Date</label>
                                    <td><input type="date" class="form-control" id="creation-date"
                                            data-date-split-input="true" min="<?= //date('d-m-Y') ?>"
                                            onchange="CreateDateApplyFilter()"></td>
                                </div> --}}

                                
    
                                {{-- <div class="col-md-3 col-sm-6 col-6 mb-1"> --}}
                                    {{-- <label for="">Previous Follow-up</label>
                                    <input type="date" class="form-control" id="datefilterPrevious"
                                        data-date-split-input="true" max="<?= //date('d-m-Y') ?>"> --}}
                                        {{-- <label for="">Free Search</label> --}}
                                        {{-- <form action="{{ route('free-search') }}" method="get">
                                            @csrf
                                            <td><input type="text" name="free_search"  class="form-control"></td> 
                                        </form> --}}
                                {{-- </div>  --}}
                                
                        </div>


                         <div>
                            <table id="datatable-buttons" class="table table-centered table-nowrap table-hover mb-0 table-responsive" data-placement="top">
                                <thead>
                                    <tr> 
                                        <td>Action</td>
                                        <th>Creation Date</th>
                                        <th>Customer Name</th>
                                        <th>Customer Type</th>
                                        <th>Buying Location</th>
                                        <th>Status</th> 
                                        <th>Notification</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notificationData as $notification) 
                                    @php
                                        $LeadStatusName = DB::table('lead_statuses')
                                        ->where('id',$notification->data['leads_status'])
                                        ->select('name')
                                        ->first();

                                        $currentEMP = DB::table('employees')->where('id',$notification->data['current_empid'])->first();  
                                        $today = Carbon\Carbon::now();
                                        $leadNotification = DB::table('leads')->where('id', $notification->data['leadsID'])->first(); 
                                        $rwaName = DB::table('users')->where('id',isset($notification->data['co_follow_up']))->first();
                                        $emName = DB::table('employees')
                                    ->where('user_id', $notification->data['EmployeeName'])
                                    ->first();
                                       
                                    @endphp
                                        <tr id="task" class="task-list-row"> 
                                            <td class="text-wrap">
                                            <span class="clipboard action-icon " data-toggle="tooltip" data-placement="top" title="Copy Lead Info"
                                                onclick="copy_to_clipboard('{{ url('lead-status/' . encrypt($notification->data['leadsID'])) }}')"> 
                                                   <i class="mdi mdi-content-copy"></i> 
                                               </span> 
                                                <a data-toggle="tooltip" data-placement="top"
                                                title="Check Status"
                                                href="{{ url('lead-status/'  . encrypt($notification->data['leadsID'])) }}"
                                                class="action-icon" target="blank">
                                                <img style="width:20px; margin-bottom:2px"
                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                            </a>

                                            </td>
                                            <td class="text-wrap">{{ $notification->created_at }}</td> 
                                            <td class="text-wrap">{{ $notification->data['lead_name'] ?? "N/A" }}</td> 
                                            <td>{{ $notification->data['property_requirement'] }}</td>
                                            <td  class="text-wrap">{{  $notification->data['location'] }}</td>
                                            <td class="text-wrap">{{ $LeadStatusName->name }}</td> 
                                          
                                            
                                            @if ($notification->data['messageUpdateBy'])
                                            <td class="text-wrap">
                                                {{ $notification->data['messageUpdateBy'] }}
                                           </td> 
                                           @elseif( $notification->data['leads_status'] == 1 || $leadNotification->next_follow_up_date >= $today  && $notification->data['leads_status'] == 5 && !isset($notification->data['co_follow_up']))
 
                                            <td class="text-wrap">
                                                {{ 'New Lead ' . ($currentEMP != null ? $currentEMP->employee_name : "") }}
                                            </td>

                                            @elseif($leadNotification->next_follow_up_date < $today && $notification->data['leads_status'] == 5 )
                                            <td class="text-wrap">
                                                {{ 'Next Follow-Up Time Lapsed ' . ($currentEMP->employee_name ?? "") }}
                                            </td>
                                            @elseif(isset($notification->data['co_follow_up']) && $notification->data['co_follow_up'] == Auth::user()->id)

                                            <td class="text-wrap"> {{ $currentEMP->employee_name . ' Made Follow-Up Buddy '. Auth::user()->name }}</td>

                                            @elseif($notification->data['leads_status'] == 14)

                                            {{ 'Congratulations'. $emName ? $emName->employee_name : "N/A" .'for the booking of' . $notification->data['lead_name'] . 'Keep up the good work.Congratulations'  }}
                                          
                                            @elseif($notification->data['message'])
                                            <td class="text-wrap">
                                                {{ $notification->data['message'] }}
                                           </td>  

                                           <td class="text-wrap">
                                                {{ $notification->data['message'] }}
                                           </td> 
                                           @else
                                           <td>{{ "N/A" }}</td>
                                            @endif  
                                            <td class="text-wrap">
                                                <a href="{{ url('read-single-notification-clear/' . ($notification->id)) }}"
                                                class="action-icon mr-4 btn btn-danger btn-xs text-light"> <i
                                                    class="mdi mdi-delete"></i>
                                                </a>
 
                                                </td>
                                        </tr>
                                    @endforeach 
                                </tbody>
                            </table> 
                        </div>
                        
                        
                            <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2" id="isPaginate">
                                {{ $notificationData->links('pagination::bootstrap-4') }}
                            </ul>  
                           
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
@section('scripts')
<style>
    .iti {
        display: block !important;
    }
   

    .select2-container--default .select2-selection--single {
        background-color: #f2f5f7 !important;
        border-radius: 4px;
        border: 1px solid #ced4da !important;
        line-height: 35.9px;
        height: 35.9px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #6c757d !important;
        line-height: 35.9px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 35px
    }


    .iti__flag {
        display: none;
    }
    #demo-foo-filtering_length{
        display: none;
    }
    #demo-foo-filtering_info{
        display: none;
    }
    #demo-foo-filtering_paginate{
        display: none;
    }
     .buttons-copy{
        display: none;
    }
    .buttons-print{
        display: none;
    }
</style>
<script>
     $('#demo-foo-filtering').dataTable( { 
          lengthMenu: [
               [100,75, 50,25,  -1],
               [100,75, 50,25, 'All'],
          ],
        processing: true, 
    });
</script>
    <script>
         $('#assigned-user-filter').select2({
            // selectOnClose: true,
            placeholder: "Select"
        
        });

        $('#common_pool').select2({
            // selectOnClose: true,
            placeholder: "Select"
        
        });
        $(document).ready(function() {
             var allVals = [];
            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });

            $('.sub_chk').on('click', function(e) {
                if ($(this).is(':checked', true)) {

                    // $("#master").prop('checked', false);
                } else {
                    $("#master").prop('checked', false);
                }
            });



            $('.delete_all').on('click', function(e) { 
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                    // alert 
                });
                
                if (allVals.length <= 0  ) { 
                  //  alert("Please select row.");
                } else {
                    var check = true;
                    if (check == true) {
                        var join_selected_values = allVals.join(",");

                        // alert(join_selected_values);
                        
                        demo.value = join_selected_values;

                        // $.ajax({
                        //     url: "{{ route('assign-common-pool') }}",
                        //     type: 'POST',
                        //     headers: {
                        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        //     },
                        //     data: 'ids=' + join_selected_values,  
                        // });
                        // $.each(allVals, function(index, value) {
                        //     $('table tr').filter("[data-row-id='" + value + "']").remove();
                        // });
                    }
                }
            });

        }); 
 
 
    </script>
    
    
    
<script>
    $('#projectType').select2({
            //placeholder: 'Select Project Type',
            // selectOnClose: true  
        });
        $('#CustomerType').select2({
            //placeholder: 'Select Project Type',
            // selectOnClose: true  
        });
        
        $('#BuyingLocation').select2({
            //placeholder: 'Select Buying Location Type',
            // selectOnClose: true  
        });
        $('#employee').select2({
            //placeholder: 'Select Employee Type',
            // selectOnClose: true  
        });
        $('#projectName').select2({
            //placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#followupbuddy').select2({
           // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

        $('#ChannelPartner').select2({
           // placeholder: 'Select Project Name Type',
            // selectOnClose: true  
        });

   
</script>

 
<script>
	
	
 


    var
        filters = {
            user: null,
            status: null,
            milestone: null,
            priority: null,
            tags: null
        };

    function updateFilters() {
        $('.task-list-row').hide().filter(function() {
            var
                self = $(this),
                result = true; // not guilty until proven guilty

            Object.keys(filters).forEach(function(filter) {
                if (filters[filter] && (filters[filter] != 'None') && (filters[filter] != 'Any')) {
                    result = result && filters[filter] === self.data(filter);
                }
            });

            return result;
        }).show();
    }

    function changeFilter(filterName) {
        filters[filterName] = this.value;
        updateFilters();
    }

    // Assigned User Dropdown Filter
    $('#assigned-user-filter').on('change', function() {
        // var status_filter = document.getElementById('status_filter'); 
        // status_filter.value = ""; 
         changeFilter.call(this, 'user'); 

    });

    // Task Status Dropdown Filter
    $('#status_filter').on('change', function() {
        changeFilter.call(this, 'status');
    });
</script>

<script>

     


    // start to end date filters
    var minDate, maxDate;
    // Custom filtering function which will search data in column four between two values
    $.fn.dataTable.ext.search.push( 
        function(settings, data, dataIndex) {
            
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date(data[1]);
            // console.log(date);
            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        }
    );

    $(document).ready(function() {

        
        // Create date inputs
        minDate = new DateTime($('#min'), {
            format: 'D-M-Y'
        });
        
        maxDate = new DateTime($('#max'), {
            format: 'D-M-Y'
        });

        // DataTables initialisation
        var table = $('#demo-foo-filtering').DataTable();

        // Refilter the table
        $('#min, #max').on('change', function() {
            table.draw();
        });
    });

    // start to end date filters end 
</script>

<script>
   
    function copy_to_clipboard(link) { 
        var input = document.createElement('input');
        input.setAttribute('value', link);
        document.body.appendChild(input);
        input.select();
        var result = document.execCommand('copy');
       // alert(result);
        document.body.removeChild(input);
        return result; 
    }
    
</script>

<script> 
    function applyFilter() {
        var leadStatus = $('#filter-status').val(); 
        var table = $('#datatable-buttons').DataTable();  
        // Apply the filter to the "Lead Status" column
        table.column(15).search(leadStatus).draw();
        
       if(leadStatus !== "")
       {
            var isPaginate = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    }   

    function ExistingApplyFilter() {
        var existingProperty = $('#existing-property').val(); 
        var table1 = $('#datatable-buttons').DataTable();  
        // Apply the filter to the "Lead Status" column
        table1.column(12).search(existingProperty).draw();
        
       if(existingProperty !== "")
       {
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    } 
</script>
<script>
    function applyFilterUser() {
        var leadStatusUser = $('#filter-user').val(); 
        var table = $('#datatable-buttons').DataTable();
      
       
        // Apply the filter to the "Lead Status" column
        table.column(5).search(leadStatusUser).draw();
        if(leadStatusUser !== "")
        {   
            var isPaginate = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate.css('display', 'none'); 
        } else
        {
            window.location.replace('/common-pool'); 
        }
    }  
</script>
<script>
   function FollowUpDateApplyFilter() {
        var FollowUPFilter = $('#datefilterfrom').val(); 
        const FollowUpformattedDate = moment(FollowUPFilter, 'YYYY-MM-DD').format('D-MMM-YYYY'); 
        var table1 = $('#datatable-buttons').DataTable();   
        table1.column(7).search(FollowUpformattedDate).draw(); 
       if(FollowUpformattedDate !== "")
       {
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/leads'); 
       }
    } 

</script>

<script>
 function CreateDateApplyFilter() {
        var CreationDateFilter = $('#creation-date').val(); 
        const formattedDate = moment(CreationDateFilter, 'YYYY-MM-DD').format('D-MMM-YYYY'); 
         
        var table1 = $('#datatable-buttons').DataTable();   
        table1.column(2).search(formattedDate).draw(); 
 
       if(formattedDate !== "Invalid date")
       {
             
            var isPaginate1 = $('#isPaginate'); // Select the element with the id "isPaginate"
            isPaginate1.css('display', 'none'); 
       } 
       else
       {
             window.location.replace('/common-pool'); 
       }
    } 
</script>



@endsection
