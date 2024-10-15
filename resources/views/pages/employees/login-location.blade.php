@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid" >

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box"> 
                    <h4 class="page-title">Login Logs</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12"> 
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                             
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

                                <div class="col-8 d-flex justify-content-end">
                                    @can('Login Reports') 
                                        <a href="{{url('login-export')}}"  class="btn btn-info waves-effect waves-light mt-3">
                                            Login Reports
                                        </a> 
                                    @endcan   
                                </div>   
                        </div>

                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box" style="padding: 0">
                                        <table id="demo-foo-filtering" class="table table-centered table-nowrap table-hover mb-0" data-placement="top" data-page-size="100">
                                            @php
                                                $loginUserStatus =   DB::table('log')
                                                    ->join('users', 'users.id', '=', 'log.user_id')
                                                    ->select('log.*', 'users.name')
                                                    // ->where('log.user_id', auth()->user()->id) 
                                                    ->orderBy('log.created_at', 'desc') 
                                                    ->get();

                                              
                                            @endphp
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Employee Ip Address</th>
                                                    <th>Employee Login Address</th>
                                                    <th>Login Time</th>
                                                    <th>Logout Time</th>
                                                    <th>Time Line</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($loginUserStatus as $key => $loginUser)
                                              
                                                    @php
                                                        $loginTime = Carbon\Carbon::parse($loginUser->created_at);
                                                        $logoutTime = Carbon\Carbon::parse($loginUser->updated_at); 
                                                        $timeDifference = $logoutTime ? $loginTime->diffForHumans($logoutTime) : "N/A";
                                                         
                                                    @endphp 
                                            
                                                    <tr 
                                                    id="task-{{ $key + 1 }}" 
                                                    class="task-list-row" 
                                                    data-status="{{ $loginUser->name }}" 
                                                    data-priority="Urgent">

                                                        <td>{{ $loginUser->name }}</td>
                                                        <td>{{ $loginUser->ip_address ?? "N/A" }}</td>
                                                        <td>{{ $loginUser->address ?? "N/A" }}</td> 
                                                        <td> 
                                                            {{ Carbon\Carbon::parse($loginUser->created_at)->format('d-m-Y H:i') }} 
                                                        </td> 
                                                        <td> 
                                                                {{ $loginUser->action == "logout" ? Carbon\Carbon::parse($loginUser->updated_at)->format('d-m-Y H:i') : "N/A" }} 
                                                        </td>
                                                        <td>{{  $loginUser->action == "logout" ? $timeDifference : "N/A" }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> <!-- end card-box -->
                                </div> <!-- end col -->
                            </div>
                        </div>

                        {{--<tfoot>
                            <tr class="active">
                                <td colspan="6">
                                    <div class="text-right">
                                        <ul
                                            class="pagination pagination-split justify-content-end footable-pagination mt-2">
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot> --}}

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection


@section('scripts')

<script> 

$(document).ready(function() {
    dataTable = $("#demo-foo-filtering").DataTable({
     
     //    dom: 'Bfrtip', // Include the buttons
     //     buttons: [
     //         {
     //             extend: 'excelHtml5', // Add Excel export button
     //             text: 'Export to Excel', // Customize button text
     //             title: 'Data Export' // Customize the file name
     //         }
     //     ],
     //     lengthMenu: [
     //         [100,75, 50,25,  -1],
     //          [100,75, 50,25, 'All'],
     //    ],
   
 });
    $('#status_filter').on('change', function(e){
      var status = $(this).val();
      $('#status_filter').val(status)  
      dataTable.column(0).search(status).draw();
    })
});
     
 </script>
 
     
@endsection


