@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <style>
        .table th {
            white-space: normal
        }
    </style>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <div class="form-group d-flex">
                            <a type="button" class="btn btn-danger waves-effect waves-light  "
                                href="{{ url('project') }}">
                                <i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    <h4 class="page-title">Customer Type > {{ $buyerseller->name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-8 col-12">
                <div class="card-box">
                    <div class="table-responsive mt-3">
                        <table id="datatable-buttons" class="table   w-100" data-page-size="100">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                //    dd($projectBuyerSeller);
                                @endphp
                                @foreach ($projectBuyerSeller as $empName)
                                @php  
                                
                                //  dd($empName);
                                $CustomerTypeLeadCount = DB::table('leads')
                                ->where('assign_employee_id', $empName->assign_employee_id)
                                ->where('buyer_seller', $bsID)
                                ->where(function ($query) use ($projectIDDetails) {
                                    $query->where('project_id', $projectIDDetails)
                                        ->orWhere('existing_property', $projectIDDetails);
                                })
                                ->count();

 
 
                                    $empName = DB::table('employees')
                                    ->where('id',$empName->assign_employee_id) 
                                    ->select('employees.employee_name','employees.id')
                                    ->first(); 
 

                                  
                                    
                                @endphp
                                <tr>
                                    <td>{{ $empName->employee_name }}</td>
                                   <td>{{ $CustomerTypeLeadCount }}</td>  
                                    <td> 
                                        <a href="{{ url('emp-wise-customer-type/'. encrypt($projectIDDetails) .'/'. $empName->id . '/' . $bsID) }}" target="_blank">
                                            View 
                                        </a> 
                                    </td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- container -->
@endsection


@section('scripts')
    <style>
        #demo-foo-filtering_length {
            display: none;
        }

        .buttons-copy {
            display: none;
        }

        .buttons-print {
            display: none;
        }
    </style>
    <script>
        //     $(document).ready(function() {
        //     $('#datatable-buttons').DataTable( {
        //         lengthMenu: [
        //             [100, 75, 50, 25, -1],
        //             [100, 75, 50, 25, 'All'],
        //         ],
        //         dom: 'Bfrtip',
        //         buttons: [ 
        //             'excel',  
        //         ]
        //     } );
        // } );


        $('#employeedd').select2({
            placeholder: 'Select',
            // selectOnClose: true  
        });



        setTimeout(function() {
            $("#flashmessage").hide();
        }, 2000);

        setTimeout(function() {
            $("#notification").hide();
        }, 2000);

        setTimeout(function() {
            $("#NoSearch").hide();
        }, 2000);
    </script>
@endsection

