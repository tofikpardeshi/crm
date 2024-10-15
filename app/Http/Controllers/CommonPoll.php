<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leads;
use DB;
use App\Models\Employee; 
use App\Models\User;
use Carbon\Carbon; 
use App\Exports\CommonPoolExport;
use Maatwebsite\Excel\Facades\Excel;

class CommonPoll extends Controller
{
    public function index()
    {
        //   $CommonPolls = DB::table('leads')
        //   ->join('employees','employees.id','leads.assign_employee_id')
        //   ->select('employees.employee_name','employees.id','leads.*')
        //   ->where('common_pool_status',1)->paginate(50);
            // if (\Auth::user()->roles_id == 1) {
                $CommonPolls = DB::table('leads')
                ->join('employees','employees.id','leads.assign_employee_id')
                ->select('employees.employee_name','employees.id','employees.official_phone_number','employees.emp_country_code','leads.*')
                ->where('common_pool_status',1)
                ->latest('leads.updated_at')->paginate(50);
                $employees = DB::table('employees')->where('organisation_leave','!=',1)->get();
               
                $projectTypes = DB::table('project_types')->get();
                return view('pages.commonpool.index',compact(['CommonPolls','employees','projectTypes'])); 
            // } else {
            //     $empID = DB::table('employees')->where('user_id',\Auth::user()->id)->first();
            //     $CommonPolls = DB::table('leads')
            // ->join('employees','employees.id','leads.assign_employee_id')
            // ->select('employees.employee_name','employees.id','leads.*')
            // ->where('common_pool_status',1)
            // ->where('leads.assign_employee_id',$empID->id)
            // ->latest('leads.updated_at')->get();
            // $employees = DB::table('employees')->where('organisation_leave','!=',1)->get();
            // $projectTypes = DB::table('project_types')->get();
            // return view('pages.commonpool.index',compact(['CommonPolls','employees','projectTypes'])); 
            // }
            
          
    }
    
    public function FilterLeadByCommonPool(Request $request)
    {

        $submit = $request['submit']; 
            $currentUrl = $request->url();
  
           if($submit == 'submit')
           {
            
            
                $filter = $request->sortbyLead; 
                $employeefilter = $request->employee; 
                $BuyingLocationfilter = $request->BuyingLocation; 
                $projectNamefilter = $request->projectName;
                $customer_type = $request->customer_type; 
                $followupbuddy = $request->followupbuddy; 
                $ChannelPartner = $request->ChannelPartner; 
                
                // is_null($filter) || is_null($employeefilter) || is_null($BuyingLocationfilter) || is_null($projectNamefilter)
                if($request->sortbyLead == null && $request->employee == null && $request->BuyingLocation == null &&  $request->projectName == null && $request->customer_type == null && $request->followupbuddy == null && $request->ChannelPartner == null)
                {
                    
                    return redirect()->route('leads-index')->with('message','Please select anyone' );
                }
                 else
                 { 

                    //$LeadStatus = DB::table('lead_statuses')->get(); 
                    $CommonPolls =  DB::table('leads')
                    ->where('common_pool_status', 1)
                    ->where(function ($query) use ($filter) {
                        if (!empty($filter)) {
                            $query->where('project_type', 'LIKE', '%' . $filter . '%');
                        }
                    })
                    ->where(function ($query) use ($BuyingLocationfilter) {
                        if (!empty($BuyingLocationfilter)) {
                            $query->where('location_of_leads', 'LIKE', $BuyingLocationfilter);
                        }
                    })
                    ->where(function ($query) use ($employeefilter) {
                        if (!empty($employeefilter)) {
                            $query->where('assign_employee_id', 'LIKE', $employeefilter);
                        }
                    })
                    ->where(function ($query) use ($projectNamefilter) {
                        if (!empty($projectNamefilter)) {
                            $query->where('project_id', 'LIKE', '%' . $projectNamefilter . '%');
                        }
                    })
                    ->where(function ($query) use ($customer_type) {
                        if (!empty($customer_type)) {
                            $query->where('buyer_seller', 'LIKE', '%' . $customer_type . '%');
                        }
                    })
                    ->where(function ($query) use ($followupbuddy) {
                        if (!empty($followupbuddy)) {
                            $query->where('co_follow_up', 'LIKE', '%' . $followupbuddy . '%');
                        }
                    })
                    ->where(function ($query) use ($ChannelPartner) {
                        if (!empty($ChannelPartner)) {
                            $query->where('rwa', 'LIKE', '%' . $ChannelPartner . '%');
                        }
                    })
                    ->join('employees', 'employees.id', 'leads.assign_employee_id')
                    ->select('leads.*', 'employees.employee_name', 'employees.official_phone_number', 'employees.user_id','employees.emp_country_code') 
                    ->latest()
                    ->paginate(100)
                    ->withQueryString();
                   // dd($leads);
                    //$employees = Employee::all();
                    $projectTypes = DB::table('project_types')->get(); 
                    return view('pages.commonpool.index',compact(['CommonPolls','projectTypes','currentUrl'])); 
                  }
                
           }
           else
           {
              return redirect()->back();
           }     
    }

