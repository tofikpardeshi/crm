@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row d-flex justify-content-beetwen">
            <div class="col-12 col-md-4 my-3"> 
                    <h4 class="page-title">Employee Dashboard</h4>
                 
            </div>

            @php 
                $Emplist = DB::table('employees')
                ->where('organisation_leave',0)
                ->get(); 
                // dd($empId->id)
            @endphp

        <div class="col-12 col-md-4">
        </div>

            <div class="col-12 col-md-4 my-3">
                <form action="{{ url('is-employee-filter/') }}" method="post">
                    @csrf 
                   <div class="d-flex" >  
                       <select name="empFilter" class="selectpicker" 
                       data-style="btn-light" id="empFilter">
                       <option value="" selected>Select</option>
                           @foreach ($Emplist as $Emp) 
                                   <option value="{{ $Emp->id }}"
                                    {{ old('empFilter') == $Emp->id ? 'selected' : '' }}>{{ $Emp->employee_name }}</option> 
                           @endforeach 
                       </select>
                       <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                           value="submit">Filter</button>
                   </div>
               </form> 

               @if(request()->is('is-employee-filter'))
               <div class="form-group d-flex justify-content-end mt-2">
                    <a type="button" class="btn btn-danger waves-effect waves-light  "
                    href="{{ route('dashboard') }}">
                    <i class="fa fa-arrow-left"></i>Back</a>
                </div> 
                @endif
            </div>
 
        </div>
        <!-- end page title -->

        <div class="row">
            @foreach ($employeeData as $empDeshboard) 
                <div class="col-md-6 col-xl-3"> 
                     <a href="{{ url('employee-location-wise-lead/' . encrypt($empDeshboard['emaployee_id'])) }}"
                        >  
                        <div class="card-box">

                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-sm bg-soft-info rounded">
                                        <i class="fe-aperture avatar-title font-22 text-info"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="text-dark my-1"><span
                                                >{{ $empDeshboard['leadCount'] }}</span>
                                        </h3>
                                        <p class="text-muted mb-1 text-truncate">Total Leads</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"> 
                            {{-- <div class="d-flex justify-content-between">
                                <p class="text-muted mb-1 text-truncate ">Booking Confirm</p>
                            <h5 class="text-dark my-1 "><span>{{ $LocationWiseEmpBookingCount ?? 'N/A' }}</span>
                            </h5>
                            </div> --}}
                            
                                <h6 class="text-uppercase">{{ $empDeshboard['emp_name'] }} 
                                    {{-- <span class="float-right">{{ $locationWiseLeads['leads_count'] }}%</span> --}}
                                </h6>
                                {{-- <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100"
                                        >
<!--                                        style="width: {{ $locationWiseLeads['leads_count'] }}%">-->
                                    </div>
                                </div> --}}
                            </div>
                        </div> <!-- end card-box-->
                    </a>  
                </div> <!-- end col -->
            @endforeach
        </div>
    </div>


 
@endsection


@section('scripts')

    <script>
      
        $('#empFilter').select2({
            // placeholder: "Select Designation",
            // minimumResultsForSearch: Infinity
        });
        
        
    </script>
@endsection



