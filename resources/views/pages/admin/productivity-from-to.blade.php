@extends('main')
<!-- Start Content-->

@section('dynamic_page')
<style>
    .table th{white-space: normal}
</style>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <div class="form-group d-flex">
                            {{-- <button class="btn btn-success mx-1" style="height: 35px;" type="submit" name="submit"
                                data-toggle="modal" data-target="#exampleModal" value="submit">Filter</button> --}}
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
                                                <form action="{{ route('filter-by-employee') }}" method="get">
                                                    
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
                    <h4 class="page-title">Productivity Filter > 
                          
                        {{$newLead = DB::table('leads') 
                        ->where('leads.created_at', '>', $from)
                         ->where('leads.created_at', '<', $to) 
                         
                        ->count() }}
                    </h4>
                </div> 
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
 
            <div class="col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="card-box">  
                    @if (session()->has('error'))
                        <div class="alert alert-success text-center mt-3" id="notification">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                        @php
                        $from1 = date("Y-m-d", strtotime($from));  
                        $to1 = date("Y-m-d", strtotime($to));
                        @endphp
                    <div class="row">
                        <form action="{{ url('/from-productivity-to') }}" method="get">
                            @csrf
                            <div class="col-md-12 d-flex">
                                <div class="col-md-6 ">
                                    <label for="" style="font-size: 11px">Form</label>
                                    <input type="date" class="form-control" name="from" max="<?= date('Y-m-d') ?>"
                                        required value="{{ $from1 }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="" style="font-size: 11px">To</label>
                                    <input type="date" class="form-control" id="to_date" max="<?= date('Y-m-d') ?>"
                                        name="to" required value="{{ $to1 }}">
                                </div>
                                <button type="submit" class=" btn btn-info">Filter</button>
                            </div>
                        </form>
                        
                    </div>

                    <div class="d-flex" >
                        <a type="button" class="btn btn-info waves-effect waves-light  "
                           href="{{ route('employee-productivity') }}">
                           <i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                      
                    <div class="table-responsive" style="margin-top: 0px"> 
                        <table class="table table-centered table-hover mb-0"
                        onmousedown='return false;' onselectstart='return false;' data-placement="top"
                         data-page-size="100"
                        id="demo-foo-filtering">
                            <thead>
                                <tr> 
                                    <th>Employee Name</th>
                                    <th>Total Lead</th>
                                    <th>Common Poll</th>
                                    <th>Inbox Leads</th> 
                                     <th>Total Productivity</th>   
                                    <th>Self Assign</th>
                                    <th>Assigned by Other</th> 
                                    <th>New</th>
                                    <th>Pending</th>
                                    <th>Customer Called to Enquire</th> 
                                    <th>Call Not Answered</th> 
                                    <th>Next Follow-up</th> 
                                    <th>Site Visit Conducted</th> 
                                    <th>Unable to Connect</th> 
                                    <th>Case Closed - Enquiry Only</th> 
                                    <th>Case Closed - Not Interested</th> 
                                    <th>Case Closed - Low Budget</th> 
                                    <th>Case Closed - Already Booked</th> 
                                    <th>Case Closed - For Common Pool</th> 
                                    <th>Reallocate from Common Pool</th>
                                    <th>Deal Confirmed</th> 
                                    <th>Deal Cancelled</th>  
                                    {{-- <th>Employee Left - For Common Pool</th>  --}}
                                    <th>Visit / Meeting Planned</th> 
                                    <th>Old Lead</th>  
                                    <th>Total Productivity</th>  

                                </tr>
                            </thead>
                            <thead >
                                <tr> 
                                    <th class="text-center">Total Productivity</th>  
                                    <td class="text-center">
                                        {{$TotalLeads = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to)   
                                          ->count()  }}
                                      </td>
                                      <td class="text-center">
                                        {{$CommonPollLeads = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)   
                                        ->where('common_pool_status',1)->count()  }}
                                      </td>
                                      <td class="text-center">
                                          {{$InboxLeads = DB::table('leads')->where('common_pool_status',0)
                                           ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to)   
                                          ->where('common_pool_status',0)
                                          ->count() 
                                           }}
                                      </td>   
                                    <td class="text-center">
                                        {{ 
                                        $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)  
                                       ->count()  }}
                                       </td>  
                                     <td class="text-center">
                                          
                                        {{ $matchingRecords = DB::table('leads')
                                       ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)  
                                        ->count();  }} 
                                    </td>

                                    
                                    
                                    
                                    <td class="text-center">
                                      
                                          {{ $newLead = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        //    ->where('assign_employee_id', $empLead->assign_employee_id)
                                          ->where('created_by', '!=' ,$empLead->assign_employee_id) 
                                          
                                          //->where('lead_status', 1)
                                          ->count()  }} 
                                  </td>
                                    
                                    <td class="text-center">
                                         
                                          {{ $newLead = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to)  
                                        //   ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          //->where('created_by' ,$empDeshboard['emaployee_user_id'])
                                          ->where('lead_status', 1)
                                          ->count()  }} 
                                    </td>
                                    <td class="text-center">
                                         
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                         ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 2)
                                        ->count()  }} 
                                        </td>
                                    <td class="text-center">
                                         
                                        {{ $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                       // ->whereDate('leads.created_at', [$currentDate,$date])
                                        // ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                         ->where('lead_status', 3)
                                       ->count() }} 
                                       </td>
                                    <td class="text-center">
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 4)
                                        ->count() }} 
                                        </td>
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 5 )
                                        ->count() }} 
                                        </td>
                                    <td class="text-center">
                                        
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 6)
                                        ->count() }} 
                                        </td>
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 7)
                                        ->count() }} 
                                        </td> 
                                        <td class="text-center"> 
                                            {{ $newLead = DB::table('leads')
                                             ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,$date])
                                            //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 8)
                                            ->count() }} 
                                            </td> 
                                            <td class="text-center"> 
                                                {{ $newLead = DB::table('leads')
                                                 ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                                // ->whereDate('leads.created_at', [$currentDate,$date])
                                                //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                  ->where('lead_status', 9)
                                                ->count() }} 
                                                </td> 
                                                <td class="text-center"> 
                                                    {{ $newLead = DB::table('leads')
                                                     ->where('leads.created_at', '>', $from)
                                                ->where('leads.created_at', '<', $to) 
                                                    // ->whereDate('leads.created_at', [$currentDate,$date])
                                                    //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                      ->where('lead_status', 10)
                                                    ->count() }} 
                                                    </td> 
                                                    <td class="text-center"> 
                                                        {{ $newLead = DB::table('leads')
                                                         ->where('leads.created_at', '>', $from)
                                                    ->where('leads.created_at', '<', $to) 
                                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                          ->where('lead_status', 11)
                                                        ->count() }} 
                                                        </td> 
                                                        <td class="text-center"> 
                                                            {{ $newLead = DB::table('leads')
                                                             ->where('leads.created_at', '>', $from)
                                                        ->where('leads.created_at', '<', $to) 
                                                            // ->whereDate('leads.created_at', [$currentDate,$date])
                                                            //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                              ->where('lead_status', 12)
                                                            ->count() }} 
                                                            </td> 
                                                            <td class="text-center"> 
                                                                {{ $newLead = DB::table('leads')
                                                                 ->where('leads.created_at', '>', $from)
                                                            ->where('leads.created_at', '<', $to) 
                                                                // ->whereDate('leads.created_at', [$currentDate,$date])
                                                                //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                                  ->where('lead_status', 13)
                                                                ->count() }} 
                                                                </td> 
                                                            
                                    <td class="text-center">
                                      
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 14)
                                        ->count() }} 
                                    </td> 
                                    <td class="text-center">
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 15)
                                        ->count() }} 
                                    </td> 
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 17)
                                        ->count() }} 
                                        </td> 
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 18)
                                        ->count() }} 
                                        </td>   
                                    <td class="text-center">
                                        {{ 
                                        $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to) 
                                       // ->whereDate('leads.created_at', [$currentDate,$date])
                                        // ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                        
                                        //  ->where('lead_status',  18)
                                       ->count()  }}
                                       </td>
                                </tr>
                            </thead> 
                            <tbody class="table table-centered table-nowrap table-hover mb-0">
                                @foreach ($employeeData as $empDeshboard)
                                    @php
                                   
                                    $matchingRecords = DB::table('leads')
                                    // ->whereDate('leads.created_at', '>=', isset($date))
                                    ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                      //->whereColumn('created_by', '=', 'assign_employee_id')
                                    ->where('created_by', $empDeshboard['emaployee_user_id']) 
                                    ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                    ->count(); 

                                     //dd($matchingRecords);
                                    @endphp
                                    <tr class="text-center">
                                        @php
                                        $from1 = isset($from) ? $from : $date;
                                        $to1 = isset($to) ? $to : $date;

                                    @endphp
                                    <td>
                                        <a href="{{ url('emp-daywise-prod/'.encrypt($empDeshboard['emaployee_id'])) . '/'. $from1.'/'. $to1 }}" target="_blank">
                                            {{ $empDeshboard['emp_name'] }}

                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{$TotalLeads = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', $empDeshboard['emaployee_id'])  
                                          ->count()  }}
                                      </td>
                                      <td class="text-center">
                                        {{$CommonPollLeads = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)
                                        ->where('assign_employee_id', $empDeshboard['emaployee_id'])   
                                        ->where('common_pool_status',1)->count()  }}
                                      </td>
                                      <td class="text-center">
                                          {{$InboxLeads = DB::table('leads')->where('common_pool_status',0)
                                           ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to)   
                                          ->where('common_pool_status',0)
                                          ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->count() 
                                           }}
                                      </td>
					                    <td>
                                            {{ 
                                            $newLead = DB::table('leads')
                                             ->whereDate('leads.updated_at','>=', isset($date))
                                            //  ->where('leads.created_at', '>', $from)
                                            // ->where('leads.created_at', '<', $to) 
                                             ->where('leads.updated_at', '>', $from)
                                            ->where('leads.updated_at', '<', $to) 
                                           // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                            ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                            
                                            //  ->where('lead_status',  18)
                                           ->count()  }}
                                           </td> 
                                         <td>
                                              <a href="{{ url('employee-productivity-lead/' .  encrypt($empDeshboard['emaployee_id'])). '/' . 1 .'/'.$filterType}}"
                                                > 
                                            {{ $matchingRecords }}
                                            </a>
                                        </td>
 
                                        
                                        
                                        <td>
                                          <a href="{{ url('employee-productivity-lead/' .  encrypt($empDeshboard['emaployee_id'])). '/' . 1 .'/'.$filterType}}"
                                              > 
                                              {{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('created_by', '!=' ,$empDeshboard['emaployee_user_id']) 
                                              //->where('lead_status', 1)
                                              ->count()  }}
                                            </a> 
                                      </td>
                                        
                                        <td>
                                             <a href="{{ url('employee-productivity-lead/' .  encrypt($empDeshboard['emaployee_id'])). '/' . 1 .'/'.$filterType}}"
                                              title="New"> 
                                              {{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->where('leads.updated_at', '>', $from)
                                            ->where('leads.updated_at', '<', $to) 
                                              ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              //->where('created_by' ,$empDeshboard['emaployee_user_id'])
                                              ->where('lead_status', 1)
                                              ->count()  }}
                                          </a>
                                        </td>
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 2 .'/'.$filterType }}"
                                            title="Pending"  > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=',isset($date))
                                             ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 2)
                                            ->count()  }}
                                            </a>
                                            </td>
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 3 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                           // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                           ->where('leads.updated_at', '>', $from)
                                            ->where('leads.updated_at', '<', $to) 
                                            ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                             ->where('lead_status', 3)
                                           ->count() }}
                                            </a>
                                           </td>
                                        <td><a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 4 .'/'.$filterType}}"
                                            > 
                                            {{ $newLead = DB::table('leads')
                                             ->whereDate('leads.updated_at','>=', isset($date))
                                             ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 4)
                                            ->count() }}
                                            </a>
                                            </td>
                                        <td><a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 5 .'/'.$filterType}}"
                                            > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 5 )
                                            ->count() }}
                                            </a>
                                            </td>
                                        <td>
                                            
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 6 .'/'.$filterType}}"
                                            > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 6)
                                            ->count() }}
                                            </a>
                                            </td>
                                         <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 7 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 7)
                                            ->count() }}
                                            </a>
                                            </td>

                                            <td>
                                                <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 8 .'/'.$filterType}}"
                                                    > 
                                                {{ $newLead = DB::table('leads')
                                                //  ->whereDate('leads.updated_at','>=', isset($date))
                                                ->where('leads.created_at', '>', $from)
                                                ->where('leads.created_at', '<', $to) 
                                                // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                 ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                  ->where('lead_status', 8)
                                                ->count() }}
                                                </a>
                                                </td>


                                                <td>
                                                    <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 9 .'/'.$filterType}}"
                                                        > 
                                                    {{ $newLead = DB::table('leads')
                                                    //  ->whereDate('leads.updated_at','>=', isset($date))
                                                    ->where('leads.created_at', '>', $from)
                                                    ->where('leads.created_at', '<', $to) 
                                                    // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                     ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                      ->where('lead_status', 9)
                                                    ->count() }}
                                                    </a>
                                                    </td>


                                                    <td>
                                                        <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 10 .'/'.$filterType}}"
                                                            > 
                                                        {{ $newLead = DB::table('leads')
                                                        //  ->whereDate('leads.updated_at','>=', isset($date))
                                                        ->where('leads.created_at', '>', $from)
                                                        ->where('leads.created_at', '<', $to) 
                                                        // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                         ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                          ->where('lead_status', 10)
                                                        ->count() }}
                                                        </a>
                                                        </td>

                                                        <td>
                                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 11 .'/'.$filterType}}"
                                                                > 
                                                            {{ $newLead = DB::table('leads')
                                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                                            ->where('leads.created_at', '>', $from)
                                                            ->where('leads.created_at', '<', $to) 
                                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                              ->where('lead_status', 11)
                                                            ->count() }}
                                                            </a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 12 .'/'.$filterType}}"
                                                                    > 
                                                                {{ $newLead = DB::table('leads')
                                                                //  ->whereDate('leads.updated_at','>=', isset($date))
                                                                ->where('leads.created_at', '>', $from)
                                                                ->where('leads.created_at', '<', $to) 
                                                                // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                                 ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                                  ->where('lead_status', 12)
                                                                ->count() }}
                                                                </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 13 .'/'.$filterType}}"
                                                                        > 
                                                                    {{ $newLead = DB::table('leads')
                                                                    //  ->whereDate('leads.updated_at','>=', isset($date))
                                                                    ->where('leads.created_at', '>', $from)
                                                                    ->where('leads.created_at', '<', $to) 
                                                                    // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                                                     ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                                      ->where('lead_status',13)
                                                                    ->count() }}
                                                                    </a>
                                                                    </td>
                                                                    
                                                                     
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 14 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 14)
                                            ->count() }}
                                            </a>
                                        </td> 
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 15 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 15)
                                            ->count() }}
                                            </a>
                                        </td> 
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 17 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 17)
                                            ->count() }}
                                            </a>
                                            </td> 
                                        <td>
                                            <a href="{{ url('employee-productivity-lead/' . encrypt($empDeshboard['emaployee_id'])). '/' . 18 .'/'.$filterType}}"
                                                > 
                                            {{ $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                             ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 18)
                                            ->count() }}
                                            </a>
                                            </td>   
                                        <td>
                                            {{ 
                                            $newLead = DB::table('leads')
                                            //  ->whereDate('leads.updated_at','>=', isset($date))
                                            ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                           // ->whereDate('leads.created_at', [$currentDate,isset($date)])
                                            ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                            
                                            //  ->where('lead_status',  18)
                                           ->count()  }}
                                           </td> 
                                    </tr>
                                 @endforeach   
                            </tbody>
                            <thead >
                                <tr> 
                                    <th class="text-center">Total Productivity</th>  
                                    <td class="text-center">
                                        {{$TotalLeads = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to)   
                                          ->count()  }}
                                      </td>
                                      <td class="text-center">
                                        {{$CommonPollLeads = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)   
                                        ->where('common_pool_status',1)->count()  }}
                                      </td>
                                      <td class="text-center">
                                          {{$InboxLeads = DB::table('leads')->where('common_pool_status',0)
                                           ->where('leads.created_at', '>', $from)
                                          ->where('leads.created_at', '<', $to)   
                                          ->where('common_pool_status',0)
                                          ->count() 
                                           }}
                                      </td>  
                                    <td class="text-center">
                                        {{ 
                                        $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)
                                        ->where('common_pool_status',0)
                                       ->count()  }}
                                       </td>  
                                     <td class="text-center">
                                          
                                        {{ $matchingRecords = DB::table('leads')
                                       ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                          //->whereColumn('created_by', '=', 'assign_employee_id')
                                        ->where('created_by', $empLead->assign_employee_id) 
                                        
                                        // ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                        ->count();  }} 
                                    </td>

                                    
                                    
                                    
                                    <td class="text-center">
                                      
                                          {{ $newLead = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        //    ->where('assign_employee_id', $empLead->assign_employee_id)
                                          ->where('created_by', '!=' ,$empLead->assign_employee_id) 
                                          
                                          //->where('lead_status', 1)
                                          ->count()  }} 
                                  </td>
                                    
                                    <td class="text-center">
                                         
                                          {{ $newLead = DB::table('leads')
                                          ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to)  
                                        //   ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          //->where('created_by' ,$empDeshboard['emaployee_user_id'])
                                          ->where('lead_status', 1)
                                          ->count()  }} 
                                    </td>
                                    <td class="text-center">
                                         
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                         ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 2)
                                        ->count()  }} 
                                        </td>
                                    <td class="text-center">
                                         
                                        {{ $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                       // ->whereDate('leads.created_at', [$currentDate,$date])
                                        // ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                         ->where('lead_status', 3)
                                       ->count() }} 
                                       </td>
                                    <td class="text-center">
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 4)
                                        ->count() }} 
                                        </td>
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 5 )
                                        ->count() }} 
                                        </td>
                                    <td class="text-center">
                                        
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 6)
                                        ->count() }} 
                                        </td>
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 7)
                                        ->count() }} 
                                        </td> 
                                        <td class="text-center"> 
                                            {{ $newLead = DB::table('leads')
                                             ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to) 
                                            // ->whereDate('leads.created_at', [$currentDate,$date])
                                            //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                              ->where('lead_status', 8)
                                            ->count() }} 
                                            </td> 
                                            <td class="text-center"> 
                                                {{ $newLead = DB::table('leads')
                                                 ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                                // ->whereDate('leads.created_at', [$currentDate,$date])
                                                //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                  ->where('lead_status', 9)
                                                ->count() }} 
                                                </td> 
                                                <td class="text-center"> 
                                                    {{ $newLead = DB::table('leads')
                                                     ->where('leads.created_at', '>', $from)
                                                ->where('leads.created_at', '<', $to) 
                                                    // ->whereDate('leads.created_at', [$currentDate,$date])
                                                    //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                      ->where('lead_status', 10)
                                                    ->count() }} 
                                                    </td> 
                                                    <td class="text-center"> 
                                                        {{ $newLead = DB::table('leads')
                                                         ->where('leads.created_at', '>', $from)
                                                    ->where('leads.created_at', '<', $to) 
                                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                          ->where('lead_status', 11)
                                                        ->count() }} 
                                                        </td> 
                                                        <td class="text-center"> 
                                                            {{ $newLead = DB::table('leads')
                                                             ->where('leads.created_at', '>', $from)
                                                        ->where('leads.created_at', '<', $to) 
                                                            // ->whereDate('leads.created_at', [$currentDate,$date])
                                                            //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                              ->where('lead_status', 12)
                                                            ->count() }} 
                                                            </td> 
                                                            <td class="text-center"> 
                                                                {{ $newLead = DB::table('leads')
                                                                 ->where('leads.created_at', '>', $from)
                                                            ->where('leads.created_at', '<', $to) 
                                                                // ->whereDate('leads.created_at', [$currentDate,$date])
                                                                //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                                                  ->where('lead_status', 13)
                                                                ->count() }} 
                                                                </td> 

                                    <td class="text-center">
                                      
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 14)
                                        ->count() }} 
                                    </td> 
                                    <td class="text-center">
                                        
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 15)
                                        ->count() }} 
                                    </td> 
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 17)
                                        ->count() }} 
                                        </td> 
                                    <td class="text-center"> 
                                        {{ $newLead = DB::table('leads')
                                         ->where('leads.created_at', '>', $from)
                                    ->where('leads.created_at', '<', $to) 
                                        // ->whereDate('leads.created_at', [$currentDate,$date])
                                        //  ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                          ->where('lead_status', 18)
                                        ->count() }} 
                                        </td>   
                                    <td class="text-center">
                                        {{ 
                                        $newLead = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to) 
                                       // ->whereDate('leads.created_at', [$currentDate,$date])
                                        // ->where('assign_employee_id', $empDeshboard['emaployee_id'])
                                        
                                        //  ->where('lead_status',  18)
                                       ->count()  }}
                                       </td>
                                </tr>
                            </thead> 
                        </table>
                    </div>
                    {{-- <ul class="pagination pagination-rounded justify-content-end mb-0 mt-2">
                        {{ $locations->links('pagination::bootstrap-4'); }}
                    </ul> --}}
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
    $('#demo-foo-filtering').dataTable( {
         lengthMenu: [
             [100,75, 50,25,  -1],
             [100,75, 50,25, 'All'],
         ],
        // pagingType: 'full_numbers'
        // processing: true, 
    });

   
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



