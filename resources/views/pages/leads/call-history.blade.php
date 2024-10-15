@extends('main')


@section('dynamic_page')
    <!-- Start Content-->
    <!-- Start Content-->
    <div class="container-fluid" >

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box"> 
                    <h4 class="page-title">Call History - {{ $GetLeadData->lead_name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-lg-12"> 
                <div class="card">
                    <div class="card-body"> 
                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box" style="padding: 0">
                                        <table id="demo-foo-filtering" class="table table-centered table-nowrap table-hover mb-0" data-placement="top" data-page-size="100"> 
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Employee Number</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Call Duration</th>
                                                    <th>Call Status</th>
                                                    <th>Call Type</th>
                                                    <th>recordingurl</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($LeadCallHistory as $CallHistory)
                                               
                                               
                                                    <tr>
                                                        <td>{{ $GetLeadData->employee_name }}</td>
                                                        <td>{{ $GetLeadData->official_phone_number }}</td>
                                                        {{-- <td>{{ $CallHistory->callernumber }}</td> --}}
                                                        <td>{{ $CallHistory->date }}</td>
                                                        <td>{{ $CallHistory->time ?? "N/A" }}</td>
                                                        <td>{{ $CallHistory->callduration ?? "N/A" }}</td> 
                                                        <td> 
                                                            {{ $CallHistory->callstatus }} 
                                                        </td> 
                                                        <td> 
                                                                {{  $CallHistory->calltype }} 
                                                        </td>
                                                        <td> 
                                                            <audio controls>
                                                                <source src="{{ $CallHistory->recordingurl }}" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> <!-- end card-box -->
                                    <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2" id="isPaginate">
                                        {{ $LeadCallHistory->links('pagination::bootstrap-4') }}
                                    </ul>  
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

 