    public function EmployeeAssingComoonPoll(Request $request)
    {
        $submit = $request['submit'];
        if($submit)
        {    
              $assingemployee = array();
              $assingemployee['assign_employee_id'] = $request->common_pool;
              $assingemployee['common_pool_status'] = 0; 
              $data = DB::table('leads')
              ->where('id',$request->commonpooID)
              ->update($assingemployee);

              return redirect()->back()->with('success','Assigned Employee'); 
        }
        else
        {
            return "Error";
        }
    }

    public function AssignCommonPool(Request $request)
    {

        $updateCP = array();
        $updateCP['assign_employee_id'] = $request->common_pool;
        $updateCP['common_pool_status'] = 0;
        $updateCP['lead_status'] = 2;
        $updateCP['next_follow_up_date'] = Carbon::now();
        $test = DB::table("leads")
        ->whereIn('id',explode(",",$request->cp))
        ->update($updateCP);  
        if($test == true)
        {
            return redirect()->route('common-pool')->with('success','Lead Assigned Successfull'); 
        }
        else
        {
            return redirect()->route('common-pool')->with('error','Something Went Wrong'); 
        }
        // return response()->json([
            
        //     'success'=> "Lead Assigned Successfull"
        // ]);
    }
    
    public function CommonPoolDownload()
        {
            return Excel::download(new CommonPoolExport, 'CommonPoll.xlsx');
        }

         public function LeadFilters(Request $request)
        {
             
            $currentUrl = $request->url();

            
            
            $CommonPolls = DB::table('leads')
            ->join('employees', 'employees.id', 'leads.assign_employee_id')
            ->join('lead_statuses', 'lead_statuses.id', 'leads.lead_status')
            ->select('employees.employee_name', 'employees.id','employees.official_phone_number','employees.emp_country_code','leads.*')
            ->where('common_pool_status', 1)
            ->when(!is_null($request->EmpName), function ($query) use ($request) {
                return $query->where('employees.employee_name', $request->EmpName);
            })
            ->when(!is_null($request->creationDate), function ($query) use ($request) {
                return $query->whereDate('leads.date', $request->creationDate);
            })
            ->when(!is_null($request->dateFollowUp), function ($query) use ($request) {
                return $query->orWhereDate('leads.next_follow_up_date', $request->dateFollowUp);
            })
            ->when(!is_null($request->LeadStatus), function ($query) use ($request) {
                return $query->where('leads.lead_status', $request->LeadStatus);
            })
            ->when(!is_null($request->exitsProperty), function ($query) use ($request) {
                return $query->where('leads.existing_property', $request->exitsProperty);
            }) 
            ->when(!is_null($request->budget), function ($query) use ($request) {
                return $query->where('leads.budget', $request->budget);
            }) 
            ->when(!is_null($request->investor), function ($query) use ($request) {
                return $query->where('leads.regular_investor', $request->investor);
            }) 
            ->latest('leads.updated_at')
            ->paginate(100)
            ->withQueryString();
             
               
                $employees = DB::table('employees')->where('organisation_leave','!=',1)->get();
               
                $projectTypes = DB::table('project_types')->get();
                return view('pages.commonpool.index',compact(['CommonPolls','employees','projectTypes','currentUrl'])); 
        }
}
