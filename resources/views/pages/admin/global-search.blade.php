@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Homents</a></li>
                            <li class="breadcrumb-item active">Trail Logs</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Trail Logs</h4>
                </div>

                @if (session()->has('success'))
            <div class="alert alert-danger text-center">
                {{ session()->get('success') }}
                {{-- <a class="text-success" href="{{ url('common-pool') }}">(Go to common-pool)</a> --}}
            </div>
            @endif
            </div>
            
        </div>
        <!-- end page title -->

        <div class="row"> 
                <div class="card-box col-md-12">  
                    <form action="{{ url('/trail-logs-filter') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="to">From</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="to">To</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div style="margin-top: 30px"> 
                                <button type="submit" class="btn btn-info btn-sm">Filter</button>
                            </div>
                        </div>
                        
                        
                        
                    </form>

                    <div class="col-md-4">
                        <label for="">Employee Name</label>
                        @php
                            $emp = DB::table('employees')->get();
                        @endphp
                        <select id="status_filter" class="form-control">
                            <option value="">See All</option>
                            @foreach ($emp as $item)
                                <option value="{{ $item->employee_name }}">{{ $item->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                     <div class=" d-flex justify-content-end">
                        @can('Trail Logs') 
                            <a href="{{url('trail-logs-excel')}}"  class="btn btn-info waves-effect waves-light mt-3">
                                Trails Log Reports
                            </a> 
                          @endcan   
                    </div>  
                   
                    
                    <div class="table-responsive mt-3">
                        <table id="demo-foo-filtering"
                        class="table table-centered table-nowrap table-hover mb-0" 
                        data-placement="top"  data-page-size="100">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Employee Name</th>
                                    <th>Search Number</th>
                                    <th>Count</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody 
                            class="table table-centered table-nowrap table-hover mb-0" >
                                @foreach ($GlobalSearchs as $GlobalSearch)
                                    <!-- Modal -->
                                    @php
                                        $isEmp = DB::table('employees')
                                        ->where('user_id',$GlobalSearch->emp_id)
                                        ->select('employees.employee_name')
                                        ->first(); 

                                        $isSearching = DB::table('global_search')
                                        ->where('gs_mobile_number', $GlobalSearch->gs_mobile_number)
                                        ->count();

                                        $IsNumberExist = DB::table('leads')
                                        ->where('contact_number',$GlobalSearch->gs_mobile_number)->exists();

                                        $IsLeadExist = DB::table('leads')
                                        ->where('contact_number',$GlobalSearch->gs_mobile_number)->first();
                                          
                                    @endphp
                                   
                                    <tr>
                                        <td>
                                             
                                            <a   href="{{ url('lead-status/' . encrypt(isset($IsLeadExist->id) ? $IsLeadExist->id :"")) }}"
                                                target="_blank"
                                                 @if ($IsNumberExist == false)
                                                     style="display: none;"
                                                 @else
                                                    style="display: block;"
                                                 @endif>
                                                {{-- <i class="fas fa-circle text-success"></i> --}}
                                                <img style="width:20px; margin-bottom:2px"
                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE0ElEQVRoge2Zy09bRxTGv3OvTUwUG5pbkBDhWYlHKtFWjtpNF5BVCVhgEK7asuiiihqRdpFl1UquVPUPCAKRSl01i9YGTGIg6qKBdUSkVqgEkBLAkFQp4WEbgcH2PV2UqMjXjxnb0EX4LWfO4zuauY8zA5xyyqsN5SNIj6dH3TPF3mVFb1GY7DrQQEAZAecAgIEdMD0j4gUGZkhXpuyzTQ/cbreea+6cCugY66jQdaWPgV4Ql0u6r4H4djyuDtzrHl3LVkNWBbR6ekpM5uh3DHwKoCDb5IccEPCjiekbX5dvQ9ZZugDHWMfHzNQP4LysbwY2QNw33nnnFxkn4QLst66ay0rWB0H8mbw2CYiHdoqCX0y3TMeEzEWMHH7HWY6pwwBacxInCBFPQNVdfod/N5OtksnAfuuq+STFAwAztSGueJqnmk2ZbDMWUFayPogTFP8SZmqzbhffzGSXdgu1jzo/AfHt/MmSh4AP/c4xT5r55DhHnVqUeB7A68eiTJwNMsUb/A7/i2STKbdQjPh7/P/iAUDjuOJONZl0BVpHui6oiv4YWXykaotqcFFrQIW1AtYCKwAgvB9GILyKuc1HWAouy4YEgH2YYm+MO8afJk4kfcpNxNdZUrxm0dBW24pKa4VxrlCDVqjhndK3sRIOYPLJPWxENmXCn0HM1Afgq8QJwwq43W5l5q3fVwBcEI1eaauEq64bFtUiZB+JR+BdGMFKOCCaAmB6WhgzVXld3vjRYcMzMNP0x3uQEK9ZNCnxAGBRLeip78Z5y2vCPiAu3zNH7YnDhgJY0VvEowJXaj6QEv8Si2pBW+0VKR9iupw4ZiiAdOWSaMCaompU2SqlRBylylqJGlu1sD0DmVcAQJ1owDe1i8LJU9GoNQrbEnF94pixAOIy0YAVVuFHJSVVSd5aqWDAoC3ZCpwTDWgz24STp4xRIBXDmjiQ8WfuuNGRW1ucrIAdUedQNJRTcgAIHwinA4Bw4oCxAKa/RKOthldlkiclIPExI8CgzfgaJV4QDfjni3nh5KmY23wkbkwwJDQUoBM/FI23FFrCcmhFXEACgVAAy0Fxfx1GbYYClLh6X0bExNIkdmMZW1cDe7EI/E8mpXySaTMUYJ9tegBAeHNvRbYxsuhDJB4RFrIXi8C7OIyt/S1hHwAB+2yTYQXUxIHp6Wmu+6i+FKD3RSMHD4KY31xA6dlSFJ8pSmu7HFqBZ2EYz3efi4YHABDTwA/Xhn4zjCczzqWhqbFVo1FrRKW1AkWHH6ngQeiwoZmT2vNHSNnQpOyJHb7OQQauZZMt3xDQ73eOfZlsLuWX+CBq/hrA+rGpEmfDxPRtqsmUBfzq8m4yU9KqTxTiz9Md+qb9F5ro8v0M4qH8qxKDgP7xzjvD6Wwy/swVHhRcB5Mvf7KEGQ8Xb9/IZJSxAK/LGydzrJeIJ/KjSwh/YdTsEjmhFj5eb55qNlm3i28e95uJgP5w8faNvB6vH8Xh63QxMID8n9r9fXjBkXbPJyLd0PidY55o1FwP4gEA+7L+BogjBPSzOdogKx7I8ZKv3d9efnhi1gtAvLn9lwAT/6QSD97tuPssWw15uWY9PM27REyXGbATcT0D5fivv94B0xqARVb0GSWu3rfPNj3MxzXrKae86vwDmbWeftNnJZcAAAAASUVORK5CYII=" />
                                            </a>  
                                        </td> 
                                        <td>{{ $isEmp->employee_name }}</td> 
                                    
                                        <td>
                                            {{ $GlobalSearch->gs_mobile_number }} 
                                        </td> 

                                        <td>
                                            <span class="text-danger">{{ $isSearching }}</span>
                                        </td>

                                        <td> 
                                            <span  @if ($IsNumberExist == false)
                                                class="text-danger text-bold"
                                            @else
                                            class="text-primary text-bold"
                                            @endif>{{ $IsNumberExist == false ? "No" : "Yes" }}</span>
                                        </td>
                                    	
                                       <td>{{ Carbon\Carbon::parse($GlobalSearch->created_at)->format('d-m-Y H:i'); }}</td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                      {{-- @if(method_exists($Developers, 'links')) 
                     <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $Developers->links('pagination::bootstrap-4'); }}
                    </ul> 
                    @endif --}}
                </div>
            </div>
        </div>


    </div> <!-- container -->
