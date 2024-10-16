<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Imports\ClientSheetImport;
use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Models\Lead;
use Auth;
use Illuminate\Http\JsonResponse; 
use Carbon\Carbon; 
use App\Exports\BulkExport;

class BulkUpload extends Controller
{ 
    //
    public function index()
    {
      $ClientSheets = DB::table('client_sheets')->get();
      $LeadSheets = DB::table('lead_sheets')->orderBy('created_at','desc')->paginate(50);
      
      return  view('pages.bulkupload.index',compact('ClientSheets','LeadSheets'));
    }
      
      public function LeadUpload(Request $request)
      {
          $submit = $request['submit'];
          if ($submit == 'submit') {
              $request->validate([
                  'file' => 'required|mimes:xlsx',
              ]);

              // Get the count of records before the import
              $previousRecordsCount = DB::table('lead_sheets')->count();

              $file = $request->file('file')->store('import');
              $import = new LeadsImport;
              $imported = Excel::import($import, $file); // Import the data

              // Get the count of records after the import
              $newRecordsCount = DB::table('lead_sheets')->count() - $previousRecordsCount;

              return back()->with('excel', 'Excel file imported successfully. ' . $newRecordsCount . ' new records inserted.');
          } else {
              return "Error";
          }
      }

    

    public function export() 
    {
      // return (new InvoicesExport)->download();

        return Excel::download(new LeadsImport, 'invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function moveToPool(Request $request)
    { 
        $submit = $request['submit'];
        if ($submit) {  
 
          if ($request->cp1 == null) {
            return redirect('bulk-upload')->with('error','Please Select any one');
          }
          

          // return $request->cp1;
          $MoveToCommmonPollCount = DB::table('lead_sheets') 
            ->whereIn('id', explode(',', $request->cp1))
            //  ->where('is_exist',0)
            ->count(); 
         
          // dd($MoveToCommmonPollCount);
            // dd($leadSheetsIsNoCount);
            // Explode comma-separated IDs from the input
            $leadSheetIds = explode(',', $request->cp1);
            
            // Fetch lead sheets based on the provided IDs

           

            $leadSheets = DB::table('lead_sheets')
                ->whereIn('id', $leadSheetIds)
                ->where('is_exist', 0)
                ->get();

            $leadSheetsIsExist = DB::table('lead_sheets')
                ->whereIn('id', $leadSheetIds)
                ->where('is_exist', 1)
                ->get();
 
             
              
            foreach($leadSheetsIsExist as $SheetsIsExist)
            {

              $setname[] = $SheetsIsExist->lead_name; 
                
            } 

              // $leadName = implode(',',$setname); 
             
               
            foreach ($leadSheets as $leadSheet) {
 
              
              
                $lead_type_bifurcation = 0; // Default value if not found
                switch ($leadSheet->lead_type_bifurcation) {
                    case "Hot":
                        $lead_type_bifurcation = 1;
                        break;
                    case "Cold":
                        $lead_type_bifurcation = 2;
                        break;
                    case "WIP":
                        $lead_type_bifurcation = 3;
                        break;
                }

                $leadContactNumberExist = DB::table('leads')
                ->where('contact_number', $leadSheet->contact_number)
                ->exists(); 

                
                
                 $leadIsExitsProjects = DB::table('leads')
                ->where('contact_number', $leadSheet->contact_number)
                ->first(); 

                 

                $projectIds = explode(',', $leadIsExitsProjects->existing_property ?? $leadSheet->existing_property);

                $projectNames = DB::table('projects')
                    ->whereIn('id', $projectIds)
                    ->pluck('project_name')
                    ->implode(', ');
  
              

                $date =date('Y-m-d H:i:s', strtotime($leadSheet->date));
                $number_of_units = str_replace('Unit', '', $leadSheet->number_of_units); 


                 

                if ($leadContactNumberExist) { 
                    // Update the "is_exist" column to 1 for rows with the matching contact number 

                   
                  $collection = collect($leadSheet->existing_property);
                  $delimiter = ', ';
                  $implodedString = $collection->implode($delimiter);

                  $leadIsExitsProject = DB::table('leads')
                  ->where('contact_number', $leadSheet->contact_number)
                  ->first();

                  

                  $existingProperty = explode(',', $leadIsExitsProject->existing_property);
                  $newProperties = explode(',', $leadSheet->existing_property);

                  // Merge the values and remove duplicates
                  $mergedProperties = implode(',', array_unique(array_merge($existingProperty, $newProperties)));
                  $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
                   
                    
                      $updateData = [
                    'date' => $leadIsExitsProject->date ?? $date,
                    'lead_name' => $leadIsExitsProject->lead_name ?? \Str::title($leadSheet->lead_name) ,
                    'lead_status' => $leadIsExitsProject->lead_status ?? $leadSheet->lead_status,
                    'common_pool_status' => $leadIsExitsProject->common_pool_status ?? 0,
                    'source' =>  $leadIsExitsProject->source ?? $leadSheet->source,
                    'project_type' => $leadIsExitsProject->project_type ?? $leadSheet->project_type,
                    'assign_employee_id' => $leadIsExitsProject->assign_employee_id,
                    'location_of_leads' => $leadIsExitsProject->location_of_leads ?? $leadSheet->location_of_leads,
                    'customer_interaction' => $leadSheet->customer_interaction,
                    'lead_type_bifurcation_id' => $leadIsExitsProject->lead_type_bifurcation ?? $lead_type_bifurcation,
                    'is_featured' => 0,
                    'number_of_units' => $leadIsExitsProject->number_of_units ?? $leadSheet->number_of_units,
                    'regular_investor' => "NO",
                    'property_requirement' => $leadIsExitsProject->property_requirement ?? $leadSheet->property_requirement, 
                    'lead_email' => $leadIsExitsProject->lead_email ??  $leadSheet->customer_email, 
                    'buyer_seller' => $leadIsExitsProject->buyer_seller ?? $leadSheet->customer_type,
                    'next_follow_up_date' => $leadIsExitsProject->next_follow_up_date ?? null,
                    'alt_no_Whatsapp' => $leadIsExitsProject->alt_no_Whatsapp ?? $leadSheet->alt_contact_number_1,
                    'alt_no_Whatsapp_2' => $leadIsExitsProject->alt_no_Whatsapp_2 ?? $leadSheet->alt_contact_number_2,
                    'alt_contact_name' => $leadIsExitsProject->alt_contact_name ?? $leadSheet->alt_contact_name, 
                    'existing_property' => $mergedProperties ?? $leadSheet->existing_propertys, 
                    'country_code' => $leadIsExitsProject->country_code ?? $leadSheet->country_code_bu ?? null,
                    'about_customer' => $leadSheet->customer_profile ?? null,
                    'created_by' => $leadIsExitsProject->created_by ?? null,
                ];
                
                // Conditionally update columns with non-null values from $leadSheet
                  foreach ($updateData as $column => $value) {
                      if ($value !== null) {
                          $updateData[$column] = $value;
                      } elseif ($leadIsExitsProject !== null) {
                          // Use values from $leadIsExitsProject if $value is null
                          $updateData[$column] = $leadIsExitsProject->$column;
                      }
                  }
 
                  
                  DB::table('leads')
                      ->where('contact_number', $leadSheet->contact_number)
                    ->update($updateData);

                    DB::table('lead_sheets')
                        ->where('contact_number', $leadSheet->contact_number)
                        ->update(['is_exist' => 1]);
                  
                        $lead_history['lead_id'] = $leadIsExitsProject->id;
                        $lead_history['date'] = Carbon::now();
                        $lead_history['status_id'] = $leadIsExitsProject->lead_status; 
                        $lead_history['customer_interaction'] = $leadSheet->customer_interaction  ?? "Comming From Bulk Upload";
                        // $lead_history['customer_interaction'] = $leadSheet->customer_interaction . isset($leadIsExitsProject->existing_property) ?  'Befor move to bulk upload Existing Property : '. $projectNames :''
                        // ?? "Comming From Bulk Upload";  
                        $lead_history['next_follow_up_date'] =  $leadIsExitsProject->next_follow_up_date ?? null; 
                        $lead_history['created_by'] = Auth::user()->id;
                        $lead_history['created_at'] = Carbon::now();
                       
                        $userData = DB::table('lead_status_histories')->insert($lead_history); 

                        // return redirect()->back()->with('error','Lead Alreay Contact Number Exist');
                } else {
                
                  
                $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
                 if ($leadSheet->country_code_bu == "+91") {
                  $CountryCodeIN = $leadSheet->country_code_bu != null ? '+'.$leadSheet->country_code_bu : null;  
                 
                } else {
                  $CountryCode = $leadSheet->country_code_bu != null ? '+'.$leadSheet->country_code_bu : null; 
                }

                  //dd($CountryCode);
                  // dd(substr($CountryCodeIN, 1));
                  
                     $data = array(
                      'date' => $date,
                      'lead_name' => \Str::title($leadSheet->lead_name) ?? null,
                      'contact_number' => $leadSheet->contact_number,
                      'lead_status' => $leadSheet->lead_status ?? 1,
                      'common_pool_status' => 1 ?? 0,
                      'source' => $leadSheet->source ?? null,
                      'project_type' => $leadSheet->project_type ?? null,
                      'assign_employee_id' => $emp->id,
                      'location_of_leads' => $leadSheet->location_of_leads ?? null,
                      'customer_interaction' => $leadSheet->customer_interaction ?? null,
                      'lead_type_bifurcation_id' => $lead_type_bifurcation ?? null,
                      'is_featured' => 0,
                      'number_of_units' => $leadSheet->number_of_units ?? 1,
                      'regular_investor' => "NO",
                      'property_requirement' => $leadSheet->property_requirement ?? null, 
                      'lead_email' => $leadSheet->customer_email ?? null, 
                      'buyer_seller' => $leadSheet->customer_type ?? null,
                      'next_follow_up_date' =>  null,
                      'alt_no_Whatsapp' => $leadSheet->alt_contact_number_1 ?? null,
                      'alt_no_Whatsapp_2' => $leadSheet->alt_contact_number_2 ?? null,
                      'alt_contact_name' => $leadSheet->alt_contact_name ?? null, 
                      'existing_property' => $leadSheet->existing_property ?? null,
                       'country_code' => isset($CountryCodeIN) ? substr($CountryCodeIN, 1) : $CountryCode,
                      //  'country_code' => $CountryCode ?? substr($CountryCodeIN, 1),
                      'about_customer' => $leadSheet->customer_profile ?? null,
                      'created_by' => Auth::user()->id ?? null,
                      'created_at' => Carbon::now(),
                      'updated_at' => Carbon::now(),
                  );

                    
                //  return substr($CountryCodeIN, 1);
                   
                  $GetId = DB::table('leads')->insertGetId($data);
                    
                  $lead_history['lead_id'] = $GetId;
                  $lead_history['date'] = Carbon::now();
                  $lead_history['status_id'] = 1; 
                  $lead_history['customer_interaction'] = $leadSheet->customer_interaction ?? "Comming From Bulk Upload";  
                  $lead_history['next_follow_up_date'] =  null; 
                  $lead_history['created_by'] = Auth::user()->id;
                  $lead_history['created_at'] = Carbon::now(); 
                  $userData = DB::table('lead_status_histories')->insert($lead_history); 
                  
                   DB::table('lead_sheets')->where('contact_number', $data['contact_number'])
                  ->delete();
                  
                }  
                
            } 
            if (count($leadSheetsIsExist) > 0) {
              // return redirect()->back()->with('success','Lead Moved To Common Pool Some leads Exists');
              return redirect()->back()->with('success','Lead already exists, not moved to common pool. Use Merge & Delete option');
            } 
            else {
              return redirect()->back()->with('success', $MoveToCommmonPollCount.' Lead Moved To Common Pool');
            } 
            
        } 
    }
    
     public function DeleteBulkWithNoMergeData(Request $request)
    {

 
     
       $leadSheetsIsExists = DB::table('lead_sheets') 
       ->where('id', $request->updatelocationID)
      ->delete();
       return redirect('bulk-upload')->with('Delete','Data deleted successfully');
 
    }


    public function DeleteBulkSheet(Request $request)
    {

    	
     	//  $leadSheets = DB::table('lead_sheets')
      // ->where('id', $request->updatelocationID)
      // ->first();

      // dd($leadSheets);
 
      // if ($leadSheets->is_exist == 0) {  
      //   $leads = DB::table('lead_sheets')->where('id', $request->updatelocationID)->delete(); 
      // } else {

        // $leadGetData = DB::table('leads')->where('contact_number', $leadSheets->contact_number)->first(); 


        // $collection = collect($leadSheets->existing_property);
        // $delimiter = ', ';
        // $implodedString = $collection->implode($delimiter);

         
          
        // DB::table('leads')->where('contact_number',$leadSheets->contact_number)->update([
        //   'existing_property' => $implodedString ?? $leadSheets->existing_property,
        //   'location_of_leads' => $leadSheets->location_of_leads ?? $IsLeadExist->location_of_leads,
        //   'source' => $leadSheets->source ?? $IsLeadExist->source,
        //   'lead_status' => $leadSheets->lead_status ?? $IsLeadExist->lead_status,
        //   'customer_interaction' => isset($leadSheets->customer_interaction) ? $leadSheets->customer_interaction : ($IsLeadExist->customer_interaction ?? null),
        //   'buyer_seller' =>  isset($leadSheets->customer_type) ? $leadSheets->customer_type : ($IsLeadExist->customer_type ?? null),
        //   'alt_no_Whatsapp' => isset($leadSheets->alt_contact_number_1) ? $leadSheets->alt_contact_number_1 : ($IsLeadExist->alt_contact_number_1 ?? null),
        //   'alt_contact_name' => isset($leadSheets->alt_contact_name) ? $leadSheets->alt_contact_name : ($IsLeadExist->alt_contact_name ?? null),
        //   'alt_no_Whatsapp_2' => isset($leadSheets->alt_contact_number_2) ? $leadSheets->alt_contact_number_2 : ($IsLeadExist->alt_contact_number_2 ?? null),
        //   'about_customer' => isset($leadSheets->customer_profile) ? $leadSheets->customer_profile : ($IsLeadExist->customer_profile ?? null),
        // ]);

        // $lead_history['lead_id'] = $leadGetData->id;
        // $lead_history['date'] = Carbon::now();
        // $lead_history['status_id'] = $leadSheets->lead_status; 
        // $lead_history['customer_interaction'] = $leadSheets->customer_interaction 
        // ?? "Comming From Bulk Upload";  
        // $lead_history['next_follow_up_date'] = null; 
        // $lead_history['created_by'] = Auth::user()->id;
        // $lead_history['created_at'] = Carbon::now();
        // $userData = DB::table('lead_status_histories')->insert($lead_history); 
        $leads = DB::table('lead_sheets')->where('id', $request->updatelocationID)->delete(); 

      // }
        
        return redirect('bulk-upload')->with('Delete','Data deleted successfully');
 
    }
    
    public function BulkUploadDeleteWithNo(Request $request)
    {
        
       
     
        if ($request->selectedUserId == null || $request->selectedUserId == []) {
          
          $leadSheetsIsNoCount = DB::table('lead_sheets') 
          // ->whereIn('id', $request->selectedUserId)
          ->where('is_exist',0)
          ->count(); 

            $leadSheetsIsExists = DB::table('lead_sheets') 
            ->where('is_exist', 0)
           ->delete();

      } else {
        $leadSheetsIsNoCount = DB::table('lead_sheets') 
        ->whereIn('id', $request->selectedUserId)
        ->where('is_exist',0)
        ->count(); 


          $leadSheetsIsExists = DB::table('lead_sheets') 
          ->whereIn('id', $request->selectedUserId)
          ->where('is_exist',0)
          ->delete(); 
 
      } 

      session()->flash('success', $leadSheetsIsNoCount .  ' entries deleted successfully ');
      return new JsonResponse(['message' => 'Bulk Upload No Successfully'], 200);
      // return redirect('bulk-upload')->with('Delete','Bulk Upload Status No Successfully Deleted');
        
    }

    public function BulkUploadDeleteWithYes(Request $request)
    {
     
      if ($request->selectedUserId == null || $request->selectedUserId == []) {

        $leadSheetsIsYesXCount = DB::table('lead_sheets') 
          // ->whereIn('id', $request->selectedUserId)
          ->where('is_exist', 1)
          ->count();


          $leadSheetsIsExists = DB::table('lead_sheets') 
          ->where('is_exist', 1)
          ->get();

      } else {

        $leadSheetsIsYesXCount = DB::table('lead_sheets') 
          ->whereIn('id', $request->selectedUserId)
          ->where('is_exist', 1)
          ->count();

          $leadSheetsIsExists = DB::table('lead_sheets') 
          ->whereIn('id', $request->selectedUserId)
          ->where('is_exist', 1)
          ->get(); 
      }
       
        foreach($leadSheetsIsExists as $leadSheet)
        { 
          
          $leadContactNumberExist = DB::table('leads')
          ->where('contact_number', $leadSheet->contact_number)
          ->exists();  

          if ($leadContactNumberExist == true) { 
 
              $IsLeadExist = DB::table('leads')
              ->where('contact_number', $leadSheet->contact_number)
              ->first();  
      
              $collection = collect($leadSheet->existing_property);
              $delimiter = ', ';
              $implodedString = $collection->implode($delimiter);
                
              DB::table('leads')->where('contact_number',$leadSheet->contact_number)->update([
                //'existing_property' => $implodedString ?? $leadSheet->existing_property,
                'location_of_leads' => $leadSheet->location_of_leads ?? $IsLeadExist->location_of_leads,
                'source' => $leadSheet->source ?? $IsLeadExist->source,
                'lead_status' => $leadSheet->lead_status ?? $IsLeadExist->lead_status,
                'customer_interaction' => isset($leadSheet->customer_interaction) ? $leadSheet->customer_interaction : ($IsLeadExist->customer_interaction ?? null),
                'buyer_seller' =>  isset($leadSheet->customer_type) ? $leadSheet->customer_type : ($IsLeadExist->customer_type ?? null),
                'alt_no_Whatsapp' => isset($leadSheet->alt_contact_number_1) ? $leadSheet->alt_contact_number_1 : ($IsLeadExist->alt_contact_number_1 ?? null),
                'alt_contact_name' => $leadSheet->alt_contact_name ?? $IsLeadExist->alt_contact_name,
                'alt_no_Whatsapp_2' => isset($leadSheet->alt_contact_number_2) ? $leadSheet->alt_contact_number_2 : ($IsLeadExist->alt_contact_number_2 ?? null),
                'about_customer' => isset($leadSheet->customer_profile) ? $leadSheet->customer_profile : ($IsLeadExist->customer_profile ?? null),
              ]);
 
               DB::table('lead_sheets')->where('id', $leadSheet->id) ->delete(); 
          } else {
              return "error";
          }
        }
        
        session()->flash('success', $leadSheetsIsYesXCount .' records deleted succesfully ');
        return new JsonResponse(['message' => 'Bulk Upload Merge & Deleted Successfully'], 200); 
    }

    public function BulkReportsDownload()
    {
        return Excel::download(new BulkExport, 'Bulk Sheets.xlsx');
    }

}



