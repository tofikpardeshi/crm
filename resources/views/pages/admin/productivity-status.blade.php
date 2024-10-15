@extends('main')
<!-- Start Content-->

@section('dynamic_page')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                @if (session()->has('error'))
                        <div class="alert alert-success text-center mt-3" id="notification">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                {{-- employee Popup filter --}}
                <div class="page-title-box"> 
                    <div class="page-title-right">
                        <div class="form-group d-flex">
                            <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                data-toggle="modal" data-target="#exampleModal" value="submit">Filter</button>
                        </div>

                        <div class="modal fade" id="exampleModal" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                @php 
                                                    $Employees = DB::table('employees')->get(); 
                                                @endphp
                                                <form action="{{ route('filter-by-employee') }}" method="get">
                                                    @csrf 
                                                    @if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11 )
                                                        <div class="form-group mb-3">
                                                            <label for="example-email">Employee <span
                                                                    class="text-danger"></label>
                                                            <select name="employee" class="selectpicker"
                                                                data-style="btn-light" id="employeedd">
                                                                <option value="">Employee</option>
                                                                @foreach ($Employees as $Employee)
                                                                    <option value="{{ $Employee->id }}" 
                                                                    @if ($empLeadProd->id == $Employee->id)
                                                                        selected 
                                                                    @endif>
                                                                        {{ $Employee->employee_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select> 
                                                        </div> 

                                                        <div class="form-group mb-3">
                                                            <select name="filter_type" class="selectpicker"
                                                            data-style="btn-light" id="employeedd">
                                                                <option value="" selected>Select</option>
                                                                <option value="7">Last 7 days</option>
                                                                <option value="15">Last 15 days</option>
                                                                <option value="30">Last 30 days</option>
                                                                <!-- Add more options as needed -->
                                                            </select>
                                                            </div>
                                                    @endif  
                                                    <div class="modal-footer">
                                                        <button class="btn btn-success mx-1" style="height: 35px;"
                                                            type="submit" name="submit" value="submit">filter</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="page-title">Today Productivity Status > {{ $empLeadProd->employee_name }}</h4>
                </div> 
                {{-- end --}}
            </div>
        </div>
        <!-- end page title -->

        <div class="row"> 
            @php
                $leadstatus =DB::table('lead_statuses')
                ->where('id', '!=', 16)
                ->get();
            @endphp
            @foreach ($leadstatus as $pls) 
                {{-- @php
                    dd($leadstatus);
                @endphp --}}
                <div class="col-md-6 col-xl-3"> 
                     <a href="{{ url('employee-productivity-lead/' . encrypt($empLeadProd->id)). '/' . $pls->id}}"
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
                                        @php
                                         
                                             $currentDate = Carbon\Carbon::today();
                                                $oneWeekAgo = $currentDate->subWeek(); 
                                        @endphp
                                        <h3 class="text-dark my-1"><span
                                                data-plugin="counterup">
                                                {{  
                                                $newLead = DB::table('leads')
                                                ->whereDate('leads.created_at','>=', $date)
                                                // ->whereDate('leads.created_at', [$currentDate,$date])
                                                ->where('assign_employee_id', $empLeadProd->id)
                                                ->where('lead_status', $pls->id)
                                                ->count(); }}</span>
                                        </h3>
                                        <p class="text-muted mb-1 text-truncate">Total Leads</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">  
                                <h6 class="text-uppercase">{{ $pls->name }} 
                                    {{-- <span class="float-right">{{ $locationWiseLeads['leads_count'] }}%</span> --}}
                                </h6> 
                            </div>
                        </div> <!-- end card-box-->
                    </a>  
                </div> <!-- end col -->
            @endforeach
        </div>
    </div>


    </div> <!-- container -->
@endsection


@section('scripts')
<script>
    $('#employeedd').select2({
            placeholder: 'Select',
            // selectOnClose: true  
        });
</script>
<script> 
    
    function ProdEmpStatus(this) {
        // Get the selected value from the dropdown
        var selectedValue = document.getElementById("filter_data").value;
        
        // Make an AJAX request to fetch the updated data
        fetch('/employee-productivity-status/' + selectedValue)
            .then(response => response.json())
            alert(response);
            .then(data => {
               
                // Update the page content with the fetched data
    
                // For example, if you want to update the total leads count:
                const totalLeadsElements = document.querySelectorAll('.text-dark');
                totalLeadsElements.forEach(element => {
                    // Assuming the fetched data contains a property 'total_leads_count'
                    const totalLeadsCount = data.total_leads_count;
                    element.textContent = totalLeadsCount;
                });
    
                // Similarly, update other elements on the page as needed.
    
                // Note: You may need to modify the selector and data properties to match your HTML structure and fetched data.
    
            })
            .catch(error => console.error('Error fetching data:', error));
    }
    </script>
    
@endsection


