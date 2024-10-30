<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Employee;
use App\Models\Lead;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\LeadExportReports;
use App\Notifications\WelcomeNotification;
use App\Notifications\LeadNotification; 
use App\Notifications\BuilderNotification;
use Illuminate\Support\Facades\Notification;
use Auth;
use App\Exports\EmployeeLeadExport; 
use App\Mail\ProjectWithNumberWithoutMail;
use Stevebauman\Location\Facades\Location;
use DateTime;

class LeadsController extends Controller
{
    public function index(Request $request) 
    {
        

        
        if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11) { 
            
        
            $LeadStatus = DB::table('lead_statuses')->get(); 
            $leads = DB::table('leads')
            ->where('common_pool_status',0)
            ->whereNotIn('lead_status', [14, 16, 8, 9, 10, 11, 12])
            ->join('employees','employees.id','leads.assign_employee_id') 
            ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
            ->select('leads.*','employees.employee_name','employees.official_phone_number'
            ,'employees.emp_country_code','lead_type_bifurcation.lead_type_bifurcation')
            ->latest()->paginate(50);
            $empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            $employees = Employee::all();
        $projectTypes = DB::table('project_types')->get();  
        return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus']));
        }
        else if(Auth::user()->roles_id == 10 )
        {
            
            $empId = DB::table('employees')
            ->where('user_id',Auth::user()->id)
            ->where('role_id',10)
            ->first();
            //   dd($empId);
            $LeadStatus = DB::table('lead_statuses')->get(); 
           $leads = DB::table('leads')
            ->where('common_pool_status',0)
            ->whereNotIn('lead_status', [14, 16, 8, 9, 10, 11, 12])
            //->where('leads.assign_employee_id',$empId->id)
             ->where('leads.rwa',$empId->user_id)
            ->join('employees','employees.id','leads.assign_employee_id') 
            ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
            ->select('leads.*','employees.employee_name','employees.official_phone_number',
            'employees.emp_country_code','lead_type_bifurcation.lead_type_bifurcation')->latest()->paginate(50);
             $empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            $employees = Employee::all();
            $projectTypes = DB::table('project_types')->get();  
            return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus'])); 
        }
        else
        {
           
            $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
           
            $LeadStatus = DB::table('lead_statuses')->get(); 
            
            $leads = DB::table('leads')
            ->where('common_pool_status', 0)
            ->where('lead_status', '!=', 14)
            ->where('lead_status','!=',16)
            ->where('lead_status','!=',8)
            ->where('lead_status','!=',9)
            ->where('lead_status','!=',10)
            ->where('lead_status','!=',11)
            ->where('lead_status','!=',12)
            ->where('co_follow_up', $empId->user_id)
            ->where('leads.assign_employee_id', $empId->id)
            ->orWhere(function ($query) use ($empId) {
                $query->where('co_follow_up', $empId->user_id)
                      ->where('common_pool_status', 0)
                      ->where('lead_status', '!=', 14)
                      ->where('lead_status','!=',16)
                      ->where('lead_status','!=',8)
                      ->where('lead_status','!=',9)
                      ->where('lead_status','!=',10)
                      ->where('lead_status','!=',11)
                      ->where('lead_status','!=',12)
                      ->orWhere('leads.assign_employee_id', $empId->id);
                    //   ->whereNotIn('lead_status', [14, 16, 8, 9, 10, 11, 12]);
            })
            ->join('employees', 'employees.id', 'leads.assign_employee_id') 
            ->join('lead_type_bifurcation', 'lead_type_bifurcation.id', 'leads.lead_type_bifurcation_id') 
            ->select('leads.*', 'employees.employee_name', 'employees.official_phone_number',
                     'employees.emp_country_code', 'lead_type_bifurcation.lead_type_bifurcation')
            ->latest()
            ->paginate(50);
            //  dd(count($leads));
            $empIdStatus = DB::table('employees')->where('user_id', Auth::user()->id)->first();

            $employees = Employee::all();
            $projectTypes = DB::table('project_types')->get();  
            return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus']));
        }
        

    }
 
    public function FilterLeadByProject(Request $request)
    	{

       

        $submit = $request['submit']; 
  
            if($submit == 'submit')
           {


         
            
              //return $request->ChannelPartner;
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
                    
                    

                     if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11) {
                        
                          $LeadStatus = DB::table('lead_statuses')->get(); 
                          $leads = DB::table('leads')
                            ->where('common_pool_status', 0)
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
                            ->select('leads.*', 'employees.employee_name', 'employees.official_phone_number', 'employees.user_id','emp_country_code')
                            ->latest()
                            ->get();
                            
                           
                           if (count($leads) == 0) {
                             return redirect()->back()->withSuccess("No Leads Available");
                           }

                          
                         foreach ($leads as $leadsSeach) {
                            $sellerprojectid = explode(',', $leadsSeach->project_id);
                            foreach ($sellerprojectid as $sellervalue) {
                                if ($sellervalue == $projectNamefilter) {
                                    $Projectsellercount[] = $leadsSeach;
                                }
                            }
                        }
                          if($request->employee != null)
                        {
                            $empIdStatus = DB::table('employees')->where('id',$request->employee)->first();
                        }
                        else
                        {
                            $empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
                        }

                        
                        $employees = Employee::all();
                        $projectTypes = DB::table('project_types')->get(); 

                        return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus'])); 
                    }
                    else
                    {
                         
                        $LeadStatus = DB::table('lead_statuses')->get(); 
                        $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                        $leads = DB::table('leads')
                        ->where('common_pool_status', 0)
                        ->where('leads.assign_employee_id',$empId->id)
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
                        ->select('leads.*', 'employees.employee_name', 'employees.official_phone_number', 'employees.user_id')
                        ->latest()
                        ->get(); 
                        
                    
                         
                        foreach ($leads as $leadsSeach) {
                            $sellerprojectid = explode(',', $leadsSeach->project_id);
                            foreach ($sellerprojectid as $sellervalue) {
                                if ($sellervalue == $projectNamefilter) {
                                    $Projectsellercount[] = $leadsSeach;
                                }
                            }
                        } 
                        $employees = Employee::all();
                        $projectTypes = DB::table('project_types')->get();
                        
                        $empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
                        return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus'])); 
                    }
                  }
                
           }
           else
           {
              return redirect()->back();
           }
       }
 

    public function createLeadsView()
    {
        
        $relations = DB::table('relationship')->get();
        $buyerSellers = DB::table('buyer_sellers')->get();
        $locations =  DB::table('locations')->get();
        $SourceTypes =  DB::table('source_types')->get();
        $employees =  DB::table('employees')
          ->where('lead_assignment',1)
          ->where('role_id','!=',10)
          ->orderBy('employee_name','asc')
          ->latest()->get();
        $leadnames = DB::table('leads')->get();
        $projectTypes = DB::table('project_types')->get();
        $LeadStatus = DB::table('lead_statuses')->get();
        $leadTypeBifurcations = DB::table('lead_type_bifurcation')->get();
        $number_of_units = DB::table('number_of_units')->get();
        $property_requirements = DB::table('property_requirement')->get();
        $projects = DB::table('projects')->orderBy('project_name')->get(); 
        $Budgets = DB::table('budget')->get();
        return view('pages.leads.create',compact(['projectTypes','employees','locations','SourceTypes','LeadStatus','leadTypeBifurcations','number_of_units','property_requirements','buyerSellers','projects','Budgets','leadnames','relations']));
    }

    public function createLeads(Request $request)
    {
         
        
        $submit = $request['submit'];
        if($submit == 'submit')
        {
            
             //$user = User::where('roles_id',1)->get(); 
             //return $user;
            
            $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first();
           

              $validation = $request->validate([
                  'date' => 'required', 
                 'lead_name' => 'required',
                  'contact_number.main' =>['required','unique:leads,contact_number'],
                //  'customer_email' => 'required',
                 'assign_employee_id' =>'required',
                 'buying_location' => 'required',
                 'lead_type' => 'required',
                 'source' => 'required', 
                 'lead_status' => 'required',
                  'customer_requirement' => 'required',
                 'property_requirement' => 'required',
                 'customer_interaction' => 'required',
              ],
              [ 
                  'date.required' => 'Kindly provide valid date & time.', 
                //    'customer_email.required' => 'Customer email field is required',
                 'lead_name.required' => "Customer name can't be left bank.",
                   'contact_number.main.required' => "Contact number can't be left blank.",
                   'contact_number.main.unique' => 'Contact number already registered in CRM. You may click below link to see the details or enter a new number to register.'. $request->contact_number['main'],
                 'source.required' => 'Select the Source for this lead.',
                 'buying_location.required' => 'Select the Buying Location of customer.',
                 'lead_status.required' => 'Lead status field is required',
                 'lead_type.required' => 'Lead type field is required',
                 'customer_requirement.required'=>'Select One or Multiple Customer Requirements from the dropdown.',
                 'assign_employee_id.required' => 'Select the user to assign this lead.',
                 'property_requirement.required' => 'Select Customer Type from the dropdown.',
                 'customer_interaction.required' => 'Enter complete details of discussion you had with customer.',
             ]);

             
            
            

              //return $request->alt_contact_number;
             $countryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']);
              $withcountryCode = implode(', ',$countryCode);
              $finalcountryCode = str_replace(',', '', $withcountryCode); 
              $IsSeller = array();
              
              if ($request->emergeny_contact_number['relationCC'] ==null) {
                $RelationcountryCode =null;  
                $RelationwithcountryCode = null; 
                $RelationfinalcountryCode = null;
              }else {
                 $RelationcountryCode = explode(preg_replace('/\s+/', '',$request->emergeny_contact_number['relationCC']), $request->emergeny_contact_number['relationCCode']);  
              $RelationwithcountryCode = implode(', ',$RelationcountryCode); 
              $RelationfinalcountryCode = str_replace(',', '', $RelationwithcountryCode);
              }
              
              
            //   $RelationcountryCode = explode($request->emergeny_contact_number['relationCC'], $request->emergeny_contact_number['relationCCode']);  
            //   $RelationwithcountryCode = implode(', ',$RelationcountryCode); 
            //   $RelationfinalcountryCode = str_replace(',', '', $RelationwithcountryCode);
              
              if (preg_replace('/\s+/', '',$request->alt_contact_number['altmain']) != null) { 
                $altcountryCode = explode(preg_replace('/\s+/', '',$request->alt_contact_number['altmain']), $request->alt_contact_number['altfull']);
                $altwithcountryCode = implode(', ',$altcountryCode);
                $altfinalcountryCode = str_replace(',', '', $altwithcountryCode); 
              }
              if (preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']) != null) { 
          
                $altcountryCode2 = explode(preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']), $request->alt_contact_number_2['altfull2']);
                $altwithcountryCode2 = implode(', ',$altcountryCode2);
                $altfinalcountryCode2 = str_replace(',', '', $altwithcountryCode2); 
              }
              
              if (preg_replace('/\s+/', '',$request->emergeny_contact_number['relationCC']) != null) { 
                $relationcountryCode = explode(preg_replace('/\s+/', '',$request->emergeny_contact_number['relationCC']), $request->emergeny_contact_number['relationCCode']);
                
                $relationwithcountryCode = implode(', ',$relationcountryCode);

                $RelationsfinalcountryCode = str_replace(',', '', $relationwithcountryCode);  
              }else {
                $relationcountryCode = null;
                
                $relationwithcountryCode = null;

                $RelationsfinalcountryCode = null;
              }
              
              
             

            $projectMultiple = implode(',', $request->customer_requirement ?? []);
            $projectBooking = implode(',',$request->booking_project ?? []);
            $existingProperty = implode(',', $request->existing_property ?? []);
            $projectName = implode(',',$request->project ?? []);
            
            $originalArrayDis = $request->project ?? []; 
             $elementToRemovesDV = $request->existing_property ?? []; 
             foreach ($elementToRemovesDV as $elementToRemoveDisVis) { 
               $filteredPDV = array_values(array_diff($originalArrayDis, [$elementToRemoveDisVis])); 
                 $originalProjectDis= $request->existing_property ?? [];  
                 $originalArrayEProject = array_unshift($originalProjectDis, $elementToRemoveDisVis); 
             } 
              
             $ExistProjectIsConfirm = implode(',',$originalProjectDis?? []);
             $projectDis = implode(',',$filteredPDV ?? []);
              
            $next_follow_up_date =date('Y-m-d H:i:s', strtotime($request->next_follow_up_date_lead));

            $lead = new Lead;
            $lead->date = $request->date;
            $lead->lead_name =  \Str::title($request->lead_name);
            $lead->lead_email =  $request->customer_email;
            $lead->contact_number = str_replace(' ', '', $request->contact_number['main']);
            $lead->country_code = $finalcountryCode;
            $lead->project_id = $projectName;
            $lead->location_of_client = $request->location_of_customer;
            $lead->project_type = $projectMultiple;
            $lead->assign_employee_id = $request->assign_employee_id; 
            if ($request->alt_contact_number_2['altmain2'] != null) {
                $lead->alt_no_Whatsapp_2 = str_replace(' ', '', $request->alt_contact_number_2['altmain2']);
                $lead->alt_country_code1 = $altfinalcountryCode2;
            } else {
                $lead->alt_no_Whatsapp_2 = null;
                $lead->alt_country_code1 = null;
            }

            if ($request->alt_contact_number['altmain'] != null) {
                $lead->alt_no_Whatsapp = str_replace(' ', '', $request->alt_contact_number['altmain']);
                $lead->alit_country_code = $altfinalcountryCode;
            } else {
                $lead->alt_no_Whatsapp = null;
                $lead->alit_country_code = null;
            } 
            
            if ($request->emergeny_contact_number['relationCC'] != null) {
                $lead->emergeny_contact_number = str_replace(' ', '', $request->emergeny_contact_number['relationCC']);
                $lead->relations_country_code = $RelationsfinalcountryCode;
            } else {
                $lead->emergeny_contact_number = null;
                $lead->relations_country_code = null;
            } 
            
            
            $lead->location_of_leads = $request->buying_location;
            $lead->alt_contact_name = $request->alt_contact_name;
            $lead->alt_contact_name_2 = $request->alt_contact_name_2;
            $lead->source = $request->source;
            if($existingProperty)
            {
                $idsToUpdate = explode(',', $existingProperty);

                if (count($idsToUpdate) > 1) {
                    $lead->regular_investor = "YES";
                }else
                {
                    $lead->regular_investor = "NO";
                }
            }
            
            $lead->existing_property = $existingProperty; 
            //$lead->lead_status = $request->lead_status; 
            $lead->lead_type_bifurcation_id = $request->lead_type;
            $lead->wedding_anniversary = $request->wedding_anniversary;
            $lead->dob = $request->dob;
            $lead->customer_interaction = $request->customer_interaction; 
            $lead->buyer_seller = $request->property_requirement;
            $lead->number_of_units = $request->number_of_units;
            $lead->about_customer = $request->about_customer;
            if ($request->lead_status == 8 || $request->lead_status == 9 || $request->lead_status == 10 || $request->lead_status == 11 ||$request->lead_status == 12) {
                $lead->lead_status = $request->lead_status;
                $lead->common_pool_status = 1; 
                $lead->next_follow_up_date = null;

            }else
            {
                $lead->lead_status = $request->lead_status;
                $lead->common_pool_status = 0;
                $lead->next_follow_up_date = $request->next_follow_up_date_lead;
            }
            $lead->is_featured = $request->is_featured;
            //$lead->next_follow_up_date = $next_follow_up_date;
            $lead->rent = $request->rent; 
            $lead->budget = $request->budget;
            $lead->investment_or_end_user = $request->investment_or_end_user;
           // $lead->regular_investor = $request->regular_investor;
            $lead->reference = $request->reference;
            $lead->reference_contact_number = $request->reference_contact_number;
            $lead->emergeny_contact_number = $request->emergeny_contact_number['relationCC'];
            //$lead->emergeny_contact_number = $request->emergeny_contact_number;
            $lead->emergeny_contact_name = $request->leadnames;
            $lead->relationship = $request->relationship;
            $lead->relationship_name = $request->relationshipName;
            $lead->booking_date = $request->booking_date;
            $lead->booking_amount = $request->booking_amount;
            $lead->booking_project = $projectBooking;
            $lead->created_at = Carbon::now();
            $lead->created_by = Auth::user()->id;
            $lead->dnd = $request->is_dnd;
            $lead->co_follow_up = $request->co_follow_up;
            if (Auth::user()->roles_id == 10) {
                $lead->rwa = Auth::user()->id;
            } 
             else
             {
                 $lead->rwa = $request->rwa ?? null;
             }
            
            
            if($lead->save())
            { 
                // $lead->location_of_leads
                $LeadAssinglaction = DB::table('locations')
                ->join('leads', 'leads.location_of_leads','=','locations.id')
                ->where('locations.id', $lead->location_of_leads)
                ->select('locations.location')->first();

                $buyerSelllerName = DB::table('leads')
                ->join('buyer_sellers', 'leads.buyer_seller','=','buyer_sellers.id')
                ->where('buyer_sellers.id', $lead->buyer_seller)
                ->select('buyer_sellers.name')->first();

		$user = User::where('roles_id',1)->get(); 
              
                 //  Is Customer Type Seller When Send Email & Notification Of Employee
             if($lead->buyer_seller == 2 || $lead->buyer_seller == 11 ||$lead->buyer_seller == 4 || $lead->buyer_seller == 5 ||$lead->buyer_seller == 12 || $lead->buyer_seller == 13)
             { 
                 $IsLeadSellers = DB::table('employees')
                ->join('users','employees.user_id','users.id')
                ->where('employees.employee_location','LIKE','%'.$request->buying_location.'%')
                ->where('organisation_leave',0)
                ->select('employees.employee_location','users.email','users.id')
                ->get();

                foreach ($IsLeadSellers as $emp) { 
                    $EmployeeLocations = explode(',', $emp->employee_location); 
                    foreach ($EmployeeLocations as $empLocation) { 
                        if ($empLocation == $request->buying_location) { 
                            $IsSeller[] = $emp->email;
                            $empNoty[] = $emp->id;
                        }
                    }
                }  
             }  
             //  Is Customer Type Seller When Send Email & Notification Of Employee End

                //dd($LeadAssinglaction->location);
 
                $lead_history['lead_id'] = $lead->id;
                $lead_history['date'] = $lead->date; 
                $lead_history['status_id'] = $lead->lead_status;  
                //$lead_history['customer_interaction'] = $request->customer_interaction; 
                $lead_history['about_customer'] = $lead->about_customer; 
                $lead_history['next_follow_up_date'] = $next_follow_up_date; 
               
             	
             	 // Co Follow Up logic
                    if ($request->co_follow_up == null) { 
                        $customerInteraction = $request->customer_interaction;
                        $cupAction = "";  // No action needed for co_follow_up
                    } else {
                        $coFollowUp = User::find($request->co_follow_up);
                        $cupAction = $coFollowUp ? Auth::user()->name . " Made follow-up Buddy " . $coFollowUp->name : "";
                        $customerInteraction = $request->customer_interaction;
                    }
                    
                    // RWA logic
                    if ($request->rwa == null) { 
                        $cpAction = "";  // No action needed for rwa
                        $customerInteraction = $request->customer_interaction;

                    } else {
                        $cpname = User::find($request->rwa);
                        $cpAction = Auth::user()->name . " Made Channel Partner " . $cpname->name;
                        $customerInteraction = $request->customer_interaction;
                    }
                
                
                    $lead_history['customer_interaction'] = $customerInteraction . "<br>" . $cupAction . "<br>" . $cpAction;
                    
                $lead_history['created_by'] = Auth::user()->id;
                $lead_history['created_at'] = Carbon::now();
                $userData = DB::table('lead_status_histories')->insert($lead_history); 
 
                   $user = User::first(); 
                   
                   $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                   $empNotification = User::where('id',$employeeN->user_id)->first(); 
                   $AdminNotification = User::where('id',22)->first(); 

                   
                   
                   $leadContactNumber = substr_replace($lead['contact_number'],"******",0,6);
                   
                   $buyer_seller_customerType = DB::table('leads')
                   ->join('buyer_sellers', 'leads.buyer_seller','buyer_sellers.id')
                   ->where('buyer_sellers.id',$lead->buyer_seller)
                   ->select('buyer_sellers.name')->first();
                   $actionURL = url(`https://crm.homents.in/`);


		$emp = DB::table('employees')->where('user_id',Auth::user()->id)->first();  
                   
                  if ($request->co_follow_up  && $request->co_follow_up != null) {
                        // dd("co_follow_up");
                        $employeeN = DB::table('employees')->where('user_id',$request->assign_employee_id)->first();  
                        $employeeCoFollowUp = DB::table('employees')->where('user_id',$request->co_follow_up)->first(); 
                        $employeeCoFollowUpRemove = DB::table('employees')->where('user_id',$request->co_follow_up)->first(); 
                        $emPrevious = DB::table('employees')->where('id',$request->assign_employee_id)->first(); 

                        $coFollowUpNotification = User::where('id',$request->co_follow_up)->first();
                        //$RimdercoFollowUpNotification = User::where('id',$ReminderUser->co_follow_up)->first();
			$user = User::where('roles_id',1)->get(); 
                        $details = [
                            'subject' => $emPrevious->employee_name . "  made $employeeCoFollowUp->employee_name  Follow-Up Buddy | $request->lead_name | $buyer_seller_customerType->name | $LeadAssinglaction->location | $request->number_of_units " ." Units",
                            // 'greeting' => 'Hi '.$datastatus['lead_name'],
                            'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                            'thanks' => 'Thank you for choosing Homent.',  
                            'leads' =>$request->lead_name,
                            'EmployeeName' => Auth::user()->id,
                            'leads_status' => $request->lead_status,
                            'leadsID' =>  $lead->id,
                            'property_requirement' => $buyer_seller_customerType->name,
                            'location' => $LeadAssinglaction->location, 
                            'number_of_units' => $request->number_of_units,
                            'privios_emp' => $request->assign_employee_id,
                            'budget' => $request->budget, 
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($lead['id']),
                            'current_empid' => $request->assign_employee_id,
                            'cc' => ['pradeepmishra@homents.in'],
                      ]; 


    
                    //    Notification::sendNow($user,new WelcomeNotification($details));
                      Notification::sendNow($coFollowUpNotification,new WelcomeNotification($details));
                   }   

                   if ( $request->budget == null) {
                      if ($IsSeller == []) {
                        if ($lead->rwa != null ) { 
                            $employeeN = DB::table('employees')
                            ->where('id',$request->assign_employee_id)->first();  
                            
                            $employeeRwa = DB::table('employees')->where('user_id',$lead->rwa)->first();  
                            if ($lead->rwa == Auth::user()->id) {
                                $subject =  "CP " .  $employeeRwa->employee_name ." Lead Assigned to  $employeeN->employee_name | $request->lead_name | $buyerSelllerName->name | $LeadAssinglaction->location | $request->number_of_units ";
                            } 
                            else
                            {
                                $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                            }
                        } else {
                            $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ;
                        }
                        
                        $details = [ 
                            'subject' => $subject,
                            // 'greeting' => 'Hi '.$lead['lead_name'],
                            'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                            // 'thanks' => 'Thank you for choosing Homent.',  
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($lead['id']),
                            'leads' => $request->lead_name,
                            'leads_status' => $lead->lead_status, 
                            'property_requirement' => $buyerSelllerName->name,
                            'location' => $LeadAssinglaction->location,
                            'EmployeeName' => Auth::user()->id,
                            'number_of_units' => $request->number_of_units,
                            'budget' => 'NA',
                            'leadsID' =>  $lead->id,
                            'privios_emp' => $request->assign_employee_id,
                            'cc' => ['pradeepmishra@homents.in'],
                            'current_empid' => $request->assign_employee_id,
                            
                       ];  
                          if (isset($empNoty) && $empNoty == null) {
                            foreach($empNoty as $notyfy)
                            {
                               
                                $empNotifi = User::where('id',$notyfy)->first(); 
                                //  Notification::sendNow($user,new WelcomeNotification($details));
                                Notification::sendNow($empNotifi, new WelcomeNotification($details));
                            }
		             }
		             else
		             {
                        $RimderRwaNotification = User::where('id',$request->rwa)->first(); 
                            if ($RimderRwaNotification) {
                             	// Notification::sendNow($user,new WelcomeNotification($details));
                                Notification::sendNow($RimderRwaNotification, new WelcomeNotification($details));
                            } 
                          
                            //  Notification::sendNow($user,new WelcomeNotification($details));
		                Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));
		             }
                    } else {
                        if ($lead->rwa != null) { 
                            $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                            $employeeRwa = DB::table('employees')->where('user_id',$lead->rwa)->first();  
                            if ($lead->rwa == Auth::user()->id) {
                                $subject =  "CP " .  $employeeRwa->employee_name ." Lead Assigned to  $employeeN->employee_name | $request->lead_name | $buyerSelllerName->name | $LeadAssinglaction->location | $request->number_of_units ";
                            } 
                            else
                            {
                                $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                            }
                        } else {
                            $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ;
                        }
                        $details = [
                            'subject' => $subject,
                            // 'greeting' => 'Hi '.$lead['lead_name'],
                            'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                            // 'thanks' => 'Thank you for choosing Homent.',  
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($lead['id']),
                            'leads' => $request->lead_name,
                            'leads_status' => $lead->lead_status, 
                            'property_requirement' => $buyerSelllerName->name,
                            'location' => $LeadAssinglaction->location,
                            'EmployeeName' => Auth::user()->id,
                            'number_of_units' => $request->number_of_units,
                            'budget' => 'NA',
                            'leadsID' =>  $lead->id,
                            'privios_emp' => $request->assign_employee_id,
                            'cc' => ['pradeepmishra@homents.in'],
                            'current_empid' => $request->assign_employee_id,
                            
                       ];  
                          if (isset($empNoty) && $empNoty == null) {
		                foreach($empNoty as $notyfy)
		                {
                             
		                    $empNotifi = User::where('id',$notyfy)->first(); 
		                    //  Notification::sendNow($user,new WelcomeNotification($details));
		                    Notification::sendNow($empNotifi, new WelcomeNotification($details));
		                }
                        
		             }
		             else
		             {
             
                        $RimderRwaNotification = User::where('id',$request->rwa)->first(); 
                            if ($RimderRwaNotification) {
                             	// Notification::sendNow($user,new WelcomeNotification($details));
                                Notification::sendNow($RimderRwaNotification, new WelcomeNotification($details));
                            } 
                             	// Notification::sendNow($user,new WelcomeNotification($details));
		                Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));
		             }
                    }
                   } else {
                     if ($IsSeller == []) {
                        if ($lead->rwa != null) { 
                            $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                            $employeeRwa = DB::table('employees')->where('user_id',$lead->rwa)->first();  
                            if ($lead->rwa == Auth::user()->id) {
                                $subject =  "CP " .  $employeeRwa->employee_name ." Lead Assigned to  $employeeN->employee_name | $request->lead_name | $buyerSelllerName->name | $LeadAssinglaction->location | $request->number_of_units ";
                            } 
                            else
                            {
                                $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                            }
                        } else {
                            $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                        }
                        $details = [ 
                            'subject' => $subject,
                            // 'greeting' => 'Hi '.$lead['lead_name'],
                            'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                            // 'thanks' => 'Thank you for choosing Homent.',  
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($lead['id']),
                            'leads' => $request->lead_name,
                            'leads_status' => $lead->lead_status, 
                            'property_requirement' => $buyerSelllerName->name,
                            'location' => $LeadAssinglaction->location,
                            'EmployeeName' => Auth::user()->id,
                            'number_of_units' => $request->number_of_units,
                            'budget' => $request->budget,
                            'leadsID' =>  $lead->id,
                            'privios_emp' => $request->assign_employee_id,
                            'cc' => ['pradeepmishra@homents.in'],
                            'current_empid' => $request->assign_employee_id,
                            
                       ];  
                          if (isset($empNoty) && $empNoty == null) {
                                foreach($empNoty as $notyfy)
                                {
                                    $RimderRwaNotification = User::where('id',$lead->rwa)->first();
                                  
                                        if ($RimderRwaNotification) {
                                            // Notification::sendNow($user,new WelcomeNotification($details));
                                            Notification::sendNow($RimderRwaNotification, new WelcomeNotification($details));
                                        } 

                                    $empNotifi = User::where('id',$notyfy)->first(); 
                                  
                                    //  Notification::sendNow($user,new WelcomeNotification($details));
                                    Notification::sendNow($empNotifi, new WelcomeNotification($details));
                                }
                     		}
		             else
		             { 
                        $RimderRwaNotification = User::where('id',$request->rwa)->first(); 
                        if ($RimderRwaNotification) {
                            
                        // Notification::sendNow($user,new WelcomeNotification($details));
                            Notification::sendNow($RimderRwaNotification, new WelcomeNotification($details));
                        }  
                   
                        	// Notification::sendNow($user,new WelcomeNotification($details));
		                Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));
		             }
                    } else {
                        if ($lead->rwa != null) { 
                            $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                            $employeeRwa = DB::table('employees')->where('user_id',$lead->rwa)->first();  
                            if ($lead->rwa == Auth::user()->id) {
                                $subject =  "CP " .  $employeeRwa->employee_name ." Lead Assigned to  $employeeN->employee_name | $request->lead_name | $buyerSelllerName->name | $LeadAssinglaction->location | $request->number_of_units ";
                            } 
                            else
                            {
                                $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                            }
                        } else {
                            $subject = 'New Lead: '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . $request->budget ;
                        }
                        $details = [ 
                            'subject'=> $subject,
                            // 'greeting' => 'Hi '.$lead['lead_name'],
                            'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                            // 'thanks' => 'Thank you for choosing Homent.',  
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($lead['id']),
                            'leads' => $request->lead_name,
                            'leads_status' => $lead->lead_status, 
                            'property_requirement' => $buyerSelllerName->name,
                            'location' => $LeadAssinglaction->location,
                            'EmployeeName' => Auth::user()->id,
                            'number_of_units' => $request->number_of_units,
                            'budget' => $request->budget,
                            'leadsID' =>  $lead->id,
                            'privios_emp' => $request->assign_employee_id,
                            'cc' => ['pradeepmishra@homents.in'],
                            'current_empid' => $request->assign_employee_id,
                            
                       ];  
                        
                         if (isset($empNoty) && $empNoty == null) {
		                foreach($empNoty as $notyfy)
		                {
                            $RimderRwaNotification = User::where('id',$request->rwa)->first(); 
                            if ($RimderRwaNotification) {
                                 
                             	// Notification::sendNow($user,new WelcomeNotification($details));
                                Notification::sendNow($RimderRwaNotification, new WelcomeNotification($details));
                            }  
                             
		                    $empNotifi = User::where('id',$notyfy)->first();
		                    //  Notification::sendNow($user,new WelcomeNotification($details)); 
		                    Notification::sendNow($empNotifi, new WelcomeNotification($details));
		                }
                     	}
		             else
		             {

                         
                        	// Notification::sendNow($user,new WelcomeNotification($details));
		                Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));
		             }
                    }
                   }
                   
                // }  
                  
            }
            else{

                return redirect()->back()->with('message','Somthing Went Wrong');
            }

             
            return redirect('leads')->with('success','Lead Created Successfully');
 
        }
        // else
        // {
        //     return "error";
        // }
        
 
    }  
 
    public function Markasread($id)
    {
        if($id)
        {
            auth()->user()->unreadnotifications->where('id',$id)->markAsRead();
        }
        return redirect('leads');
    }

    public function ClearNotification()
    {
        
       $notification = auth()->user()->notifications()->delete(); 
       return redirect()->back()->with('success', 'All Notification Clear');
    }

    public function ReadSingleNotificationClear($id)
    {
        
         $user = auth()->user()->notifications()->where('id',$id)->delete();
       return redirect()->back()->with('success', 'Notification Clear');
    }
 
 

    public function EditLeads($id ,Request $request)
     {
         
            $idlead =  decrypt($id);  
          $relations = DB::table('relationship')->get();
          $projects = DB::table('projects')->orderBy('project_name')->get();
          $leadnames = DB::table('leads')->get();
          $leads = DB::table('leads')->where('id', $idlead)->first();  
          $teams = DB::table('teams')->where('id', $idlead)->first();
          $locations =  DB::table('locations')->get();
          $LeadTypes = DB::table('lead_type_bifurcation')->get();
          $SourceTypes =  DB::table('source_types')->get();
          $employees =  DB::table('employees')
          ->where('lead_assignment',1)
          ->where('organisation_leave',0)
          ->get();
          $LeadStatus = DB::table('lead_statuses')->get();
          $projectTypes = DB::table('project_types')->get();
          $buyerSellers = DB::table('buyer_sellers')->get(); 
          $number_of_units = DB::table('number_of_units')->get();
          $Budgets = DB::table('budget')->get();
          $preferences = DB::table('preference')->get();
          $property_requirements = DB::table('property_requirement')->get();

          $countryCodeMainIso = DB::table('country')  
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $leads->country_code))
         ->first();

     
         
         $countryCodeAltIso = DB::table('country')  
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $leads->alit_country_code))
         ->first();

        
         $countryCodeAlt2Iso = DB::table('country')
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $leads->alt_country_code1))
         ->first();

         $RelationCountryCodeIso = DB::table('country')
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $leads->relations_country_code))
         ->first();



         return view('pages.leads.edit',compact(['locations','SourceTypes','employees','projectTypes','leads','LeadStatus','buyerSellers','number_of_units','property_requirements','LeadTypes','Budgets','preferences','relations','leadnames','projects','countryCodeAltIso','countryCodeAlt2Iso','countryCodeMainIso','RelationCountryCodeIso'])); 
     }

 
     public function updateLead(Request $request, $id)
    {
 	
         $submit = $request['submit'];
         if($submit == 'submit')
         { 
          
          $date =date('Y-m-d H:i:s', strtotime($request->date));
         
         
 
             $validation = $request->validate([
                  // 'date' => 'required|before_or_equal:today', 
                 //  'customer_email' => 'required',
                 //  'lead_name' => 'required',
             //    'contact_number' =>['required','unique:leads,contact_number'],
                //  'project_type' => 'required',
                 'assign_employee_id' =>'required',
                'buying_location' => 'required',
                'lead_type' => 'required',
                'source' => 'required', 
                'lead_status' => 'required',
                'booking_amount' => 'required_if:lead_status,==,14',
                'booking_project' => 'required_if:lead_status,==,14',
                'booking_date' => 'required_if:lead_status,==,14',
             //    'customer_interaction' => 'required',    
                'customer_requirement' => 'required',
                 // 'property_requirement' => 'required'
              ],
 
               [
                  'lead_status' => 'required',
                 'booking_amount.required_if' => 'The booking amount field is required when lead status is Booking Confirmed',
                 'booking_project.required_if' => 'The booking project field is required when lead status is Booking Confirmed',
                 'booking_date.required_if' => 'The booking date field is required when lead status is Booking Confirmed',
                 // 'contact_number.unique' => 'The contact number has already been taken '. $request->contact_number,
               ]
         );
 
 
 
             $countryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']);
              $withcountryCode = implode(', ',$countryCode);
              $finalcountryCode = str_replace(',', '', $withcountryCode); 
 
              if (preg_replace('/\s+/', '',$request->contact_number['main']) != null) { 
            
                $MaincountryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']);
                $MainwithcountryCode = implode(', ',$MaincountryCode);
                $MainfinalcountryCode = str_replace(',', '', $MainwithcountryCode);  
              } 
             
              if ($request->emergeny_contact_number['relationCC'] ==null) {
                $RelationcountryCode =null;  
                $RelationwithcountryCode = null; 
                $RelationfinalcountryCode = null;
              }else {
                 $RelationcountryCode = explode(preg_replace('/\s+/', '',$request->emergeny_contact_number['relationCC']), $request->emergeny_contact_number['relationCCode']);  
              $RelationwithcountryCode = implode(', ',$RelationcountryCode); 
              $RelationfinalcountryCode = str_replace(',', '', $RelationwithcountryCode);
              }

             // dd(preg_replace('/\s+/', '',$request->alt_contact_number['altmain']));
 

              if (preg_replace('/\s+/', '',$request->alt_contact_number['altmain']) != null) { 
                $altcountryCode = explode(preg_replace('/\s+/', '',$request->alt_contact_number['altmain']), $request->alt_contact_number['altfull']);
                $altwithcountryCode = implode(', ',$altcountryCode);
                $altfinalcountryCode = str_replace(',', '', $altwithcountryCode); 
              }
              if (preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']) != null) { 
          
                $altcountryCode2 = explode(preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']), $request->alt_contact_number_2['altfull2']);
                $altwithcountryCode2 = implode(', ',$altcountryCode2);
                $altfinalcountryCode2 = str_replace(',', '', $altwithcountryCode2); 
              }

               
         
           
           
             $projectMultiple = implode(',',$request->customer_requirement ?? []);
             $projectBooking = implode(',',$request->booking_project ?? []);
             $existingProperty = implode(',',$request->existing_property ?? []);
             $projectName = implode(',',$request->project ?? []); 
             $IsSeller = array();
              $AdminNotification = User::where('id',22)->first();
             
                     $leads_details= DB::table('leads')->where('id',$id)->first();
                    
                 if ($leads_details->assign_employee_id != $request->assign_employee_id) {
 
                     $previous_emp_name=DB::table('employees')->where('id',$leads_details->assign_employee_id)->first();
                     $current_emp_name=DB::table('employees')->where('id',$request->assign_employee_id)->first();


                 if ($leads_details->lead_status == $request->lead_status) {
                     $leadistory = array(
                         'lead_id' => $id,
                         'status_id' => $request->lead_status, 
                         'customer_interaction' => 'Lead Assigned From '.$previous_emp_name->employee_name.' to '.$current_emp_name->employee_name ,
                         'next_follow_up_date' => $request->next_follow_up_date_lead,
                         'created_at' => Carbon::now(),
                         'created_by' => Auth::user()->id,
                     ); 
                     $leadstatushistory = DB::table('lead_status_histories')->insert($leadistory);
                 }else
                 {
                     $leadistory = array(
                         'lead_id' => $id,
                         'status_id' => $request->lead_status, 
                         'customer_interaction' => 'Lead Assigned From '.$previous_emp_name->employee_name.' to '.$current_emp_name->employee_name ,
                         'next_follow_up_date' => $request->next_follow_up_date_lead,
                         'created_at' => Carbon::now(),
                         'created_by' => Auth::user()->id,
                     ); 
                     $leadstatushistory = DB::table('lead_status_histories')->insert($leadistory);
                 }
                 } else {
                     if ($leads_details->lead_status == $request->lead_status) {
                                 
                     }else
                     {
                         $leadistory = array(
                             'lead_id' => $id,
                             'status_id' => $request->lead_status, 
                             'customer_interaction' => $request->customer_interaction,
                             'next_follow_up_date' => $request->next_follow_up_date_lead,
                             'created_at' => Carbon::now(),
                             'created_by' => Auth::user()->id,
                         ); 
                         $leadstatushistory = DB::table('lead_status_histories')->insert($leadistory);
                     }
                 }
                 
 
             
                 //return ucwords($request->lead_name);
                 
             $lead = array();
             $lead['date'] = $date;
             $lead['lead_name'] =  \Str::title($request->lead_name);
             $lead['contact_number'] = str_replace(' ', '', $request->contact_number['main']); 
              $lead['country_code'] = $MainfinalcountryCode;
            //  $lead['alt_no_Whatsapp'] = str_replace(' ', '', $request->alt_contact_number);
             //$lead['alt_no_Whatsapp_2'] = str_replace(' ', '', $request->alt_contact_number_2);
              $lead['alt_no_Whatsapp'] =  preg_replace('/\s+/', '',$request->alt_contact_number['altmain']) ?? null;
             $lead['alit_country_code'] =  $altfinalcountryCode ?? null; 
             $lead['alt_no_Whatsapp_2'] =  preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']) ?? null;
              $lead['alt_country_code1'] =   $altfinalcountryCode2 ?? null;
             $lead['project_type'] = $projectMultiple;
             $lead['assign_employee_id'] = $request->assign_employee_id; 
             $lead['location_of_leads'] = $request->buying_location;
             $lead['source'] = $request->source;
             if ($request->lead_status == 8 || $request->lead_status == 9 || $request->lead_status == 10 || $request->lead_status == 11 ||$request->lead_status == 12) {
                 $lead['lead_status'] = $request->lead_status;
                 $lead['common_pool_status'] = 1 ?? $request->common_pool_status;
                 $lead['updated_at'] = Carbon::now();
                 $lead['next_follow_up_date'] = null;
 
             }else
             {
                 $lead['lead_status'] = $request->lead_status;
                 $lead['common_pool_status'] = 0 ?? 1;
                 $lead['next_follow_up_date'] = $request->next_follow_up_date_lead;
             }
            
             $lead['rera_number'] = $request->rera_number;
             $lead['lead_type_bifurcation_id'] = $request->lead_type;
             $lead['alt_contact_name'] = $request->alt_contact_name;
             $lead['alt_contact_name_2'] = $request->alt_contact_name_2;
             $lead['about_customer'] = $request->about_customer;
             $lead['customer_interaction'] = $request->customer_interaction;
             $lead['reference'] = $request->reference;
             $lead['reference_contact_number'] = $request->reference_contact_number;
             //$lead['project_id'] = $projectName == "" ? $projectName : $projectDis  ?? null ;
             $lead['project_id'] = $projectName != null ? $projectName : null ;
             $lead['booking_project'] = $projectBooking;
             $lead['existing_property'] = $existingProperty;
             $lead['lead_email'] = $request->customer_email;
             $lead['investment_or_end_user'] = $request->investment_or_end_user;
             $lead['budget'] = $request->budget;
             $lead['booking_amount'] = $request->booking_amount;
             $lead['wedding_anniversary'] = $request->wedding_anniversary;
             $lead['dob'] = $request->dob;
             $lead['total_number_of_booking'] = $request->total_number_of_booking;
             $lead['location_of_client'] = $request->location_of_client;
             $lead['real_location'] = $request->real_location;
             $lead['personal_details_if_any'] = $request->personal_details_if_any;
             $lead['relationship'] = $request->relationship;
             $lead['relationship_name'] = $request->relationshipName;
             $lead['emergeny_contact_name'] = $request->leadnames;
             $lead['regular_investor'] = $request->regular_investor;
             $lead['emergeny_contact_number'] = preg_replace('/\s+/', '',$request->emergeny_contact_number['relationCC']) ?? null; 
             $lead['relations_country_code'] = $request->emergeny_contact_number['relationCCode'] ?? null;
             $lead['underconstruction'] = $request->underconstruction; 
             if($request->buyer_seller == 1 || $request->buyer_seller == 2 || $request->buyer_seller == 3)
             {
                 $lead['rent'] = null ;
             }
             else
             {
                 $lead['rent'] = $request->rent;
             }
             $lead['price_quoted'] = $request->price_quoted;
             $lead['booking_date'] = $request->booking_date;
             $lead['property_details'] = $request->propertyDetails;   
             $lead['number_of_units'] = $request->number_of_units;  
             $lead['property_requirement'] = $request->property_requirement;  
             $lead['buyer_seller'] = $request->buyer_seller; 
             $lead['is_featured'] = $request->is_featured;
             $lead['dnd'] = $request->is_dnd;
             $lead['updated_at'] = Carbon::now();
 
            
             
             $buyer_seller_customerType = DB::table('leads')
             ->join('buyer_sellers', 'leads.buyer_seller','buyer_sellers.id')
             ->where('buyer_sellers.id',$lead['buyer_seller'])
             ->select('buyer_sellers.name')->first();
 
              
 
             $leadContactNumber = substr_replace($lead['contact_number'],"******",0,6); 
             $notificationExisit = DB::table('leads')->where('id',$request->leadId)->first();
               
 
             $LeadAssinglaction = DB::table('locations')
             ->leftjoin('leads', 'leads.location_of_leads','=','locations.id')
             ->where('locations.id', $lead['location_of_leads'])
             ->select('locations.location')->first();

             $projectDiscussed = Project::whereIn('id',explode(',',$lead['project_id']))
             ->select('project_name')->get();
             
            //  return $projectDiscussed;
             $PD = array(); 
             foreach($projectDiscussed as $projectDis)
             { 
               $PD[] = $projectDis->project_name;
               $string = 'Project Discussed: '. implode(', ', $PD);
               
             }

             $emPrevious = DB::table('employees')
             ->where('id',$notificationExisit->assign_employee_id)
             ->first();
             $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                if ($request->buyer_seller == 2) {
                    $subject = $emPrevious->employee_name. ' Lead Assigned to: ' .$employeeN->employee_name .':'.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' . ' | ' . isset($string)  ?? 'N/A' . ' | ' . $lead['contact_number'];
                }
                else{
                    $subject = $emPrevious->employee_name. ' Lead Assigned to: ' .$employeeN->employee_name .':'.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA'  . ' | ' . isset($string)  ?? 'N/A'. ' | ' . $lead['contact_number'];
                }


             //  Is Customer Type Seller When Send Email & Notification Of Employee
             
               if($lead['buyer_seller'] == 2 || $lead['buyer_seller'] == 11 || $lead['buyer_seller'] == 4 || $lead['buyer_seller'] == 5 || $lead['buyer_seller'] == 12 || $lead['buyer_seller'] == 13)
             { 
                 
                
                $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                $empNotification = User::where('id',$employeeN->user_id)->first(); 

                $IsLeadSellers = DB::table('employees')
                ->join('users','employees.user_id','users.id')
                ->where('employees.employee_location','LIKE','%'.$request->buying_location.'%')
                ->where('organisation_leave',0)
                ->select('employees.employee_location','users.email','users.id')
                ->get();

                foreach ($IsLeadSellers as $emp) { 
                    $EmployeeLocations = explode(',', $emp->employee_location); 
                    foreach ($EmployeeLocations as $empLocation) { 
                        if ($empLocation == $request->buying_location) { 
                            $IsSeller[] = $emp->email;
                            $empNoty[] = $emp->id;
                        }
                    }
                } 

                 if ($leads_details->assign_employee_id != $request->assign_employee_id) {  
                    $details = [
                        'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' .$request->budget ?? 'N/A', ' | ' .isset($string) ?? 'N/A',' | ' .$lead['contact_number'],
                         //'greeting' => 'Hi '.$lead['lead_name'],
                        'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                        'thanks' => 'Thank you for choosing Homent.',  
                        'leads' => $request->lead_name,
                        'leadsID' =>  $id,
                        'leads_status' => $request->lead_status,
                        'EmployeeName' => Auth::user()->id,
                        'property_requirement' => $buyer_seller_customerType->name,
                        'location' => $LeadAssinglaction->location, 
                         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                        'number_of_units' => $request->number_of_units,
                        'privios_emp' => $notificationExisit->assign_employee_id,
                        'budget' => $request->budget, 
                        'current_empid' => $lead['assign_employee_id'],
                        'message' => 'Lead edited by ' . \Auth::user()->name, 
                        //'cc' => $leads_details->buyer_seller == 2 ? [] : [$IsSeller],
                        'cc' => [],
                    ];   
                   
                     //Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));    

                    foreach($empNoty as $notyfy)
                    {
                       
                        $empNotifi = User::where('id',$notyfy)->first(); 
                        Notification::sendNow($empNotifi, new WelcomeNotification($details));
                    }
                 } 
                 elseif($leads_details->assign_employee_id == $request->assign_employee_id)
                 {
                   //  return "jju";
                 }  
                 else
                 {
                    $details = [
                        'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' .$request->budget ?? 'N/A', ' | ' .isset($string) ?? 'N/A',' | ' .$lead['contact_number'],
                         //'greeting' => 'Hi '.$lead['lead_name'],
                        'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                        'thanks' => 'Thank you for choosing Homent.',  
                        'leads' => $request->lead_name,
                        'leadsID' =>  $id,
                        'leads_status' => $request->lead_status,
                        'EmployeeName' => Auth::user()->id,
                        'property_requirement' => $buyer_seller_customerType->name,
                        'location' => $LeadAssinglaction->location, 
                         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                        'number_of_units' => $request->number_of_units,
                        'privios_emp' => $notificationExisit->assign_employee_id,
                        'budget' => $request->budget, 
                        'current_empid' => $lead['assign_employee_id'],
                        'message' => 'Lead edited by ' . \Auth::user()->name, 
                       'cc' => [],
                    ];   
                   
                   
                     //Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));    

                    foreach($empNoty as $notyfy)
                    {
                       
                        $empNotifi = User::where('id',$notyfy)->first(); 
                        Notification::sendNow($empNotifi, new WelcomeNotification($details));
                    }
                 }
                   
                 if($leads_details->buyer_seller  != $lead['buyer_seller'])
                 {
                    $details = [
                        'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' .$request->budget ?? 'N/A', ' | ' .isset($string) ?? 'N/A',' | ' .$lead['contact_number'],
                         //'greeting' => 'Hi '.$lead['lead_name'],
                        'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                        'thanks' => 'Thank you for choosing Homent.',  
                        'leads' => $request->lead_name,
                        'leadsID' =>  $id,
                        'leads_status' => $request->lead_status,
                        'EmployeeName' => Auth::user()->id,
                        'property_requirement' => $buyer_seller_customerType->name,
                        'location' => $LeadAssinglaction->location, 
                         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                        'number_of_units' => $request->number_of_units,
                        'privios_emp' => $notificationExisit->assign_employee_id,
                        'budget' => $request->budget, 
                        'current_empid' => $lead['assign_employee_id'],
                        'message' => 'Lead edited by ' . \Auth::user()->name, 
                        'cc' => [],
                    ];  
                    // return $details;
                     //Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));    

                    foreach($empNoty as $notyfy)
                    {
                       
                        $empNotifi = User::where('id',$notyfy)->first(); 
                        Notification::sendNow($empNotifi, new WelcomeNotification($details));
                    }
                 }
 
              
               
             }  

             $data = DB::table('leads')->where('id',$id)->update($lead);
             //  Is Customer Type Seller When Send Email & Notification Of Employee End
             
 
              $employeeNo = DB::table('employees')->where('id',$request->assign_employee_id)->first(); 
              if(Auth::user()->id == $employeeNo->user_id)
              {
                 if ($request->budget == null) {
                         
                     $user = User::first();
                      $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                      $empNotification = User::where('id',$employeeN->user_id)->first();  
                     if ($request->assign_employee_id == $notificationExisit->assign_employee_id ) {
                         $details = [
                             'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ,
                             // 'greeting' => 'Hi '.$lead['lead_name'],
                             'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                             'thanks' => 'Thank you for choosing Homent.',  
                             'leads' => $request->lead_name,
                             'EmployeeName' => Auth::user()->id, 
                             'leads_status' => $request->lead_status,
                             'leadsID' =>  $id, 
                             'property_requirement' => $buyer_seller_customerType->name,
                             'location' => $LeadAssinglaction->location, 
                              'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                             'number_of_units' => $request->number_of_units,
                             'privios_emp' => $notificationExisit->assign_employee_id,
                             'budget' => 'NA', 
                             'current_empid' => $lead['assign_employee_id'],
                             'cc' => [$IsSeller],
                             'message' => 'Lead edited by ' . \Auth::user()->name, 
                        ];  
                         
                      } else {
                         $emPrevious = DB::table('employees')
                         ->where('id',$notificationExisit->assign_employee_id)
                         ->first();
                         
                         $details = [
                             'subject' => $emPrevious->employee_name. ' Lead Assigned to: ' .$employeeN->employee_name .':'.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ,
                             // 'greeting' => 'Hi '.$lead['lead_name'],
                             'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                             'thanks' => 'Thank you for choosing Homent.',  
                             'leads' => $request->lead_name,
                             'EmployeeName' => Auth::user()->id,
                             'leads_status' => $request->lead_status,
                             'leadsID' =>  $id,
                             'property_requirement' => $buyer_seller_customerType->name,
                             'location' => $LeadAssinglaction->location, 
                             'number_of_units' => $request->number_of_units,
                             'privios_emp' => $notificationExisit->assign_employee_id,
                             'budget' => $request->budget, 
                              'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                             'current_empid' => $lead['assign_employee_id'],
                             'cc' => ['pradeepmishra@homents.in',$IsSeller],
                             'message' => 'Lead edited by ' . \Auth::user()->name, 
                        ]; 
                      } 
                     
                      
                        Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));  
                       //Notification::sendNow(new WelcomeNotification($details)); 
                 } else {
 
                    
                     $user = User::first();
                      $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                      $empNotification = User::where('id',$employeeN->user_id)->first();  
 
                     if ($request->assign_employee_id == $notificationExisit->assign_employee_id ) {
                         $details = [
                             'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' .$request->budget ,
                             // 'greeting' => 'Hi '.$lead['lead_name'],
                             'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                             'thanks' => 'Thank you for choosing Homent.',  
                             'leads' => $request->lead_name,
                             'leadsID' =>  $id,
                             'leads_status' => $request->lead_status,
                             'EmployeeName' => Auth::user()->id,
                             'property_requirement' => $buyer_seller_customerType->name,
                             'location' => $LeadAssinglaction->location, 
                             'number_of_units' => $request->number_of_units,
                              'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                             'privios_emp' => $notificationExisit->assign_employee_id,
                             'budget' => $request->budget, 
                             'current_empid' => $lead['assign_employee_id'],
                             'cc' => [$IsSeller],
                             'message' => 'Lead edited by ' . \Auth::user()->name, 
                         ]; 

                          
                      } else {
                         $emPrevious = DB::table('employees')
                         ->where('id',$notificationExisit->assign_employee_id)
                         ->first();
                         
                         $details = [
                             'subject' => $emPrevious->employee_name. ' Lead Assigned to: ' .$employeeN->employee_name .':'.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ,
                             // 'greeting' => 'Hi '.$lead['lead_name'],
                             'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                             'thanks' => 'Thank you for choosing Homent.',  
                             'leads' => $request->lead_name,
                             'EmployeeName' => Auth::user()->id,
                             'leads_status' => $request->lead_status,
                             'leadsID' =>  $id,
                              'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                             'property_requirement' => $buyer_seller_customerType->name,
                             'location' => $LeadAssinglaction->location, 
                             'number_of_units' => $request->number_of_units,
                             'privios_emp' => $notificationExisit->assign_employee_id,
                             'budget' => $request->budget, 
                             'current_empid' => $lead['assign_employee_id'],
                             'cc' => ['pradeepmishra@homents.in',$IsSeller],
                             'message' => 'Lead edited by ' . \Auth::user()->name, 
                        ]; 
                      } 
                     

                    
                    
                     
                      Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));  
                     // Notification::sendNow(new WelcomeNotification($details));
                 }
                 
              }else
              {
  
                 // Another employee by IF lead update when nofication send Assign Employyed
 
                 // if ($notificationExisit->assign_employee_id != $request->assign_employee_id) 
                 // { 
                     if ($request->budget == null) {
                         
                         $user = User::first();
                         $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                         $empNotification = User::where('id',$employeeN->user_id)->first(); 
                         if ($request->assign_employee_id == $notificationExisit->assign_employee_id ) {
                             $details = [
                                 'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ,
                                 // 'greeting' => 'Hi '.$lead['lead_name'],
                                 'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                                 'thanks' => 'Thank you for choosing Homent.',  
                                 'leads' => $request->lead_name,
                                 'EmployeeName' => Auth::user()->id,
                                 'leads_status' => $request->lead_status,
                                 'leadsID' =>  $id,
                                 'property_requirement' => $buyer_seller_customerType->name,
                                 'location' => $LeadAssinglaction->location, 
                                  'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                                 'number_of_units' => $request->number_of_units,
                                 'privios_emp' => $notificationExisit->assign_employee_id,
                                 'budget' => "NA", 
                                 'current_empid' => $lead['assign_employee_id'],
                                 'message' => 'Lead edited by ' . \Auth::user()->name, 
                                 'cc' => [$IsSeller],
                            ]; 

                             
                          } else {
                             $emPrevious = DB::table('employees')
                             ->where('id',$notificationExisit->assign_employee_id)
                             ->first();
                             
                             $details = [
                                 'subject' => $emPrevious->employee_name. ' Lead Assigned to: ' .$employeeN->employee_name .':'.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' . 'NA' ,
                                 // 'greeting' => 'Hi '.$lead['lead_name'],
                                 'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                                 'thanks' => 'Thank you for choosing Homent.',  
                                 'leads' => $request->lead_name,
                                 'EmployeeName' => Auth::user()->id,
                                 'leads_status' => $request->lead_status,
                                 'leadsID' =>  $id,
                                 'property_requirement' => $buyer_seller_customerType->name,
                                 'location' => $LeadAssinglaction->location, 
                                  'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                                 'number_of_units' => $request->number_of_units,
                                 'privios_emp' => $notificationExisit->assign_employee_id,
                                 'budget' => $request->budget, 
                                 'current_empid' => $lead['assign_employee_id'],
                                 'message' => 'Lead edited by ' . \Auth::user()->name, 
                                 'cc' => ['pradeepmishra@homents.in',$IsSeller],
                            ]; 
                          } 
                           
                           Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));  
                        //   Notification::sendNow($user, new WelcomeNotification($details)); 
                     } else {
 
                          
                         $user = User::first();
                         $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                         $empNotification = User::where('id',$employeeN->user_id)->first();  
 
                         if ($request->assign_employee_id == $notificationExisit->assign_employee_id ) {
                             $details = [
                                 'subject' => 'Lead Edited by ' .Auth::user()->name .' | '.$lead['lead_name'].' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $request->number_of_units . ' | ' .$request->budget ,
                                 // 'greeting' => 'Hi '.$lead['lead_name'],
                                 'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                                 'thanks' => 'Thank you for choosing Homent.',  
                                 'leads' => $request->lead_name,
                                 'leadsID' =>  $id,
                                 'leads_status' => $request->lead_status,
                                 'EmployeeName' => Auth::user()->id,
                                 'property_requirement' => $buyer_seller_customerType->name,
                                 'location' => $LeadAssinglaction->location, 
                                  'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                                 'number_of_units' => $request->number_of_units,
                                 'privios_emp' => $notificationExisit->assign_employee_id,
                                 'budget' => $request->budget, 
                                 'current_empid' => $lead['assign_employee_id'],
                                 'message' => 'Lead edited by ' . \Auth::user()->name, 
                                 'cc' => [],
                             ]; 

                             
                              
                          } else {
                            

                             $details = [
                                 'subject' => $subject,
                                 // 'greeting' => 'Hi '.$lead['lead_name'],
                                 'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                                 'thanks' => 'Thank you for choosing Homent.',  
                                 'leads' => $request->lead_name,
                                 'EmployeeName' => Auth::user()->id,
                                 'leads_status' => $request->lead_status,
                                 'leadsID' =>  $id,
                                 'property_requirement' => $buyer_seller_customerType->name,
                                 'location' => $LeadAssinglaction->location, 
                                 'number_of_units' => $request->number_of_units,
                                 'privios_emp' => $notificationExisit->assign_employee_id,
                                 'budget' => $request->budget, 
                                 'current_empid' => $lead['assign_employee_id'],
                                  'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                                  'message' => 'Lead edited by ' . \Auth::user()->name, 
                                 'cc' => ['pradeepmishra@homents.in',$IsSeller],
                            ]; 
                            // return  $details;
                          }  
                         Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));  
                        //  Notification::sendNow($user, new WelcomeNotification($details)); 
                     }
                 
                 
                 // } 
 
              }
             
                
             if($data == true)
             {
                 // if ($notificationExisit->lead_status == $request->lead_status) {
                     
                 // }else
                 // {
                 //     $leadistory = array(
                 //         'lead_id' => $id,
                 //         'status_id' => $request->lead_status, 
                 //         'customer_interaction' => $request->customer_interaction,
                 //         'next_follow_up_date' => $request->next_follow_up_date_lead,
                 //         'created_at' => Carbon::now(),
                 //         'created_by' => Auth::user()->id,
                 //     ); 
                 //     $leadstatushistory = DB::table('lead_status_histories')->insert($leadistory);
                 // }
                
                 return redirect('lead-status/'.encrypt($id))->with('success','Lead Updated Successfully');
             }
             else
             {
             
                 return redirect('edit-leads/'.$id)->with('success','Lead Nothing To Update');
             }
              
         }
         else
         {
             return "error";
         }
         
    }

    public function RelativeLeadContactNumber(Request $request)
    {
        // return $request;
        $LeadData = DB::table('leads')->where('contact_number', 9956595233)->first();

        return response()->json([
            "LeadNumber" => $LeadData,
            "status" => 200
        ]);

    }


      public function DeleteLead($id)
     {
          // return decrypt($id);
         $leads = DB::table('leads')->where('id', decrypt($id))->delete();
         $lead_status_histories = DB::table('lead_status_histories')->where('lead_id', decrypt($id))->delete();
         
         return redirect('leads')->with('Delete','Lead Deleted Successfully');
     }

     public function LeadStatus($id)
     { 
         
        $leads = DB::table('leads') 
        ->join('employees','employees.id','leads.assign_employee_id')
        ->select('leads.*','employees.employee_name','employees.official_phone_number','employees.emp_country_code')
        ->where('leads.id', decrypt($id))
        ->first();  
        if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 11) {
            // Next and previous 
            $leadDetails = Lead::find(decrypt($id)); 
            $previous = Lead::where('id', '<', $leadDetails->id)->max('id'); 
            $next = Lead::where('id', '>', $leadDetails->id)->min('id');
        } else {
           // Next and previous 
            $leadDetails = Lead::find(decrypt($id)); 
            $previous = Lead::where('id', '<', $leadDetails->id)->where('assign_employee_id',$leads->assign_employee_id)->max('id'); 
            $next = Lead::where('id', '>', $leadDetails->id)->where('assign_employee_id',$leads->assign_employee_id)->min('id');
        }
        
        
         

        // Lead History
        $leadstatushistory = DB::table('lead_status_histories')
        ->where('lead_id',decrypt($id))->latest()->paginate(10);
  
        
         return view('pages.leads.lead-info',compact(['leads','leadstatushistory','leadDetails','previous','next']));
     }


     public function SearchLeads(Request $request)
     {
        
        $output = ""; 
        if (Auth::user()->roles_id == 1 || Auth::user()->roles_id == 1) {
            $leads= Lead::where('lead_name','LIKE','%'.$request->search.'%')
            ->orwhere('contact_number','LIKE','%'.$request->search.'%')->get(); 
            
           
            foreach($leads as $lead)
            {
                if ($lead->common_pool_status == 1) {
                    return redirect()->back()->with('moveCommonPool',$output .="This lead in common pool");
                } 
                elseif($lead->lead_status == 14)
                {
                    return redirect()->back()->with('BookingConfirm',$output .="This lead in Booking Confirm");
                }
            }
            if(count($leads)>0)
            { 
                return view('pages.leads.search',compact(['leads']));
            }
            else
            { 
                return redirect()->back()->with('NoSearch',$output .="No data Found");
            }
        } else {
            $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
            $leads= DB::table('leads')
            ->where('lead_name','LIKE','%'.$request->search.'%' ) 
            // ->orwhere('contact_number','LIKE','%'.$request->search.'%')
            ->where('assign_employee_id','=',$emp->id)
            ->get();  
            foreach($leads as $lead)
            {
                if ($lead->common_pool_status == 1) {
                    return redirect()->back()->with('moveCommonPool',$output .="This lead in common pool");
                } 
                elseif($lead->lead_status == 14)
                {
                    return redirect()->back()->with('BookingConfirm',$output .="This lead in Booking Confirm");
                }
            }
            if(count($leads)>0)
            { 
                return view('pages.leads.search',compact(['leads']));
            }
            else
            { 
                return redirect()->back()->with('NoSearch',$output .="No data Found");
            }
        }
        
          
         
    }  

    public function SearchByContact(Request $request, $id)
     {
        
        
        $searchByContact= Lead::where('contact_number','LIKE','%'.$id.'%')
        ->select('leads.lead_name','leads.id')->first();
        if($searchByContact == "")
        {
            return "";
        }
        else
        {
            $data = array();
            $data['lead_name'] = $searchByContact->lead_name;
            $data['id'] = encrypt($searchByContact->id);
            return  $data;
            // return $output = "";
        } 
    }
    
    public function searchByContactEdit(Request $request, $l_id, $id)
	{
	    $searchByContact = Lead::where('contact_number', 'LIKE', '%' . $id . '%')
		->where('id', '!=', $l_id)
		->select('lead_name', 'id', 'contact_number')
		->first();

	    if (!$searchByContact) {
		return "";
	    }

	    $data = [
		'lead_name' => $searchByContact->lead_name,
		'id' => encrypt($searchByContact->id),
		'contact_number' => $searchByContact->contact_number,
	    ];

	    return $data;
	}
    
   

    public function IsLeadCreate(Request $request,$id)
    {

       // return "td";
        $LeadStatus = DB::table('leads')
        ->where('id',decrypt($id))->first();

        if($LeadStatus->lead_status == 1)
        {
            $leadsStatus = array();
            $leadsStatus['lead_status'] = 2;
            DB::table('leads')->where('id',decrypt($id))->update($leadsStatus); 
            $lead_status_histories = array();
            $lead_status_histories['status_id'] = 2;
            $lead_status_histories['lead_id'] = $LeadStatus->id;
            $lead_status_histories['created_at'] = Carbon::now();
            $lead_status_histories['created_by'] = Auth::user()->id;
            DB::table('lead_status_histories')->where('id',decrypt($id))->insert($lead_status_histories); 
            return redirect('edit-leads/'.encrypt($LeadStatus->id));
        }
        else
        {
            return  redirect()->back();
        }
    }

    public function IsLeadHistory(Request $request,$id)
    {
 
        $LeadStatus = DB::table('leads')
        ->where('id',decrypt($id))->first();

        $ip = "1.39.235.217"; 
        $position = Location::get($ip);

        if($LeadStatus->lead_status == 1) 
        { 
            
            $leadsStatus = array();
            $leadsStatus['lead_status'] = 2;
            $leadsStatus['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            DB::table('leads')->where('id',decrypt($id))->update($leadsStatus); 

            $lead_status_histories = array();
            $lead_status_histories['status_id'] = 2;
            $lead_status_histories['date'] = $LeadStatus->date;
            $lead_status_histories['lead_id'] = $LeadStatus->id;
            $lead_status_histories['created_at'] = Carbon::now();
            $lead_status_histories['created_by'] = Auth::user()->id;
            $lead_status_histories['customer_interaction'] = "Lead has been opened";
            DB::table('lead_status_histories')->where('id',decrypt($id))->insert($lead_status_histories); 

            $lead_info_history['lead_id'] =$LeadStatus->id;
            $lead_info_history['user_id'] = Auth::user()->id;
            $lead_info_history['regionName'] = $position->regionName ?? null;
            $lead_info_history['cityName'] = $position->cityName ?? null;
            $lead_info_history['created_at'] = Carbon::now();
            $lead_info_history['updated_at'] = Carbon::now();
            $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);


            return redirect('lead-status/'.encrypt($LeadStatus->id));
        }
        else
        { 

           
             
                $lead_info_history['lead_id'] =$LeadStatus->id;
                $lead_info_history['user_id'] = Auth::user()->id;
                $lead_info_history['regionName'] = $position->regionName ?? null;
                $lead_info_history['cityName'] = $position->cityName ?? null;
                $lead_info_history['created_at'] = Carbon::now();
                $lead_info_history['updated_at'] = Carbon::now();
                $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);

                return redirect('lead-status/'.encrypt($LeadStatus->id));
         
        }
    }

    public function IsLeadHistoryUpdate(Request $request,$id)
    {

        
        
        $LeadStatus = DB::table('leads')
        ->where('id',decrypt($id))->first();

        if($LeadStatus->lead_status == 1) 
        {
            dd("asd");
            $leadsStatus = array();
            $leadsStatus['lead_status'] = 2;
            DB::table('leads')->where('id',decrypt($id))->update($leadsStatus); 

            $lead_status_histories = array();
            $lead_status_histories['status_id'] = 2;
            $lead_status_histories['lead_id'] = $LeadStatus->id;
            $lead_status_histories['created_at'] = Carbon::now();
            $lead_status_histories['created_by'] = Auth::user()->id;
            DB::table('lead_status_histories')->where('id',decrypt($id))->insert($lead_status_histories); 
            return redirect('lead-stutas-update/'.encrypt($LeadStatus->id));
        }
        else
        {  
            $lead_info_history['lead_id'] = $LeadStatus->id;
                $lead_info_history['user_id'] = Auth::user()->id;
                $lead_info_history['regionName'] = $position->regionName ?? null;
                $lead_info_history['cityName'] = $position->cityName ?? null;
                $lead_info_history['created_at'] = Carbon::now();
                $lead_info_history['updated_at'] = Carbon::now();
                $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);
        }
    }

     public function isLeadsNumberExist(Request $request)
    {
         
    
    	$output = $request->lead_search." ";
        
        $leads= Lead::where('contact_number','LIKE','%'.$request->lead_search.'%')->first();  
        $ifRwa =  DB::table('leads')
        ->where('rwa',Auth::user()->id)
        ->where('contact_number',$request->lead_search)
        ->first();
        
         $ip = request()->ip(); 
         
         //$ip = "1.39.235.217"; 
        $position = Location::get($ip);
        //dd($position->regionName);
             
        if(empty($leads))
        {
 
            $leadsAlt= Lead::where('alt_no_Whatsapp','LIKE','%'.$request->lead_search.'%')->first(); 
            // return $leadsAlt;
            if ($leadsAlt) {
                $lead_info_history['lead_id'] = $leadsAlt->id;
                $lead_info_history['user_id'] = Auth::user()->id;
                $lead_info_history['regionName'] = $position->regionName ?? null;
                $lead_info_history['cityName'] = $position->cityName ?? null;
                $lead_info_history['created_at'] = Carbon::now();
                $lead_info_history['updated_at'] = Carbon::now();
                $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);

                if (!is_null($ifRwa)) { 
                    if($ifRwa->rwa == Auth::user()->id && $ifRwa->contact_number == $leads->contact_number)
                    {
                        return redirect()->route('lead-status',encrypt($leads->id));
                    }
                     
                } else {
                    if ($request->lead_search != isset($ifRwa->contact_number) && Auth::user()->roles_id == 10)
                    {  
                        return redirect()->back()->with('rwa',"You don't have permission to view this lead");
                    }
                    return redirect()->route('lead-status',encrypt($leadsAlt->id)); 
                } 
            }
            if(empty($leadsAlt))
            {  
                $leads= Lead::where('alt_no_Whatsapp_2','LIKE','%'.$request->lead_search.'%')->first(); 
                if($leads){  
                $lead_info_history['lead_id'] = $leads->id;
                $lead_info_history['regionName'] = $position->regionName ?? null;
                $lead_info_history['cityName'] = $position->cityName ?? null; 
                $lead_info_history['created_at'] = Carbon::now();
                $lead_info_history['updated_at'] = Carbon::now();
                $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);
                //DB::table('global_search')->insert($data);

                if (!is_null($ifRwa)) { 
                    if($ifRwa->rwa == Auth::user()->id && $ifRwa->contact_number == $leads->contact_number)
                    {
                        return redirect()->route('lead-status',encrypt($leads->id));
                    }
                     
                } else {
                    if ($request->lead_search != isset($ifRwa->contact_number) && Auth::user()->roles_id == 10)
                    { 
                       
                        return redirect()->back()->with('rwa',"You don't have permission to view this lead");
                    }
                    return redirect()->route('lead-status',encrypt($leads->id));
                    // Code to handle when $ifRwa is null
                } 
            }
                else
                {
                   
		         $data = [
		                'emp_id' => Auth::user()->id,
		                'gs_mobile_number' => $request->lead_search,
		                'created_at' => Carbon::now(),
		                'updated_at' => Carbon::now(),
		            ];
		     
		            DB::table('global_search')->insert($data);
 
                    return redirect()->route('create-leads')->with('mySearch',$output .="No data Found");
                }
            }
            
        }

        if(!empty($leads))
        { 

             
                $lead_info_history['lead_id'] = $leads->id;
                 $lead_info_history['user_id'] = Auth::user()->id;
                 $lead_info_history['regionName'] = $position->regionName ?? null;
                $lead_info_history['cityName'] = $position->cityName ?? null;
                 $lead_info_history['created_at'] = Carbon::now();
                 $lead_info_history['updated_at'] = Carbon::now();
                 $lead_info_histroy_inster = DB::table('lead_info_history')->insert($lead_info_history);

                 if (!is_null($ifRwa)) { 
                    if($ifRwa->rwa == Auth::user()->id && $ifRwa->contact_number == $leads->contact_number)
                    {
                        return redirect()->route('lead-status',encrypt($leads->id));
                    }
                     
                } else {
                    if ($request->lead_search != isset($ifRwa->contact_number) && Auth::user()->roles_id == 10)
                    {  
                        return redirect()->back()->with('rwa',"You don't have permission to view this lead");
                    }
                    return redirect()->route('lead-status',encrypt($leads->id));
                    // Code to handle when $ifRwa is null
                } 
              
                 
        }
        else
        {
           
            return redirect()->route('create-leads')->with('mySearch',$output .="No data Found");
        }
           
        
    }

    public function SearchByEmployeeAssignLocation(Request $request, $id)
     {
 
        $SearchByEmployeeAssignLocation= DB::table('employees')
        ->where('id',$id)->first();
        
        $select = explode(',',$SearchByEmployeeAssignLocation->employee_location);

        $locationName = DB::table('locations')
        ->whereIn('id',$select)->get();
        return $locationName;

        if($SearchByEmployeeAssignLocation == "")
        {
            return "";
        }
        else
        {
            return  $SearchByEmployeeAssignLocation; 
        } 
    } 


    public function isSendMaiWithNumber(Request $request,$id)
    {

     $submit = $request['submit'];
        if($submit == 'submit')
        {
 
            $validation = $request->validate([
                'project' => 'required', 
                'flexRadioDefault' => 'required|in:witNumber,withoutNumber,',
            ] );

             
 
             if ($request->flexRadioDefault == "witNumber") {
                $leadDetails =DB::table('leads')->where('id',decrypt($id))->first();
                $buyingLocation =  DB::table('locations')->where('id',$leadDetails->location_of_leads)->first();
                $user = User::first();
                $assineEmploy_email =DB::table('employees')->where('id',$leadDetails->assign_employee_id)->first();
                $projectMain = Project::where('id',$request->project)->first();
                
                $builderCC1 =  explode(',',$request->builder_cc_mail); 
         
                if ($builderCC1 == [""]) {
                    $BuilderCC = null;
                } else {
                    $BuilderCC =  $builderCC1 ;
                } 
                $BuilderCC[] = 
                $BuilderCC[] ='pradeepmishra@homents.in';
                $BuilderCC[] = $assineEmploy_email->official_email;
                $BuilderCC[] =$projectMain->email;
                
                if ($projectMain->email == null) {
                    return redirect()->back()->with('message', "Project Mail Does Not Exists");
                } else {
                     
                $buildername =DB::table('teams')->where('project_id',$projectMain->id)->first();
                if ($buildername == null) {
                    $IsbuilerName = "NA";
                } else {
                   $IsbuilerName = $buildername->team_name;
                }
                
                if ($leadDetails->alt_no_Whatsapp == null) {
                   $alt_no_Whatsapp = "NA";
                } else {
                    $alt_no_Whatsapp = $leadDetails->alt_no_Whatsapp;
                }
        
                if ($leadDetails->alt_no_Whatsapp_2 == null) {
                    $alt_no_Whatsapp_2 = "NA";
                 } else {
                     $alt_no_Whatsapp_2 = $leadDetails->alt_no_Whatsapp_2;
                 }
        
                 if ($leadDetails->alt_contact_name == null) {
                    $alt_contact_name = "NA";
                 } else {
                     $alt_contact_name = $leadDetails->alt_contact_name;
                 }
                 
                 if ($projectMain->email == null) {
                     $projectMailID = null;
                 }else
                 {
                    $projectMailID = $projectMain->email;
                 }
                 
                 $ProjectRegister = [
                    'subject' => 'Customer Registration: ' . $leadDetails->lead_name . ' | ' . $projectMain->project_name . ' | ' . $buyingLocation->location .'', 
                    'body' => ' Dear '.$IsbuilerName.' Team,',
                    // 'cc' => $BuilderCC,
                    'line2'=>'  Customer Name:'.  $leadDetails->lead_name ,
                    'line3'=>' Contact Number:'.$leadDetails->contact_number,
                    'line4'=>'  Alt Contact Number 1: '.$alt_no_Whatsapp,
                    'line5'=>' Alt Contact Name: '. $alt_contact_name,
                    'line6'=>'  Alt Contact Number 2: '. $alt_no_Whatsapp_2,
                    'line7'=>' Customer Requirement:'.$leadDetails->project_type, 
                    'line8'=>' Note:'.$request->email_notes,
                    'line9'=>' C/o: Arti Mala Mishra',
                    'line10'=>'Team Member Name: '.$assineEmploy_email->full_emp_name ?? $assineEmploy_email->employee_name,
                    'line11'=>' Employee Phone Number: ' .$assineEmploy_email->official_phone_number,
                    'line12'=>' Homents Pvt. Ltd.',
                    'thanks' => 'Thank you for choosing Homents.', 

                ]; 
                $leadcc =  [
                    'cc_mail' => $request->builder_cc_mail == null ? $leadDetails->cc_mail :  $request->builder_cc_mail,
                ]; 
                \Mail::to($projectMain->email)
                        ->cc($BuilderCC) 
                        ->send(new ProjectWithNumberWithoutMail($ProjectRegister)); 
    
                     
                 
                    // $details = [
                    //     'subject' => 'Customer Registration: ' . $leadDetails->lead_name . ' | ' . $projectMain->project_name . ' | ' . $buyingLocation->location .'',
                    //     'body' => ' Dear '.$IsbuilerName.' Team,',
                    //      'cc' => $BuilderCC,  
                    //    'line2'=>'  Customer Name:'.  $leadDetails->lead_name ,
                    //    'line3'=>' Contact Number:'.$leadDetails->contact_number,
                    //    'line4'=>'  Alt Contact Number 1: '.$alt_no_Whatsapp,
                    //    'line5'=>' Alt Contact Name: '. $alt_contact_name,
                    //    'line6'=>'  Alt Contact Number 2: '. $alt_no_Whatsapp_2,
                    //    'line7'=>' Customer Requirement:'.$leadDetails->project_type, 
                    //    'line8'=>' Note:'.$request->email_notes,
                    //    'line9'=>' C/o: Arti Mala Mishra',
                    //    'line10'=>'Team Member Name: '.$assineEmploy_email->employee_name,
                    //    'line11'=>' Employee Phone Number: ' .$assineEmploy_email->official_phone_number,
                    //    'line12'=>' Homents Pvt. Ltd.',
                    //    'thanks' => 'Thank you for choosing Homent.', 
                    // ]; 
                    
                    	
                //  $leadcc =  [
                //         'cc_mail' => $request->builder_cc_mail == null ? $leadDetails->cc_mail :  $request->builder_cc_mail,
                //     ]; 
                  //  DB::table('leads')->where('id',decrypt($id))->update($leadcc);
                // Notification::sendNow($projectMain, new BuilderNotification($details));  
                    
                return redirect()->back()->with('success', "Mail Send Successfully");

                }
                
                Notification::sendNow($user, new BuilderNotification($bulder));
             } else {
                $leadDetails =DB::table('leads')->where('id',decrypt($id))->first();
                $buyingLocation =  DB::table('locations')->where('id',$leadDetails->location_of_leads)->first();
                $user = User::first();
                $assineEmploy_email =DB::table('employees')->where('id',$leadDetails->assign_employee_id)->first();
                $projectMain = Project::where('id',$request->project)->first();
                
                
                $builderCC1 =  explode(',',$request->builder_cc_mail); 
         
                if ($builderCC1 == [""]) {
                    $BuilderCC = null;
                } else {
                    $BuilderCC =  $builderCC1 ;
                } 
                $BuilderCC[] = 
                $BuilderCC[] ='pradeepmishra@homents.in';
                $BuilderCC[] = $assineEmploy_email->official_email;
                $BuilderCC[] =$projectMain->email;
                
                
                if ($projectMain->email == null) {
                    return redirect()->back()->with('message', "Project Mail Does Not Exists");
                } else {
                $buildername =DB::table('teams')->where('project_id',$projectMain->id)->first();
                if ($buildername == null) {
                    $IsbuilerName = "NA";
                } else {
                   $IsbuilerName = $buildername->team_name;
                }
                
                if ($leadDetails->alt_no_Whatsapp == null) {
                    $numberHidealt = "NA";
                 } else { 
                     $numberHidealt = substr_replace($leadDetails->alt_no_Whatsapp, '******', 0, 6);
                 }
         
                 if ($leadDetails->alt_no_Whatsapp_2 == null) {
                     $numberHidealt2 = "NA";
                  } else {
                      
                      $numberHidealt2 = substr_replace($leadDetails->alt_no_Whatsapp_2, '******', 0, 6);
                  }
         
                  if ($leadDetails->alt_contact_name == null) {
                     $alt_contact_name = "NA";
                  } else {
                      $alt_contact_name = $leadDetails->alt_contact_name;
                  }
                  
                  if ($projectMain->email == null) {
                     $projectMailID = null;
                 }else
                 {
                    $projectMailID = $projectMain->email;
                 }

                 
        
                $numberHide = substr_replace($leadDetails->contact_number, '******', 0, 6);

                $ProjectRegister = [
                    'subject' => 'Customer Registration: ' . $leadDetails->lead_name . ' | ' . $projectMain->project_name . ' | ' . $buyingLocation->location .'',
                         'body' => ' Dear '.$IsbuilerName.' Team,',
                        //  'cc' => $BuilderCC,
                       // 'cc' => ['pradeepmishra@homents.in',$assineEmploy_email->official_email,$projectMailID],
                       //'line1'=>'Please find customer registration details below:',
                       'line2'=>'  Customer Name:'.  $leadDetails->lead_name , 
                       'line3'=>' Contact Number: '. $numberHide, 
                       'line4'=>'  Alt Contact Number 1:'. $numberHidealt, 
                       'line5'=>' Alt Contact Name: '. $alt_contact_name, 
                       'line6'=>'  Alt Contact Number 2:'.$numberHidealt2, 
                       'line7'=>' Customer Requirement:'.$leadDetails->project_type,  
                       'line8'=>' Note:'.$request->email_notes,
                       'line9'=>' C/o: Arti Mala Mishra', 
                       'line10'=>'Team Member Name:'. $assineEmploy_email->full_emp_name ??$assineEmploy_email->employee_name, 
                       'line11'=>' Employee Phone Number: ' .$assineEmploy_email->official_phone_number, 
                       'line12'=>' Homents Pvt. Ltd.',  
                        'thanks' => 'Thank you for choosing Homents.',

                ]; 
                $leadcc =  [
                    'cc_mail' => $request->builder_cc_mail == null ? $leadDetails->cc_mail :  $request->builder_cc_mail,
                ]; 
                \Mail::to($projectMain->email)
                        ->cc($BuilderCC) 
                        ->send(new ProjectWithNumberWithoutMail($ProjectRegister)); 

                
                 
                //     $details = [
                //         'subject' => 'Customer Registration: ' . $leadDetails->lead_name . ' | ' . $projectMain->project_name . ' | ' . $buyingLocation->location .'',
                //         'body' => ' Dear '.$IsbuilerName.' Team,',
                //          'cc' => $BuilderCC,
                //        // 'cc' => ['pradeepmishra@homents.in',$assineEmploy_email->official_email,$projectMailID],
                //        //'line1'=>'Please find customer registration details below:',
                //        'line2'=>'  Customer Name:'.  $leadDetails->lead_name , 
                //        'line3'=>' Contact Number: '. $numberHide, 
                //        'line4'=>'  Alt Contact Number 1:'. $numberHidealt, 
                //        'line5'=>' Alt Contact Name: '. $alt_contact_name, 
                //        'line6'=>'  Alt Contact Number 2:'.$numberHidealt2, 
                //        'line7'=>' Customer Requirement:'.$leadDetails->project_type,  
                //        'line8'=>' C/o: Arti Mala Mishra', 
                //        'line9'=>'Team Member Name: Lead Assigned To '.$assineEmploy_email->employee_name, 
                //        'line11'=>' Employee Phone Number: ' .$assineEmploy_email->official_phone_number, 
                //        'line11'=>' Homents Pvt. Ltd.',  
                //         'thanks' => 'Thank you for choosing Homent.', 
                //     ]; 
                    
                //      $leadcc =  [
                //         'cc_mail' => $request->builder_cc_mail == null ? $leadDetails->cc_mail :  $request->builder_cc_mail,
                //     ]; 
                //     DB::table('leads')->where('id',decrypt($id))->update($leadcc);
                    
                // Notification::sendNow($projectMain, new BuilderNotification($details));  
        
                return redirect()->back()->with('success', "Mail Send Successfully");
                }
             }
             

        }   

    }
    
    public function LeadExport()
    {
       
        //   Excel::download(new LeadExportReports(),'Leads-Reports.xlsx');

        //   return "downloading Running";
       // return "downloading Running";
           // Generate the file and get its path
        $filePath = storage_path('app/Leads-Reports.xlsx');
        Excel::store(new LeadExportReports(), 'Leads-Reports.xlsx');

        
    }
    
    public function LeadInfoHistory($id)
    { 
        $leadInfoHistorys = DB::table('lead_info_history')
        ->where('lead_id',decrypt($id))->get(); 
        $leadsName = DB::table('leads')->where('id',decrypt($id))->first(); 
        return view('pages.leads.lead-info-history',compact(['leadInfoHistorys','leadsName']));
    }

    public function filterLeads(Request $request)
    {
        
        
       
          /// Leads Fi
        if ($request->filter_type ==1 ) {
           
           
            if (Auth::user()->roles_id ==1) {

                   $isfeatured =1;
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                        ->join('employees','employees.id','leads.assign_employee_id') 
                        ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                        ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number','employees.emp_country_code')
                        ->where('leads.is_featured', $isfeatured)
                        ->where('leads.common_pool_status',0)
                        ->where('leads.lead_status','!=',14)
                        ->latest()->get();  

                        $leads12=array();
                        foreach($leads as $lead){
                            $customerType = DB::table('leads')
                                    ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                    ->select('leads.*', 'buyer_sellers.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();

                                   
                                    

                                    $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                                    $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                                    $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                                     $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                    $lead->buyer_seller= $customerType->name;
                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                    ->where('id', $lead->lead_type_bifurcation_id)
                                                    ->first();
                                $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                                $lead->preference=$lead->lead_status;
                                $country_code= explode('+',trim($lead->country_code));
                               
                                $lead->country_code= $country_code[1];
                                
                                if($lead->budget ==null){
                                    $lead->budget ='N/A';
                                }else{
                                    $lead->budget = $lead->budget;
                                }
                                $leadStatusName = DB::table('leads')
                                        ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                        ->select('leads.*', 'lead_statuses.name')
                                        ->where('leads.id', $lead->id)
                                        ->first();
                                        $lead->lead_status= $leadStatusName->name;
                                $LeadCount = DB::table('lead_status_histories')
                                ->where('lead_id', $lead->id)
                                ->count();
                                $lead->location_of_client=$lead->id;

                                

                                $lead->mode_of_lead= $LeadCount;   
                                $lead->id=encrypt($lead->id);  
                                
                                
                          $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 
                               $leads12[] =$lead;

                        }
               
                        
                       
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            }
             else
              {
                 
                   $isfeatured =1;
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.is_featured', $isfeatured)
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    ->where('leads.assign_employee_id',$empId->id)
                    ->latest()->get();  
                  $leads12=array();
                 foreach($leads as $lead){
                    $customerType = DB::table('leads')
                            ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                            ->select('leads.*', 'buyer_sellers.name')
                            ->where('leads.id', $lead->id)
                            ->first();
                            

                            $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                            $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                            $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                             $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                            $lead->buyer_seller= $customerType->name;
                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                            ->where('id', $lead->lead_type_bifurcation_id)
                                            ->first();
                        $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                        $lead->preference=$lead->lead_status;
                        $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                        $country_code= explode('+',trim($lead->country_code));
                         $lead->country_code= $country_code[1];
                        if($lead->budget ==null){
                            $lead->budget ='N/A';
                        }else{
                            $lead->budget = $lead->budget;
                        }
                        $leadStatusName = DB::table('leads')
                                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                ->select('leads.*', 'lead_statuses.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                $lead->lead_status= $leadStatusName->name;
                        $LeadCount = DB::table('lead_status_histories')
                        ->where('lead_id', $lead->id)
                        ->count();
                        $lead->location_of_client=$lead->id;

                        $lead->mode_of_lead= $LeadCount;      
                        $lead->id=encrypt($lead->id); 
                       $leads12[] =$lead;

                 }
               
                  
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            }
            
            
        } 


        //leads filters for leads type 
        elseif ($request->filter_type ==2) {

             if (Auth::user()->roles_id ==1) {
                
                    if ($request->filter_value =='Hot') {
                        $lead_type  =1;
                    }
                    elseif ($request->filter_value =='Cold') {
                        $lead_type  =2;
                    }
                    elseif ($request->filter_value =='WIP') {
                        $lead_type  =3;
                    }
                   
                   
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.lead_type_bifurcation_id', $lead_type)
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    ->latest()->get();  
                    //return  $lead_type;
                    $leads12=array();
                    foreach($leads as $lead){
                        $customerType = DB::table('leads')
                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                ->select('leads.*', 'buyer_sellers.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                

                                $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                                $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                                $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                                 $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                $lead->buyer_seller= $customerType->name;
                                $lead_type_bif = DB::table('lead_type_bifurcation')
                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                ->first();
                            $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                            $lead->preference=$lead->lead_status;
                            // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                            $country_code= explode('+',trim($lead->country_code));
                            $lead->country_code= $country_code[1];
                            if($lead->budget ==null){
                                $lead->budget ='N/A';
                            }else{
                                $lead->budget = $lead->budget;
                            }
                            $leadStatusName = DB::table('leads')
                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                    ->select('leads.*', 'lead_statuses.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    $lead->lead_status= $leadStatusName->name;
                            $LeadCount = DB::table('lead_status_histories')
                            ->where('lead_id', $lead->id)
                            ->count();
                            $lead->location_of_client=$lead->id;

                            $lead->mode_of_lead= $LeadCount;      
                            $lead->id=encrypt($lead->id); 

                                  
                          $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 
                               $leads12[] =$lead;
                                

                    }
                    
                     
                    
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            } else {
                
                   
                   if ($request->filter_value =='Hot') {
                    $lead_type  =1;
                    }
                    elseif ($request->filter_value =='Cold') {
                        $lead_type  =2;
                    }
                    elseif ($request->filter_value =='WIP') {
                        $lead_type  =3;
                    }
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                   $leads = DB::table('leads')
                        ->join('employees','employees.id','leads.assign_employee_id') 
                        ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                        ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                        ->where('leads.lead_type_bifurcation_id', $lead_type)
                        ->where('leads.assign_employee_id',$empId->id)
                        ->where('leads.common_pool_status',0)
                        ->where('leads.lead_status','!=',14)
                        
                        ->latest()->get();  
                        $leads12=array();
                        foreach($leads as $lead){
                            $customerType = DB::table('leads')
                                    ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                    ->select('leads.*', 'buyer_sellers.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    

                                    $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                                    $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                                    $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                                     $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                    $lead->buyer_seller= $customerType->name;
                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                    ->where('id', $lead->lead_type_bifurcation_id)
                                                    ->first();
                                $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                                $lead->preference=$lead->lead_status;
                                $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                                $country_code= explode('+',trim($lead->country_code));
                                $lead->country_code= $country_code[1];
                                if($lead->budget ==null){
                                    $lead->budget ='N/A';
                                }else{
                                    $lead->budget = $lead->budget;
                                }
                                $leadStatusName = DB::table('leads')
                                        ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                        ->select('leads.*', 'lead_statuses.name')
                                        ->where('leads.id', $lead->id)
                                        ->first();
                                        $lead->lead_status= $leadStatusName->name;
                                $LeadCount = DB::table('lead_status_histories')
                                ->where('lead_id', $lead->id)
                                ->count();
                                $lead->location_of_client=$lead->id;

                                $lead->mode_of_lead= $LeadCount;      
                                $lead->id=encrypt($lead->id); 

                                $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 

                               $leads12[] =$lead;

                        }
                
               
                       
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            }
        }

        ///Leads Filters Status
        elseif ($request->filter_type ==3) {
            if (Auth::user()->roles_id ==1) {
                
                    $lead_status  =$request->filter_value;
                    //return $lead_status;
                   
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.lead_status', $lead_status)
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    ->latest()->get();  
                    $leads12=array();
                    foreach($leads as $lead){
                        $customerType = DB::table('leads')
                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                ->select('leads.*', 'buyer_sellers.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                

                                $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                                $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                                $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                                 $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                $lead->buyer_seller= $customerType->name;
                                $lead_type_bif = DB::table('lead_type_bifurcation')
                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                ->first();
                            $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                            $lead->preference=$lead->lead_status;
                            // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                            $country_code= explode('+',trim($lead->country_code));
                            $lead->country_code= $country_code[1];
                            if($lead->budget ==null){
                                $lead->budget ='N/A';
                            }else{
                                $lead->budget = $lead->budget;
                            }
                            $leadStatusName = DB::table('leads')
                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                    ->select('leads.*', 'lead_statuses.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    $lead->lead_status= $leadStatusName->name;
                            $LeadCount = DB::table('lead_status_histories')
                            ->where('lead_id', $lead->id)
                            ->count();
                            $lead->location_of_client=$lead->id;

                            $lead->mode_of_lead= $LeadCount;      
                            $lead->id=encrypt($lead->id); 

                            $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 

                           $leads12[] =$lead;

                    }
               
               
                    
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            } else {
                    $lead_status  =$request->filter_value;
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                 $leads = DB::table('leads')
                        ->join('employees','employees.id','leads.assign_employee_id') 
                        ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                        ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                        ->where('leads.assign_employee_id',$empId->id)
                        ->where('leads.lead_status', $lead_status)
                        ->where('leads.common_pool_status',0)
                        ->where('leads.lead_status','!=',14)
                        
                        ->latest()->get();  
                        $leads12=array();
                        foreach($leads as $lead){
                            $customerType = DB::table('leads')
                                    ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                    ->select('leads.*', 'buyer_sellers.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    

                                    $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                                    $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                                    $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                                     $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                    $lead->buyer_seller= $customerType->name;
                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                    ->where('id', $lead->lead_type_bifurcation_id)
                                                    ->first();
                                $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                                $lead->preference=$lead->lead_status;
                                $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                                $country_code= explode('+',trim($lead->country_code));
                                $lead->country_code= $country_code[1];
                                if($lead->budget ==null){
                                    $lead->budget ='N/A';
                                }else{
                                    $lead->budget = $lead->budget;
                                }
                                $leadStatusName = DB::table('leads')
                                        ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                        ->select('leads.*', 'lead_statuses.name')
                                        ->where('leads.id', $lead->id)
                                        ->first();
                                        $lead->lead_status= $leadStatusName->name;
                                $LeadCount = DB::table('lead_status_histories')
                                ->where('lead_id', $lead->id)
                                ->count();
                                $lead->location_of_client=$lead->id;

                                $lead->mode_of_lead= $LeadCount;      
                                $lead->id=encrypt($lead->id); 

                                $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 


                               $leads12[] =$lead;

                        }
                
                       
                $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                return response()->json($resopnse);
                
                
            }
        }

        ///Today's Date follow up  filter in leads
        elseif ($request->filter_type ==4) {
            if (Auth::user()->roles_id ==1) {
               
                    $lead_todays_next_followDate  =$request->filter_value;
                    //return $lead_todays_next_followDate;
                   
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.next_follow_up_date',  'LIKE', '%'.$lead_todays_next_followDate.'%')
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    ->latest()->get();  
                    $leads12=array();
                foreach($leads as $lead){
                    $customerType = DB::table('leads')
                            ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                            ->select('leads.*', 'buyer_sellers.name')
                            ->where('leads.id', $lead->id)
                            ->first();
                            

                            $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                            $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                            $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                             $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                            $lead->buyer_seller= $customerType->name;
                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                            ->where('id', $lead->lead_type_bifurcation_id)
                                            ->first();
                        $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                        $lead->preference=$lead->lead_status;
                        $country_code= explode('+',trim($lead->country_code));
                        $lead->country_code= $country_code[1];
                        // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                        if($lead->budget ==null){
                            $lead->budget ='N/A';
                        }else{
                            $lead->budget = $lead->budget;
                        }
                        $leadStatusName = DB::table('leads')
                                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                ->select('leads.*', 'lead_statuses.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                $lead->lead_status= $leadStatusName->name;
                        $LeadCount = DB::table('lead_status_histories')
                        ->where('lead_id', $lead->id)
                        ->count();
                        $lead->location_of_client=$lead->id;

                        $lead->mode_of_lead= $LeadCount;      
                        $lead->id=encrypt($lead->id); 

                        $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 

                       $leads12[] =$lead;

                }
               
                 
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            } else {
               
                    $lead_todays_next_followDate  =$request->filter_value;
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                 $leads = DB::table('leads')
                        ->join('employees','employees.id','leads.assign_employee_id') 
                        ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                        ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                        ->where('leads.next_follow_up_date',  'LIKE', '%'.$lead_todays_next_followDate.'%')
                        ->where('leads.common_pool_status',0)
                        ->where('leads.lead_status','!=',14)
                        ->where('leads.assign_employee_id',$empId->id)
                        ->latest()->get();  
                        $leads12=array();
                foreach($leads as $lead){
                    $customerType = DB::table('leads')
                            ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                            ->select('leads.*', 'buyer_sellers.name')
                            ->where('leads.id', $lead->id)
                            ->first();
                            

                            $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                            $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                            $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                             $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                            $lead->buyer_seller= $customerType->name;
                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                            ->where('id', $lead->lead_type_bifurcation_id)
                                            ->first();
                        $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                        $lead->preference=$lead->lead_status;
                        $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                        $country_code= explode('+',trim($lead->country_code));
                        $lead->country_code= $country_code[1];
                        if($lead->budget ==null){
                            $lead->budget ='N/A';
                        }else{
                            $lead->budget = $lead->budget;
                        }
                        $leadStatusName = DB::table('leads')
                                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                ->select('leads.*', 'lead_statuses.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                $lead->lead_status= $leadStatusName->name;
                        $LeadCount = DB::table('lead_status_histories')
                        ->where('lead_id', $lead->id)
                        ->count();
                        $lead->location_of_client=$lead->id;

                        $lead->mode_of_lead= $LeadCount;      
                        $lead->id=encrypt($lead->id); 

                        $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 


                       $leads12[] =$lead;

                }
                
                
                $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                return response()->json($resopnse);
                
                
            }
        }

        ///Prives Date Next Follow Date filter

        elseif ($request->filter_type ==5) {
              if (Auth::user()->roles_id ==1) {
                
                    $lead_previses_next_followDate  =$request->filter_value;
                    //return $lead_todays_next_followDate;
                   
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                  
                    $leads = DB::table('leads')
                
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.next_follow_up_date',  'LIKE', '%'.$lead_previses_next_followDate.'%')
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    ->latest()->get();
                    $leads12=array();
                    foreach($leads as $lead){
                        $customerType = DB::table('leads')
                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                ->select('leads.*', 'buyer_sellers.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                

                                $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                                $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                                $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                                 $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                $lead->buyer_seller= $customerType->name;
                                $lead_type_bif = DB::table('lead_type_bifurcation')
                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                ->first();
                            $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                            $lead->preference=$lead->lead_status;
                            $country_code= explode('+',trim($lead->country_code));
                            $lead->country_code= $country_code[1];
                            // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                            if($lead->budget ==null){
                                $lead->budget ='N/A';
                            }else{
                                $lead->budget = $lead->budget;
                            }
                            $leadStatusName = DB::table('leads')
                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                    ->select('leads.*', 'lead_statuses.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    $lead->lead_status= $leadStatusName->name;
                            $LeadCount = DB::table('lead_status_histories')
                            ->where('lead_id', $lead->id)
                            ->count();
                            $lead->location_of_client=$lead->id;

                            $lead->mode_of_lead= $LeadCount;      
                            $lead->id=encrypt($lead->id); 
                            $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 


                           $leads12[] =$lead;

                    }  
               
                     
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            } else {
               
                    $lead_previses_next_followDate  =$request->filter_value;
                   $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                 $leads = DB::table('leads')
                        ->join('employees','employees.id','leads.assign_employee_id') 
                        ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                        ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                        ->where('leads.next_follow_up_date',  'LIKE', '%'.$lead_previses_next_followDate.'%')
                        ->where('leads.common_pool_status',0)
                        ->where('leads.lead_status','!=',14)
                        ->where('leads.assign_employee_id',$empId->id)
                        ->latest()->get();  
                        $leads12=array();
                        foreach($leads as $lead){
                            $customerType = DB::table('leads')
                                    ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                    ->select('leads.*', 'buyer_sellers.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    

                                    $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                                    $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                                    $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                                     $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                    $lead->buyer_seller= $customerType->name;
                                    $lead_type_bif = DB::table('lead_type_bifurcation')
                                                    ->where('id', $lead->lead_type_bifurcation_id)
                                                    ->first();
                                $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                                $lead->preference=$lead->lead_status;
                                $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                                $country_code= explode('+',trim($lead->country_code));
                                $lead->country_code= $country_code[1];
                                // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                                if($lead->budget ==null){
                                    $lead->budget ='N/A';
                                }else{
                                    $lead->budget = $lead->budget;
                                }
                                $lead->preference=$lead->lead_status;
                                $leadStatusName = DB::table('leads')
                                        ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                        ->select('leads.*', 'lead_statuses.name')
                                        ->where('leads.id', $lead->id)
                                        ->first();
                                        $lead->lead_status= $leadStatusName->name;
                                $LeadCount = DB::table('lead_status_histories')
                                ->where('lead_id', $lead->id)
                                ->count();
                                $lead->location_of_client=$lead->id;

                                $lead->mode_of_lead= $LeadCount; 
                                $lead->wedding_anniversary= Auth::user()->roles_id;   
                                
                                $lead->id=encrypt($lead->id); 

                                $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 

                               $leads12[] =$lead;

                        }

                         
                    $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                    return response()->json($resopnse);
                
                
            }
        }

         /// Admin Assigned Leads
        elseif ($request->filter_type ==6) {
           // return 'lkj';
            if (Auth::user()->roles_id ==1) {
              
                  $lead_previses_next_followDate  =$request->filter_value;
                  //return $lead_todays_next_followDate;
                 
                 $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
                
                  $leads = DB::table('leads')
              
                  ->join('employees','employees.id','leads.assign_employee_id') 
                  ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                  ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                  ->where('leads.assign_employee_id',$empId->id)
                  ->where('leads.common_pool_status',0)
                  ->where('leads.lead_status','!=',14)
                  ->latest()->get();
                
                  $leads12=array();
                  foreach($leads as $lead){
                      $customerType = DB::table('leads')
                              ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                              ->select('leads.*', 'buyer_sellers.name')
                              ->where('leads.id', $lead->id)
                              ->first();
                              

                              $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-M-Y H:i');
                              $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-M-Y');
                              $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-M-Y H:i');
                               $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                              $lead->buyer_seller= $customerType->name;
                              $lead_type_bif = DB::table('lead_type_bifurcation')
                                              ->where('id', $lead->lead_type_bifurcation_id)
                                              ->first();
                          $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                          $lead->preference=$lead->lead_status;
                          $country_code= explode('+',trim($lead->country_code));
                          $lead->country_code= $country_code[1];
                          // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                          if($lead->budget ==null){
                              $lead->budget ='N/A';
                          }else{
                              $lead->budget = $lead->budget;
                          }
                          $leadStatusName = DB::table('leads')
                                  ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                  ->select('leads.*', 'lead_statuses.name')
                                  ->where('leads.id', $lead->id)
                                  ->first();
                                  $lead->lead_status= $leadStatusName->name;
                          $LeadCount = DB::table('lead_status_histories')
                          ->where('lead_id', $lead->id)
                          ->count();

                          $implodedResult = DB::table('projects')
                          ->where('id', $lead->existing_property)
                          ->pluck('project_name')->implode(',');

                          $lead->location_of_client=$lead->id;
                          $lead->existing_property = $implodedResult;

                          $lead->mode_of_lead= $LeadCount;      
                          $lead->id=encrypt($lead->id); 

                          $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days;

                         $leads12[] =$lead;

                        


                        
 
 

                  }  
             
                   
                  $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                  return response()->json($resopnse);
              
              
          } 
      }


       /// Leads Live search
       elseif ($request->filter_type ==7) {
        //return 'ppp';
         if (Auth::user()->roles_id ==1) {
           
               $lead_previses_next_followDate  =$request->filter_value;
               //return $lead_todays_next_followDate;
              
              $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
             
               $leads = DB::table('leads')
           
               ->join('employees','employees.id','leads.assign_employee_id') 
               ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
               ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
               ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
           
               ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','lead_statuses.name','buyer_sellers.name as buyer_seller','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
               ->orWhere('leads.lead_name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.next_follow_up_date',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('lead_statuses.name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.date',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('employees.employee_name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.contact_number',  'LIKE', '%'.$request->filter_value.'%')
               ->Where('leads.common_pool_status',0)
               ->Where('leads.lead_status','!=',14)
               ->latest()->get();
             
               $leads12=array();
               foreach($leads as $lead){
                   $customerType = DB::table('leads')
                           ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                           ->select('leads.*', 'buyer_sellers.name')
                           ->where('leads.id', $lead->id)
                           ->first();
                           

                           $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                           $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                           $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                            $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                           $lead->buyer_seller= $customerType->name;
                           $lead_type_bif = DB::table('lead_type_bifurcation')
                                           ->where('id', $lead->lead_type_bifurcation_id)
                                           ->first();
                       $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                       $lead->preference=$lead->lead_status;
                       $country_code= explode('+',trim($lead->country_code));
                       $lead->country_code= $country_code[1];
                       // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                       if($lead->budget ==null){
                           $lead->budget ='N/A';
                       }else{
                           $lead->budget = $lead->budget;
                       }
                       $leadStatusName = DB::table('leads')
                               ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                               ->select('leads.*', 'lead_statuses.name')
                               ->where('leads.id', $lead->id)
                               ->first();
                               $lead->lead_status= $leadStatusName->name;
                       $LeadCount = DB::table('lead_status_histories')
                       ->where('lead_id', $lead->id)
                       ->count();
                       $lead->location_of_client=$lead->id;

                       $lead->mode_of_lead= $LeadCount;      
                       $lead->id=encrypt($lead->id); 

                        // $nursery= $lead->orWhere('lead_name',  'LIKE', '%' . $request->filter_value . '%');

                        $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 
                      
                        $leads12[] =$lead;
                       
                       
               }  
                $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                return response()->json($resopnse);
           }
        else{
                $lead_previses_next_followDate  =$request->filter_value;
                //return $lead_todays_next_followDate;
            
            $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
           // return $empId->id;
                $leads = DB::table('leads')
            
                ->join('employees','employees.id','leads.assign_employee_id') 
                ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
            
                ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','lead_statuses.name','buyer_sellers.name as buyer_seller','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                ->where('leads.common_pool_status',0)
                ->where('leads.assign_employee_id',$empId->id)
                ->where('leads.lead_status','!=',14)
                ->orWhere('leads.lead_name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.next_follow_up_date',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('lead_statuses.name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.date',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('employees.employee_name',  'LIKE', '%'.$request->filter_value.'%')
                ->orWhere('leads.contact_number',  'LIKE', '%'.$request->filter_value.'%')
                
                ->latest()->get();
            
                $leads12=array();
                foreach($leads as $lead){
                    $customerType = DB::table('leads')
                            ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                            ->select('leads.*', 'buyer_sellers.name')
                            ->where('leads.id', $lead->id)
                            ->first();
                            

                            $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                            $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                            $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                            $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                            $lead->buyer_seller= $customerType->name;
                            $lead_type_bif = DB::table('lead_type_bifurcation')
                                            ->where('id', $lead->lead_type_bifurcation_id)
                                            ->first();
                        $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                        $lead->preference=$lead->lead_status;
                        $country_code= explode('+',trim($lead->country_code));
                        $lead->country_code= $country_code[1];
                        $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                        if($lead->budget ==null){
                            $lead->budget ='N/A';
                        }else{
                            $lead->budget = $lead->budget;
                        }
                        $leadStatusName = DB::table('leads')
                                ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                ->select('leads.*', 'lead_statuses.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                $lead->lead_status= $leadStatusName->name;
                        $LeadCount = DB::table('lead_status_histories')
                        ->where('lead_id', $lead->id)
                        ->count();
                        $lead->location_of_client=$lead->id;

                        $lead->mode_of_lead= $LeadCount;      
                        $lead->id=encrypt($lead->id); 

                        // $nursery= $lead->orWhere('lead_name',  'LIKE', '%' . $request->filter_value . '%');

                        $leadID = DB::table('leads')->where('id',decrypt($lead->id))
                          ->select('leads.id as leadID')->first();

                          $lead->price_quoted = $leadID->leadID;


                          $existingProperty = $lead->existing_property;
                          $existingProjects = array_filter(explode(',', $existingProperty), 'strlen');
                          $existingPropertyCount = count($existingProjects);

                          
                          $existingPropertyIds = explode(',', $lead->existing_property);

                          $projects = Project::whereIn('id', $existingPropertyIds)
                            ->select('project_name', 'id')
                            ->get();

                            $projectName = $projects->pluck('project_name')->toArray();

                            $projectNames = implode(',', $projectName);

                           
                          $lead->follow_up_status = $projectNames;
                          $lead->projects_visited_names = $existingPropertyCount;


                         
                          $currentDateTime = \Carbon\Carbon::now();
                          $givenDateTimeString = $lead->next_follow_up_date;
                          
                          // Make sure $givenDateTimeString is a valid date and time string
                          // You may want to validate it or handle potential formatting issues
                          // For example, assuming it's in a format like 'Y-m-d H:i:s'
                          $givenDateTime = \Carbon\Carbon::parse($givenDateTimeString);

                          $givenDateTimeNewLead = \Carbon\Carbon::parse($lead->created_at);
                          
                          // Calculate the time difference
                          $timeDifference = $currentDateTime->diff($givenDateTime);
                          $timeDifferenceNew = $currentDateTime->diff($givenDateTimeNewLead);
                        
                          
                            
                          
                        
                        $lead->personal_details_if_any =  $givenDateTime;

                        $lead->office_site_visit_date = $currentDateTime;

                        $lead->relationship_name = $timeDifferenceNew->days; 
                    
                        $leads12[] =$lead;
                        
                        
                    }  
                        $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                        return response()->json($resopnse);
            }
        }
        
       
        
    }
  

    ///Start Date to End Date Filter
    public function filterLeadsDates(Request $request)
    {
       //return $request;
      
        if (Auth::user()->roles_id ==1) {
             $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            
              $leads = DB::table('leads')
          
              ->join('employees','employees.id','leads.assign_employee_id') 
              ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
              ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
              ->whereBetween('leads.next_follow_up_date', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59'])
              ->where('leads.common_pool_status',0)
              ->where('leads.lead_status','!=',14)
              ->latest()->get();
              //return $leads;
              $leads12=array();
              foreach($leads as $lead){
                  $customerType = DB::table('leads')
                          ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                          ->select('leads.*', 'buyer_sellers.name')
                          ->where('leads.id', $lead->id)
                          ->first();
                          

                          $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                          $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                          $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                           $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                          $lead->buyer_seller= $customerType->name;
                          $lead_type_bif = DB::table('lead_type_bifurcation')
                                          ->where('id', $lead->lead_type_bifurcation_id)
                                          ->first();
                      $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                      $lead->preference=$lead->lead_status;
                      $country_code= explode('+',trim($lead->country_code));
                      $lead->country_code= $country_code[1];
                      // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                      if($lead->budget ==null){
                          $lead->budget ='N/A';
                      }else{
                          $lead->budget = $lead->budget;
                      }
                      $leadStatusName = DB::table('leads')
                              ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                              ->select('leads.*', 'lead_statuses.name')
                              ->where('leads.id', $lead->id)
                              ->first();
                              $lead->lead_status= $leadStatusName->name;
                      $LeadCount = DB::table('lead_status_histories')
                      ->where('lead_id', $lead->id)
                      ->count();
                      $lead->location_of_client=$lead->id;

                      $lead->mode_of_lead= $LeadCount;      
                      $lead->id=encrypt($lead->id); 
                     $leads12[] =$lead;

              }  
         
         
              $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
              return response()->json($resopnse);
          
          
        } else {
            
                $lead_previses_next_followDate  =$request->filter_value;
                $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            $leads = DB::table('leads')
                    ->join('employees','employees.id','leads.assign_employee_id') 
                    ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
                    ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation','employees.official_phone_number')
                    ->where('leads.assign_employee_id',$empId->id)
                    // ->select('leads.*','employees.employee_name','lead_type_bifurcation.lead_type_bifurcation')
                    ->whereBetween('leads.next_follow_up_date', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59'])
                    ->where('leads.common_pool_status',0)
                    ->where('leads.lead_status','!=',14)
                    
                    ->latest()->get();  
                    //return $leads;
                    $leads12=array();
                    foreach($leads as $lead){
                        $customerType = DB::table('leads')
                                ->join('buyer_sellers', 'buyer_sellers.id', '=', 'leads.buyer_seller')
                                ->select('leads.*', 'buyer_sellers.name')
                                ->where('leads.id', $lead->id)
                                ->first();
                                

                                $lead->date=   \Carbon\Carbon::parse($lead->date)->format('d-m-Y H:i');
                                $lead->next_follow_up_date=   \Carbon\Carbon::parse($lead->next_follow_up_date)->format('d-m-Y');
                                $lead->last_contacted=   \Carbon\Carbon::parse($lead->last_contacted)->format('d-m-Y H:i');
                                $lead->office_site_visit_date=\Carbon\Carbon::parse($lead->next_follow_up_date)->format('H:i');
                                $lead->buyer_seller= $customerType->name;
                                $lead_type_bif = DB::table('lead_type_bifurcation')
                                                ->where('id', $lead->lead_type_bifurcation_id)
                                                ->first();
                            $lead->lead_type_bifurcation_id= $lead_type_bif->lead_type_bifurcation;
                            $lead->preference=$lead->lead_status;
                            $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                            $country_code= explode('+',trim($lead->country_code));
                            $lead->country_code= $country_code[1];
                            // $lead->contact_number =substr_replace($lead->contact_number, '******', 0, 6);
                            if($lead->budget ==null){
                                $lead->budget ='N/A';
                            }else{
                                $lead->budget = $lead->budget;
                            }
                            $lead->preference=$lead->lead_status;
                            $leadStatusName = DB::table('leads')
                                    ->join('lead_statuses', 'leads.lead_status', '=', 'lead_statuses.id')
                                    ->select('leads.*', 'lead_statuses.name')
                                    ->where('leads.id', $lead->id)
                                    ->first();
                                    $lead->lead_status= $leadStatusName->name;
                            $LeadCount = DB::table('lead_status_histories')
                            ->where('lead_id', $lead->id)
                            ->count();
                            $lead->location_of_client=$lead->id;

                            $lead->mode_of_lead= $LeadCount; 
                            $lead->wedding_anniversary= Auth::user()->roles_id;   
                            
                            $lead->id=encrypt($lead->id); 

                            $leads12[] =$lead;

                    }
                $resopnse =array("status"=>"1","message"=>"data get succesfully","data"=>$leads12);
                return response()->json($resopnse);
            
            
        }
    }


	 public function globalSearchDetails()
	    {

            
            $GlobalSearchs = DB::table('global_search')
            ->orderBy('created_at', 'desc')
            ->paginate(200);
		return view('pages.admin.global-search',compact(['GlobalSearchs']));
	    }
	    
	    public function EmployeeReportsDownload()
        {
            return Excel::download(new EmployeeLeadExport, 'EmployeeReports.xlsx');
        }
        
        
         public function FreeSearch(Request $request)
	    {


             if(Auth::user()->roles_id == 1)
            {
                $searchTerm = $request->input('free_search'); 
                $searchResults = DB::table('lead_status_histories')
                ->join('leads', 'lead_status_histories.lead_id', '=', 'leads.id')
                ->leftJoin('employees', 'lead_status_histories.created_by', '=', 'employees.user_id') 
                ->select('lead_status_histories.*', 'leads.*','employees.employee_name','employees.official_phone_number','employees.emp_country_code') 
                ->orWhere('lead_status_histories.customer_interaction', 'LIKE', "%$searchTerm%") 
                ->orWhere('leads.budget', 'LIKE', "%$searchTerm%") 
                ->paginate(100);
            }else 
            {
                $searchTerm = $request->input('free_search'); 
                $empId = DB::table('employees')->where('user_id', Auth::user()->id)->first();
                $searchResults = DB::table('lead_status_histories')
                    ->join('leads', 'lead_status_histories.lead_id', '=', 'leads.id')
                    ->leftJoin('employees', 'lead_status_histories.created_by', '=', 'employees.user_id') 
                    ->select('lead_status_histories.*', 'leads.*', 'employees.employee_name','employees.official_phone_number','employees.emp_country_code')  
                    ->where(function ($query) use ($searchTerm, $empId) {
                        $query->where('lead_status_histories.customer_interaction', 'LIKE', "%$searchTerm%")
                              ->where('lead_status_histories.created_by', $empId->user_id);
                    }) 
                    ->paginate(100);

            }


            if (!$searchResults->isEmpty()) {
                return view('pages.leads.free-search',compact(['searchResults']));
             } else {
                return redirect()->back()->with('message', 'No records found');
             }

               
	    }
	    
	    
	    public function LeadReopne(Request $request,$id)
        {
            $reopen = $request['reopen']; 
            
             
            $IsReopenLead = DB::table('leads')->where('id',decrypt($id))->first();
             
            if($reopen == 'reopen')
            {

                $currentDateTime = Carbon::now(); 
                $twoHoursLater = $currentDateTime->addHours(2);
                
                $IsReopen = array();
                $IsReopen['lead_status'] = 5;
                $IsReopen['assign_employee_id'] = $IsReopenLead->assign_employee_id;
                $IsReopen['location_of_leads'] = $IsReopenLead->location_of_leads; 
                $IsReopen['next_follow_up_date'] = $twoHoursLater;
                $IsReopen['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $IsReopen['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');  
               
                 DB::table('leads')->where('id',decrypt($id))->update($IsReopen);
                 
                 $lead_history['lead_id'] = $IsReopenLead->id;
                 $lead_history['date'] = Carbon::now();
                 $lead_history['status_id'] = 5;  
                 $lead_history['customer_interaction'] = "Reopen Leads"; 
                 $lead_history['about_customer'] = $IsReopenLead->about_customer; 
                 $lead_history['next_follow_up_date'] = $twoHoursLater;  
                 $lead_history['created_by'] = Auth::user()->id;
                 $lead_history['created_at'] = Carbon::now(); 
                
                 $userData = DB::table('lead_status_histories')->insert($lead_history);
                 
                 return redirect()->back()->with('success','Lead Reopend Successfully');
            }
        }
        
        
        public function LeadDocumentsUploade(Request $request)
        {
         
            $validatedData = $request->validate([
                'file_uploads.*' => 'required|max:2048',
            ]);

            // dd($validatedData);
            if ($validatedData == []) {
                return redirect()->back()->with('message', 'Please Select File Upload');
            } else {
                # code...
            
            $leadId = $request->input('leadId');  
            $documents = $request->input('documents'); 
  
            $files = [];
            if($request->hasfile('file_uploads'))
             {
                foreach($request->file('file_uploads') as $file)
                {  
                    
                    $name = $file->getClientOriginalName();
                    $path = $file->move(public_path().'/files', $name);   
                    $files = $path;
 
                    DB::table('lead_gallerys')->insert([
                        'lead_id' => $leadId,
                        // 'emp_id' => $empId,
                        'documents' => $documents,
                        'images' =>  $files,
                        'uploaded_by' => Auth::user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                      
                }
             }
             

            return redirect()->back()->with('success', 'Lead document and images uploaded successfully.');
            }
        }
        
        public function GallaryView($id)
        {
             
            $images = DB::table('lead_gallerys')
            ->where('lead_id',decrypt($id))->get();
            // dd(decrypt($id));
            $LeadInfo = DB::table('leads')
            ->join('lead_gallerys','lead_gallerys.lead_id','leads.id')
            ->where('lead_id',decrypt($id))
             ->select('leads.lead_name','leads.id')
            ->first(); 

            $leadID = DB::table('leads')->where('id',decrypt($id))->first();
             
            if ($LeadInfo == null) { 
                return redirect()->to('lead-status/'. encrypt($leadID->id));
            } 
                return view('pages.leads.gallary',compact(['images','LeadInfo']));
             
        }

        public function DeleteDocs(Request $request,$id)
        {
            $deleteDocs = DB::table('lead_gallerys')->where('id',decrypt($id))->delete();

            return redirect()->back()->with('danger','Document Deleted');
        }
   
        
        public function leadSearch(Request $request)
        {
            $budget = $request->input('budget');
           
            $investor = $request->input('investor');

            $LeadStatus = DB::table('lead_statuses')->get(); 
            $leads = DB::table('leads')
            ->where('common_pool_status',0)
            ->whereNotIn('lead_status', [14, 16, 8, 9, 10, 11, 12])
            ->join('employees','employees.id','leads.assign_employee_id') 
            ->join('lead_type_bifurcation','lead_type_bifurcation.id','leads.lead_type_bifurcation_id') 
            ->select('leads.*','employees.employee_name','employees.official_phone_number'
            ,'employees.emp_country_code','lead_type_bifurcation.lead_type_bifurcation')
            ->where('common_pool_status', 0)
            ->when(!is_null($request->input('budget')), function ($query) use ($request) {
                return $query->where('leads.budget', $request->input('budget'));
            })
            ->when(!is_null($request->input('investor')), function ($query) use ($request) {
                return $query->where('leads.regular_investor', $request->input('investor'));
            }) 
            ->latest('leads.updated_at')
            ->get();

            $empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            $employees = Employee::all();
            $projectTypes = DB::table('project_types')->get(); 

 
            if($leads->isEmpty()) {  
                return redirect()->back()->with('message', 'Data Not Found');
            }
            else
            {
                return view('pages.leads.index',compact(['leads','LeadStatus','employees','projectTypes','empIdStatus']));
                
            } 
            
        }
     
}
