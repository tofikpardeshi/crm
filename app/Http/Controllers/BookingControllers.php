<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Notifications\IsLeadCanceledNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User; 
use Auth;
use Carbon\Carbon; 
use App\Models\Employee;
use App\Exports\DealConfirmExport;
use Maatwebsite\Excel\Facades\Excel;

class BookingControllers extends Controller
{
     public function index()
     {
       if (\Auth::user()->roles_id == 1 || \Auth::user()->roles_id == 11) {
            // $LeadBookingConfirm = DB::table('leads')
            // ->where('lead_status',14)->latest()->get(); 
            $LeadBookingConfirm = DB::table('booking_confirms')
            ->join('leads', 'booking_confirms.lead_id', '=', 'leads.id')
            ->whereIn('booking_status',[14,15]) 
            ->select('leads.*', 'booking_confirms.lead_id', 'booking_confirms.booking_status',
            'booking_confirms.booking_date','booking_confirms.booking_amount as booking_confirm_amaunt','booking_confirms.created_at as bcd',
            'booking_confirms.booking_project as bj')
            ->latest()->get();

            $projectTypes = DB::table('project_types')->get();
            return view('pages.booking.index',compact(['LeadBookingConfirm','projectTypes']));
        }
        else
        {
            $empId = DB::table('employees')->where('user_id',Auth::user()->id)->first();
            // $LeadBookingConfirm = DB::table('leads')
            // ->where('lead_status',14)
            // ->where('leads.assign_employee_id',$empId->id)->latest()->get(); 
            $LeadBookingConfirm = DB::table('booking_confirms')
            ->join('leads', 'booking_confirms.lead_id', '=', 'leads.id')
            ->whereIn('booking_status',[14,15]) 
            ->where('leads.assign_employee_id',$empId->id)
            ->select('leads.*', 'booking_confirms.lead_id', 'booking_confirms.booking_status',
            'booking_confirms.booking_date','booking_confirms.booking_amount as booking_confirm_amaunt','booking_confirms.created_at as bcd',
            'booking_confirms.booking_project as bj')
            ->latest()->get();
            $projectTypes = DB::table('project_types')->get();
            return view('pages.booking.index',compact('LeadBookingConfirm','projectTypes'));
        } 
     }
     
     
     
     public function FilterLeadByBookingConfirm(Request $request)
    {

            $submit = $request['submit']; 
  
           if($submit == 'submit')
           {
            
            
                $filter = $request->sortbyLead; 
                $employeefilter = $request->employee; 
                $BuyingLocationfilter = $request->BuyingLocation; 
                $projectNamefilter = $request->projectName; 
                // is_null($filter) || is_null($employeefilter) || is_null($BuyingLocationfilter) || is_null($projectNamefilter)
                 if($request->sortbyLead == null && $request->employee == null && $request->BuyingLocation == null &&  $request->projectName == null)
                 {
                    
                     return redirect()->route('booking-index')->with('message','Please select anyone' );
                 }
                 else
                 { 

                    $LeadStatus = DB::table('lead_statuses')->get(); 
                    $LeadBookingConfirm = DB::table('leads as l')
                    ->where('l.common_pool_status', 0)
                    ->where('l.lead_status', 14)
                    ->where('l.project_type', 'like', '%'.$filter.'%')
                    ->where('l.location_of_leads', 'like', '%'.$BuyingLocationfilter.'%')
                    ->where('l.assign_employee_id', 'like', '%'.$employeefilter.'%')
                    ->where('l.project_id', 'like', '%'.$projectNamefilter.'%')
                    ->join('employees as e', 'e.id', '=', 'l.assign_employee_id')
                    ->join('booking_confirms as bc', 'bc.lead_id', '=', 'l.id')
                    ->select('l.*', 'e.employee_name', 'bc.booking_status','bc.booking_date','bc.booking_amount as booking_confirm_amaunt','bc.created_at as bcd',
                    'bc.booking_project as bj')
                    ->get();
                    // dd($LeadBookingConfirm);
                    $employees = Employee::all();
                    $projectTypes = DB::table('project_types')->get(); 
                    return view('pages.booking.index',compact(['LeadBookingConfirm','LeadStatus','employees','projectTypes'])); 
                  }
                
           }
           else
           {
              return redirect()->back();
           }
    }

