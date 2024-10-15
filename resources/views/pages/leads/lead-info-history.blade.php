@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid" oncontextmenu="return false;">

        <!-- start page title -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between"> 
                <h4 class="page-title my-3">Lead Name > {{ $leadsName->lead_name }}</h4>  
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-6"> 
                <div class="card"> 
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="text-sm-right"> 
                                    <a type="button" class="btn btn-danger waves-effect waves-light  "
                                    href="{{ url('lead-status/' . encrypt($leadsName->id)) }}">Back</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                         

                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box">
                                        <table id="demo-foo-filtering"
                                            class="table table-centered table-nowrap table-hover mb-0" 
                                            data-placement="top" data-page-size="100">
                                            <thead>
                                                <tr> 
                                                    <th>Employee Name</th>
                                                    <th>State</th>
                                                    <th>City</th>
                                                    <th>Date/Time</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leadInfoHistorys as $lead)
                                                @php
                                                $empName = DB::table('employees')
                                                    ->where('user_id', $lead->user_id)
                                                    ->first(); 
                                                @endphp
                                                    <tr id="task" class="task-list-row" >  
                                                        <td class="text-capitalize"> 
                                                             {{ $empName->employee_name }}
                                                        </td> 
                                                        
                                                        <td class="text-capitalize"> 
                                                            {{ $lead->regionName ?? 'N/A' }}
                                                       </td> 

                                                       <td class="text-capitalize"> 
                                                        {{ $lead->cityName ?? 'N/A' }}
                                                        </td> 

                                                        <td class="text-capitalize"> 
                                                            {{ \Carbon\Carbon::parse($lead->created_at)->format('d-M-Y H:i:s') }}
                                                       </td> 
                                                    </tr>
                                                @endforeach
                                            </tbody> 
                                        </table>
                                    </div> <!-- end card-box -->
                                </div> <!-- end col -->
                            </div>
                        </div> 
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection


@section('scripts') 
    <script>
        $('#demo-foo-filtering').dataTable({
            // "paging": false
            lengthMenu: [
                [100, 75, 50, 25, -1],
                [100, 75, 50, 25, 'All'],
            ],
            processing: true,
        });

    </script>
@endsection