@endsection

@section('scripts')
<style>
    #demo-foo-filtering_length{
        display: none;
    }
</style>
<script>
    // $('#demo-foo-filtering').dataTable( {
    //      lengthMenu: [
    //          [100,75, 50,25,  -1],
    //          [100,75, 50,25, 'All'],
    //      ],
    //     processing: true, 
    // });

    $(document).ready(function() {
    dataTable = $("#demo-foo-filtering").DataTable({
      "columnDefs": [
            {
                "targets": [3],
                "visible": false
            }
        ],lengthMenu: [
             [100,75, 50,25,  -1],
             [100,75, 50,25, 'All'],
         ],
        processing: true, 
      
    });
  
  
  
  /*dataTable.columns().every( function () {
        var that = this;
 
        $('input', this.footer()).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that.search(this.value).draw();
            }
        });
      });*/
  
  
    // $('.filter-checkbox').on('change', function(e){
    //   var searchTerms = []
    //   $.each($('.filter-checkbox'), function(i,elem){
    //     if($(elem).prop('checked')){
    //       searchTerms.push("^" + $(this).val() + "$")
    //     }
    //   })
    //   dataTable.column(1).search(searchTerms.join('|'), true, false, true).draw();
    // });
  
    $('#status_filter').on('change', function(e){
      var status = $(this).val();
      $('#status_filter').val(status)
      console.log(status)
      //dataTable.column(6).search('\\s' + status + '\\s', true, false, true).draw();
      dataTable.column(1).search(status).draw();
    })
});
  

    
</script>

@endsection