     public function BookingDetails($id)
     {

      $relations = DB::table('relationship')->get();
      $projects = DB::table('projects')->get();
      $leadnames = DB::table('leads')->get();
      $leads = DB::table('leads')->where('id', decrypt($id))->first();  
      $teams = DB::table('teams')->where('id', decrypt($id))->first();
      $locations =  DB::table('locations')->get();
      $LeadTypes = DB::table('lead_type_bifurcation')->get();
      $SourceTypes =  DB::table('source_types')->get();
      $employees =  DB::table('employees')
      ->where('lead_assignment',1)->get();
      $LeadStatus = DB::table('lead_statuses')->get();
      $projectTypes = DB::table('project_types')->get();
      $buyerSellers = DB::table('buyer_sellers')->get(); 
      $number_of_units = DB::table('number_of_units')->get();
      $Budgets = DB::table('budget')->get();
      $preferences = DB::table('preference')->get();
      $property_requirements = DB::table('property_requirement')->get();
     return view('pages.booking.booking-details',compact(['locations','SourceTypes','employees','projectTypes','leads','LeadStatus','buyerSellers','number_of_units','property_requirements','LeadTypes','Budgets','preferences','relations','leadnames','projects'])); 
     }

       public function isBookingCancelled(Request $request, $id)
       { 

         
            $empCancelNotificataion = DB::table('leads')->where('id',decrypt($id))->first();
            //   dd($empCancelNotificataion);

            $LeadAssinglaction = DB::table('locations')
                ->join('leads', 'leads.location_of_leads','=','locations.id')
                ->where('locations.id', $empCancelNotificataion->location_of_leads)
                ->select('locations.location')->first();

                $buyerSelllerName = DB::table('leads')
                ->join('buyer_sellers', 'leads.buyer_seller','=','buyer_sellers.id')
                ->where('buyer_sellers.id', $empCancelNotificataion->buyer_seller)
                ->select('buyer_sellers.name')->first();

                $user = User::first(); 
                $employeeN = DB::table('employees')->where('id',$empCancelNotificataion->assign_employee_id)->first();  
                $empNotification = User::where('id',$employeeN->user_id)->first(); 

           $isBookingCancelled = array();
           $isBookingCancelled['lead_status'] = 15; 
           $BookingCancel = DB::table('leads')->where('id',decrypt($id))->update($isBookingCancelled);

           $isBookingCC = array();
           $isBookingCC['booking_status'] = 15; 
            DB::table('booking_confirms')->where('lead_id',decrypt($id))->update($isBookingCC);

            $lead_status_histories = array();
            $lead_status_histories['status_id'] =15;
            $lead_status_histories['lead_id'] = decrypt($id);
            $lead_status_histories['customer_interaction'] = $request->customer_interaction ?? "This Booking Cancelled";
            $lead_status_histories['created_at'] = Carbon::now();
            $lead_status_histories['created_by'] = Auth::user()->id;
            DB::table('lead_status_histories')->where('id',decrypt($id))->insert($lead_status_histories); 

           if ($empCancelNotificataion->budget ==null ) {
               $isBudget = "NA";
           } else {
            $isBudget = $empCancelNotificataion->budget;
           }

            
            

           $isCancel = [
             
            'leads' => $empCancelNotificataion->lead_name,
            'leads_status' => 15,
            'property_requirement' => $buyerSelllerName->name,
            'EmployeeName' => Auth::user()->id,
            'location' => $LeadAssinglaction->location ?? null,
            'number_of_units' => $empCancelNotificataion->number_of_units,
            'budget' =>   $isBudget,
            'leadsID' =>  $empCancelNotificataion->id,
            'privios_emp' =>  $empCancelNotificataion->assign_employee_id, 
            'current_empid' => $empCancelNotificataion->assign_employee_id,
         ]; 
            
            Notification::sendNow([$empNotification,$user], new IsLeadCanceledNotification($isCancel));
         
           return redirect()->route('booking-index')->with('success', 'Booking Cancelled');
       }
       
       public function DealConfirmDownload()
       {
           return Excel::download(new DealConfirmExport, 'dealconfirm.xlsx');
       }
}

