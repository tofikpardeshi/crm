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
                            <a type="button" class="btn btn-danger waves-effect waves-light  "
                            href="{{ url()->previous() }}">
                            <i class="fa fa-arrow-left"></i>Back</a>
                        </div> 
                    </div>
                    <h4 class="page-title">Productivity Reports > {{ $empId->employee_name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            

            <div class="col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="card-box">
                     <div class="form-group d-flex justify-content-end">
                        {{-- @can('Bulk Reports') --}}
                            <a href="{{ url('export-employee-reports/'. decrypt($id) .'/'. $from .'/'. $to) }}" class="btn btn-info waves-effect waves-light  ">
                                Employee Reports
                            </a>
                        {{-- @endcan --}}
                    </div>  

                    <div class="table-responsive mt-3">
                        <table id="demo-foo-filtering" class="table   w-100" data-page-size="100">
                            <thead>
                                <tr>
                                    {{-- <th>Employee Name</th> --}}
                                    <th>Date</th>
                                    <th>Total Lead</th>
                                    <th>Common Poll</th>
                                    <th>Inbox Leads</th> 
                                     <th>Total Productivity</th>  
                                    {{-- <th>Self Assign</th>
                                    <th>Assigned by Other</th>   --}}
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
                                        ->where('assign_employee_id', decrypt($id)) 
                                        ->count()  }}
                                    </td>
                                    <td class="text-center">
                                      {{$CommonPollLeads = DB::table('leads')
                                      ->where('leads.created_at', '>', $from)
                                      ->where('leads.created_at', '<', $to)  
                                      ->where('assign_employee_id', decrypt($id)) 
                                      ->where('common_pool_status',1)->count()  }}
                                    </td>
                                    <td class="text-center">
                                        {{$InboxLeads = DB::table('leads')->where('common_pool_status',0)
                                         ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)  
                                        ->where('assign_employee_id', decrypt($id))
                                        ->count() 
                                         }}
                                    </td> 

                                      
                                    <td class="text-center">
                                        {{ 
                                           $TotalLeads = DB::table('leads')
                                        ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)  
                                        ->where('assign_employee_id', decrypt($id)) 
                                        ->count()
                                        }}
                                    </td> 
                                    <td class="text-center"> {{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                
                                           ->where('lead_status', 1)
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 2)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 3)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 4)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 5)
                                  
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                
                                           ->where('lead_status', 6)
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 7)
                                  
                                          ->count() }}</td>

                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 8)
                                  
                                          ->count() }}</td>
                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                             ->where('leads.created_at', '>', $from)
                                                ->where('leads.created_at', '<', $to) 
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 9)
                                      
                                              ->count() }}</td>
                                              <td class="text-center">{{ $newLead = DB::table('leads')
                                                //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                 ->where('leads.created_at', '>', $from)
                                                    ->where('leads.created_at', '<', $to) 
                                                // ->where('leads.created_at', '<', $to) 
                                                  ->where('assign_employee_id', decrypt($id)) 
                                                   ->where('lead_status', 10)
                                          
                                                  ->count() }}</td>
                                                  <td class="text-center">{{ $newLead = DB::table('leads')
                                                    //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                    // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                     ->where('leads.created_at', '>', $from)
                                                        ->where('leads.created_at', '<', $to) 
                                                    // ->where('leads.created_at', '<', $to) 
                                                      ->where('assign_employee_id', decrypt($id)) 
                                                       ->where('lead_status', 11)
                                              
                                                      ->count() }}</td>
                                                      <td class="text-center">{{ $newLead = DB::table('leads')
                                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                         ->where('leads.created_at', '>', $from)
                                                            ->where('leads.created_at', '<', $to) 
                                                        // ->where('leads.created_at', '<', $to) 
                                                          ->where('assign_employee_id', decrypt($id)) 
                                                           ->where('lead_status', 12)
                                                  
                                                          ->count() }}</td>
                                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                            // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                             ->where('leads.created_at', '>', $from)
                                                                ->where('leads.created_at', '<', $to) 
                                                            // ->where('leads.created_at', '<', $to) 
                                                              ->where('assign_employee_id', decrypt($id)) 
                                                               ->where('lead_status', 13)
                                                      
                                                              ->count() }}</td>
                                                              
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 14)
                                  
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 15)
                                  
                                          ->count() }}</td>
                                   
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 17)
                                  
                                          ->count() }}</td>

                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                        ->where('assign_employee_id', decrypt($id)) 
                                        ->where('lead_status', 18)
                              
                                        ->count() }}</td>

                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                        ->where('assign_employee_id', decrypt($id)) 
                                        // ->where('lead_status', 18)
                              
                                        ->count() }}</td> 
                                </tr>
                            </thead> 
                            <tbody class="table table-centered table-nowrap table-hover mb-0">
                                @foreach ($LeadDayWise as $LeadDayWiseStatus)
                                @php
                                     
                                   
                                   $matchingRecords = DB::table('leads')
                                   ->whereDate('leads.created_at', '>=', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                     //->whereColumn('created_by', '=', 'assign_employee_id')
                                   ->where('created_by', decrypt($id)) 
                                   ->where('assign_employee_id', decrypt($id))
                                   ->count(); 
                                @endphp
                                    <tr class="text-center"> 
                                        <td style="font-weight: bold;">{{ 
                                            date("d-m-Y", strtotime($LeadDayWiseStatus->created_at)) }}</td>
                                            <td class="text-center">
                                              {{$TotalLeads = DB::table('leads')
                                                 ->whereDate('leads.updated_at','>=', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                ->where('assign_employee_id', decrypt($id)) 
                                                ->count()  }}
                                            </td>
                                            <td class="text-center">
                                              {{$CommonPollLeads = DB::table('leads')
                                              ->whereDate('leads.updated_at','>=', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                              ->where('assign_employee_id', decrypt($id)) 
                                              ->where('common_pool_status',1)
                                              ->count()  }}
                                            </td>
                                             
                                        <td>
                                            {{$InboxLeads = DB::table('leads')
                                           ->whereDate('leads.updated_at','>=', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                           ->where('common_pool_status',0)
                                          // ->whereDate('leads.created_at', [$currentDate,$date])
                                          ->where('assign_employee_id', decrypt($id)) 
                                  
                                           //  ->where('lead_status',  18)
                                          ->count()  }}
                                        </td>
                                        <td> {{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                            ->where('assign_employee_id', decrypt($id)) 
                                            // ->where('lead_status', 18)
                                  
                                            ->count() }}
                                            </td>
                                        {{-- <td>
                                            
                                            {{ $matchingRecords }}
                                        </td> --}}
                                        {{-- <td>
                                            {{ 
                                                $newLead = DB::table('leads')
                                              ->whereDate('leads.created_at','>=', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at))) 
                                              ->where('assign_employee_id', decrypt($id))
                                            //   ->where('created_by', '!=' ,decrypt($id)) 
                                              //->where('lead_status', 1)
                                              ->count()
                                            }}
                                        </td>  --}}
                                        <td> {{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                    
                                               ->where('lead_status', 1)
                                              ->count() }}
                                              </td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 2)
                                      
                                              ->count() }}</td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 3)
                                      
                                              ->count() }}</td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 4)
                                      
                                              ->count() }}</td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 5)
                                      
                                              ->count() }}
                                              </td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                    
                                               ->where('lead_status', 6)
                                              ->count() }}
                                              </td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 7)
                                      
                                              ->count() }}</td>
                                              <td>{{ $newLead = DB::table('leads')
                                                //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                // ->where('leads.created_at', '<', $to) 
                                                  ->where('assign_employee_id', decrypt($id)) 
                                                   ->where('lead_status', 8)
                                          
                                                  ->count() }}</td>
                                                  <td>{{ $newLead = DB::table('leads')
                                                    //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                    ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                    // ->where('leads.created_at', '<', $to) 
                                                      ->where('assign_employee_id', decrypt($id)) 
                                                       ->where('lead_status', 9)
                                              
                                                      ->count() }}</td>
                                                      <td>{{ $newLead = DB::table('leads')
                                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                        ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                        // ->where('leads.created_at', '<', $to) 
                                                          ->where('assign_employee_id', decrypt($id)) 
                                                           ->where('lead_status', 10)
                                                  
                                                          ->count() }}</td>
                                                          <td>{{ $newLead = DB::table('leads')
                                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                            // ->where('leads.created_at', '<', $to) 
                                                              ->where('assign_employee_id', decrypt($id)) 
                                                               ->where('lead_status',11)
                                                      
                                                              ->count() }}</td>
                                                              <td>{{ $newLead = DB::table('leads')
                                                                //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                                ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                                // ->where('leads.created_at', '<', $to) 
                                                                  ->where('assign_employee_id', decrypt($id)) 
                                                                   ->where('lead_status', 12)
                                                          
                                                                  ->count() }}</td>
                                                                  <td>{{ $newLead = DB::table('leads')
                                                                    //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                                    ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                                                    // ->where('leads.created_at', '<', $to) 
                                                                      ->where('assign_employee_id', decrypt($id)) 
                                                                       ->where('lead_status', 13)
                                                              
                                                                      ->count() }}</td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 14)
                                      
                                              ->count() }}
                                              </td>
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 15)
                                      
                                              ->count() }}</td>
                                       
                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 17)
                                      
                                              ->count() }}</td>

                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                            ->where('assign_employee_id', decrypt($id)) 
                                            ->where('lead_status', 18)
                                  
                                            ->count() }}</td>

                                        <td>{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            ->whereDate('leads.created_at', date("Y-m-d", strtotime($LeadDayWiseStatus->created_at)))
                                            // ->where('leads.created_at', '<', $to) 
                                            ->where('assign_employee_id', decrypt($id)) 
                                            // ->where('lead_status', 18)
                                  
                                            ->count() }}</td>

 

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
                                        ->where('assign_employee_id', decrypt($id)) 
                                        ->count()  }}
                                    </td>
                                    <td class="text-center">
                                      {{$CommonPollLeads = DB::table('leads')
                                      ->where('leads.created_at', '>', $from)
                                      ->where('leads.created_at', '<', $to)  
                                      ->where('assign_employee_id', decrypt($id)) 
                                      ->where('common_pool_status',1)->count()  }}
                                    </td>
                                    <td class="text-center">
                                        {{$InboxLeads = DB::table('leads')->where('common_pool_status',0)
                                         ->where('leads.created_at', '>', $from)
                                        ->where('leads.created_at', '<', $to)  
                                        ->where('assign_employee_id', decrypt($id))
                                        ->count() 
                                         }}
                                    </td>

                                    <td class="text-center"> {{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                        ->where('assign_employee_id', decrypt($id)) 
                                        // ->where('lead_status', 18) 
                                        ->count() }}
                                        </td>
                                    {{-- <td class="text-center">
                                        
                                        {{ $matchingRecords }}
                                    </td> --}}
                                    {{-- <td class="text-center">
                                        {{ 
                                            $newLead = DB::table('leads')
                                          ->whereDate('leads.created_at','>=', date("Y-m-d", strtotime($empId->created_at))) 
                                          ->where('assign_employee_id', decrypt($id))
                                        //   ->where('created_by', '!=' ,decrypt($id)) 
                                          //->where('lead_status', 1)
                                          ->count()
                                        }}
                                    </td>  --}}
                                    <td class="text-center"> {{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                
                                           ->where('lead_status', 1)
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 2)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 3)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 4)
                                  
                                          ->count() }}</td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 5)
                                  
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                
                                           ->where('lead_status', 6)
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 7)
                                  
                                          ->count() }}</td>

                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 8)
                                  
                                          ->count() }}</td>
                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                            // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                             ->where('leads.created_at', '>', $from)
                                                ->where('leads.created_at', '<', $to) 
                                            // ->where('leads.created_at', '<', $to) 
                                              ->where('assign_employee_id', decrypt($id)) 
                                               ->where('lead_status', 9)
                                      
                                              ->count() }}</td>
                                              <td class="text-center">{{ $newLead = DB::table('leads')
                                                //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                 ->where('leads.created_at', '>', $from)
                                                    ->where('leads.created_at', '<', $to) 
                                                // ->where('leads.created_at', '<', $to) 
                                                  ->where('assign_employee_id', decrypt($id)) 
                                                   ->where('lead_status', 10)
                                          
                                                  ->count() }}</td>
                                                  <td class="text-center">{{ $newLead = DB::table('leads')
                                                    //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                    // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                     ->where('leads.created_at', '>', $from)
                                                        ->where('leads.created_at', '<', $to) 
                                                    // ->where('leads.created_at', '<', $to) 
                                                      ->where('assign_employee_id', decrypt($id)) 
                                                       ->where('lead_status', 11)
                                              
                                                      ->count() }}</td>
                                                      <td class="text-center">{{ $newLead = DB::table('leads')
                                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                         ->where('leads.created_at', '>', $from)
                                                            ->where('leads.created_at', '<', $to) 
                                                        // ->where('leads.created_at', '<', $to) 
                                                          ->where('assign_employee_id', decrypt($id)) 
                                                           ->where('lead_status', 12)
                                                  
                                                          ->count() }}</td>
                                                          <td class="text-center">{{ $newLead = DB::table('leads')
                                                            //   ->whereDate('leads.created_at','>=', isset($date)) 
                                                            // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                                             ->where('leads.created_at', '>', $from)
                                                                ->where('leads.created_at', '<', $to) 
                                                            // ->where('leads.created_at', '<', $to) 
                                                              ->where('assign_employee_id', decrypt($id)) 
                                                               ->where('lead_status', 13)
                                                      
                                                              ->count() }}</td>
                                                           
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 14)
                                  
                                          ->count() }}
                                          </td>
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 15)
                                  
                                          ->count() }}</td>
                                   
                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                          ->where('assign_employee_id', decrypt($id)) 
                                           ->where('lead_status', 17)
                                  
                                          ->count() }}</td>

                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                        ->where('assign_employee_id', decrypt($id)) 
                                        ->where('lead_status', 18)
                              
                                        ->count() }}</td>

                                    <td class="text-center">{{ $newLead = DB::table('leads')
                                        //   ->whereDate('leads.created_at','>=', isset($date)) 
                                        // ->whereDate('leads.created_at', date("Y-m-d", strtotime($empId->created_at)))
                                         ->where('leads.created_at', '>', $from)
                                            ->where('leads.created_at', '<', $to) 
                                        // ->where('leads.created_at', '<', $to) 
                                        ->where('assign_employee_id', decrypt($id)) 
                                        // ->where('lead_status', 18)
                              
                                        ->count() }}</td> 
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
    .buttons-copy{
        display: none;
    }
    .buttons-print{
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

