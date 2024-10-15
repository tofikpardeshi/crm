<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Team;
use Carbon\Carbon; 
use Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportProject;
use App\Exports\ProjectExportReports;
use DB;
use Auth;
use Mail; 
use App\Mail\ProjectMail;

class ProjectController extends Controller
{
    //
    public function index()
    { 
        $searchTrue = 0;
        $projectTeams = DB::table('projects')->latest()->paginate(10);
        return view('pages.project.index',compact(['projectTeams','searchTrue']));
    }

    public function CreateProjectView()
    {
        $designations = DB::table('designations')->get();
        $locations =  DB::table('locations')
        ->orderBy('location','ASC')
        ->get();
        $projectTypes = DB::table('project_types')->get();
        $categorys = DB::table('category')->get();
        return view('pages.project.create',compact(['projectTypes','locations','categorys','designations']));
    }

        public function CreateProject(Request $request)
    {
          //return $request;
        $submit = $request['submit'];
        
        if($submit == 'submit')
        { 
 
            $validate = $request->validate([
                 'project_name' => 'required',
                 // 'email' => 'email| unique:projects,email',
                 'category' => 'required', 
                 'location' => 'required',
                //  'team_name' => 'required',
                 'sector' => 'required',
                 //'date_of_birth' => 'date|date_format:Y-m-d|before:'.now()->subYears(18)->toDateString(),
                // 'wedding_anniversary' => 'date|date_format:Y-m-d|before:'.now(),
                // 'contact_number' => 'unique:projects,contact_number|digits:10',
                  'project_type' => 'required',  
        ],
        [
                 'project_name.required' => "Project Name can't be left blank.",
                //  'email.required' => 'Email field is required',
                  'category.required' => 'Select Category from the dropdown.', 
                 'location.required' => 'Select Location from the dropdown.',
                //  'team_name.required' => 'Team name field is required',
                 'sector.required' => "Sector / Area can't be left blank.",
                //  'contact_number.required' => 'contact number field is required',
                 'project_type.required' => 'Select Project Type from the dropdown.', 
        ]);
        
        
        if (preg_replace('/\s+/', '',$request->contact_number['main']) != null) { 
            $contactCountryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']); 
            $contactwithcountryCode = implode(', ',$contactCountryCode); 
            $contacfinalcountryCode = str_replace(',', '', $contactwithcountryCode);  
          } 
         
        if (preg_replace('/\s+/', '',$request->alternate_contact_number['altmain']) != null) { 
            $alternateCountryCode = explode(preg_replace('/\s+/', '',$request->alternate_contact_number['altmain']), $request->alternate_contact_number['altfull']);
            $alternatewithcountryCode = implode(', ',$alternateCountryCode); 
            $alternatefinalcountryCode = str_replace(',', '', $alternatewithcountryCode);  
          }
          
        //   $projectCC1 =  explode(',',$request->project_cc_mail); 
          
        //   //return $projectCC1;
        //     if ($projectCC1 == [""]) {
        //         $projectCC = null;
        //     } else {
        //         $projectCC =  $projectCC1 ;
        //     } 
            $projectCC[] = 'prakharsahay@homents.in';
            $projectCC[] ='pradeepmishra@homents.in';
        
        if ($request->project_image) {
            //return "Hello";
            // $position = strpos($request->project_image, ';');
            // $sub = substr($request->project_image,0,$position);
            // $ext = explode('/',$sub)[0];

            // $name = time().".".$ext;
            // $img = Image::make($request->project_image);
            // $upload_path = 'backend/employee/';
            // $img_url = $upload_path.$name;
            // $img->save($img_url); 

            $image = $request->file('project_image');
            $filename =date('Ymdhi').'.'.$image->getClientOriginalExtension();
            $path = public_path().'/backend/project/';  
            $image->move($path, $filename); 

            $ProjectType = implode(',',$request->project_type ?? []);

        $project = new Project;
        $project->project_name = $request->project_name;
        $project->project_category =  $request->category;
        $project->email = $request->email;
        $project->location = $request->location;
        $project->rera_number = $request->rera_number; 
        $project->name_of_developers = $request->name_of_developer; 
        $project->sector = $request->sector; 
        $project->contact_number = $request->contact_number['main'];
        $project->contant_country_code = $contacfinalcountryCode ?? null;
        $project->project_type = $ProjectType;
        $project->total_no_of_units = $request->total_no_of_units;
        $project->total_no_of_occupied_units = $request->total_no_of_occupied_units;
        $project->total_no_of_unoccupied_units = $request->total_no_of_unoccupied_units;
        $project->total_occupancy = $request->total_occupancy;
        $project->project_launch_date = $request->project_launch_date;
        $project->project_completion_date = $request->project_completion_date;
        $project->project_website_link = $request->project_website_link;
        $project->project_fb_group_link = $request->project_fb_group_link;
        $project->project_status_id = $request->project_status;
        $project->size_of_apartment = $request->size_of_apartment;
        $project->price_of_apartment = $request->price_of_apartment;
        $project->project_plan_details = $request->project_plan_details;
        $project->created_by = Auth::user()->id;
        $project->alternate_contact_number = $request->alternate_contact_number['altmain'];
        $project->alt_country_code = $alternatefinalcountryCode ?? null;   
        $project->project_status = 1; 
        $project->project_image = $filename;  
        // $project->project_cc_mail = $request->project_cc_mail;
        
        if ($request->email == null) {
            # code...
        } else {
            $ProjectDetails = [
                'title' => 'New Project Created '. $request->project_name,
                'body' => 'This is an automated email as you have been create a new project. There is no need to reply to this email.'
            ]; 
            Mail::to($request->email)
                    ->cc($projectCC) 
                    ->send(new ProjectMail($ProjectDetails)); 

               
                    
        }
        
        if($project->save())
        {


            $data_history = array();
            $data_history['project_name'] = $request->project_name;
            $data_history['email'] = $request->email;
            $data_history['project_category'] = $request->category;
            $data_history['location'] = $request->location;
            $data_history['sector'] = $request->sector;
            $data_history['rera_number'] = $request->rera_number;
            $data_history['contact_number'] = $request->contact_number['main'];
            $data_history['name_of_developers'] = $request->name_of_developer; 
            $data_history['price_of_apartment'] = $request->price_of_apartment;
            $data_history['alternate_contact_number'] = $request->alternate_contact_number['altmain'];
            $data_history['contact_number'] = $request->contact_number;
            $data_history['project_id'] = $project->id;
            $data_history['project_type'] = $ProjectType;
            $data_history['created_at'] = Carbon::now(); 
            $data_history['created_by'] = \Auth::user()->id; 
            $data_history['project_image'] = $filename;
            $data_history['total_no_of_units'] = $request->total_no_of_units;
            $data_history['total_no_of_occupied_units'] = $request->total_no_of_occupied_units;
            $data_history['total_no_of_unoccupied_units'] = $request->total_no_of_unoccupied_units;
            $data_history['total_occupancy'] = $request->total_occupancy;
            $data_history['project_status'] = $request->project_status;
            $data_history['project_plan_details']= $request->project_plan_details ?? null;
            $projectHistory =  DB::table('project_historys')->insert($data_history);

            // return "byy";
            if($request->team_name == Null && $request->team_email == Null && $request->team_phone_number == Null && $request->alternate_contact_number_team == Null && $request->date_of_birth == Null && $request->wedding_anniversary == Null && $request->designation == Null)
            {
               
            }else 
            {
                // return "Hello";
            $team = new Team;
            $team->team_name = $request->team_name;
            $team->team_email = $request->team_email;
            $team->team_phone_number = $request->team_phone_number;
            $team->alternate_contact_number_team = $request->alternate_contact_number_team;
            $team->date_of_birth = $request->date_of_birth;
            $team->wedding_anniversary = $request->wedding_anniversary;
            $team->builder_id = $request->builder;
            $team->name_of_developer = $request->name_of_developer;
             $team->project_id = $project->id;
            $team->designation = $request->designation; 
            $team->save();
            }
            
        } 

        // $value = session('key', $project->id);
        \Session::put('projectID', $project->id); 
        return redirect('create-project')->with('success','Project Created Successfully');
        }
        else
        {
            $ProjectType = implode(',',$request->project_type ?? []);
            
            // return "Byysdsd";
        $project = new Project;
        
        $project->project_name = $request->project_name;
        $project->project_category =  $request->category;
        $project->email = $request->email;
        // $project->project_cc_mail = $request->project_cc_mail;
        $project->location = $request->location;
        $project->alternate_contact_number = $request->alternate_contact_number['altmain'];
        $project->alt_country_code = $alternatefinalcountryCode ?? null; 
        $project->rera_number = $request->rera_number; 
        $project->sector = $request->sector;
        $project->name_of_developers = $request->name_of_developer; 
        $project->contact_number = $request->contact_number['main'];
        $project->contant_country_code = $contacfinalcountryCode ?? null;
        $project->total_no_of_units = $request->total_no_of_units;
        $project->total_no_of_occupied_units = $request->total_no_of_occupied_units;
        $project->total_no_of_unoccupied_units = $request->total_no_of_unoccupied_units;
        $project->total_occupancy = $request->total_occupancy;
        $project->project_launch_date = $request->project_launch_date;
        $project->project_completion_date = $request->project_completion_date;
        $project->project_website_link = $request->project_website_link;
        $project->project_fb_group_link = $request->project_fb_group_link;
        $project->project_status_id = $request->project_status;
        $project->size_of_apartment = $request->size_of_apartment;
        $project->price_of_apartment = $request->price_of_apartment;
        $project->project_plan_details = $request->project_plan_details;
        $project->created_by = Auth::user()->id;
        $project->project_type = $ProjectType;
        
         if ($request->email == null) {
            # code...
        } else {
             
            $ProjectDetails = [
                'title' => 'New Project Created '. $request->project_name,
                'body' => 'This is an automated email as you have been create a new project. There is no need to reply to this email.'
            ];
            // $ccEmails = ['prakharsahay@@homents.in','pradeepmishra@@homents.in']; 
             
            Mail::to($request->email)
                    ->cc($projectCC) 
                    ->send(new ProjectMail($ProjectDetails)); 
        }
        
        $project->save(); 
        
        if($project)
        {
            $data_history = array();
             $data_history['project_name'] = $request->project_name;
            $data_history['email'] = $request->email;
            $data_history['project_category'] = $request->category;
            $data_history['location'] = $request->location;
            $data_history['sector'] = $request->sector;
            $data_history['rera_number'] = $request->rera_number;
            //$data_history['contact_number'] = $request->contact_number;
            $data_history['name_of_developers'] = $request->name_of_developer; 
            $data_history['alternate_contact_number'] = $request->alternate_contact_number['altmain'];
            $data_history['contact_number'] = $request->contact_number['main'];
            $data_history['project_id'] = $project->id;
            $data_history['project_type'] = $ProjectType;
            $data_history['created_at'] = Carbon::now(); 
            $data_history['created_by'] = \Auth::user()->id;
            $data_history['total_no_of_units'] = $request->total_no_of_units;
            $data_history['total_no_of_occupied_units'] = $request->total_no_of_occupied_units;
            $data_history['total_no_of_unoccupied_units'] = $request->total_no_of_unoccupied_units;
            $data_history['project_plan_details']= $request->project_plan_details ?? null;
            $data_history['total_occupancy'] = $request->total_occupancy;
            $data_history['project_status'] = $request->project_status;
            $projectHistory =  DB::table('project_historys')->insert($data_history);
            if($request->team_name == Null && $request->team_email == Null && $request->team_phone_number == Null && $request->alternate_contact_number_team == Null && $request->date_of_birth == Null && $request->wedding_anniversary == Null && $request->designation == Null)
            {

                // $project_id[] = DB::table('projects')->where('id',$project->id)
                // ->first();

                //  //return $project_id;

                //    $projectID = implode(',',$project_id->id  ?? []);
 
                //   return $projectID;
                
                // $builderProjectID = array();
                // $builderProjectID['project_id'] = $projectID;
                // DB::table('teams')->where('id',$project_id->assign_mumbers)->update($builderProjectID);
                 
            }else
            {
                // return "Dev";
                // $project_id = implode(',',$project->id ?? []);

                // return  $project_id;
            $team = new Team;
            $team->team_name = $request->team_name;
            $team->team_email = $request->team_email;
            $team->team_phone_number = $request->team_phone_number;
            $team->alternate_contact_number_team = $request->alternate_contact_number_team;
            $team->date_of_birth = $request->date_of_birth;
            $team->wedding_anniversary = $request->wedding_anniversary;
            $team->project_id = $project->id;
            $team->builder_id = $request->builder;
            $team->name_of_developer = $request->name_of_developer;
            $team->designation = $request->designation;
            $team->save();

            $project->assign_mumbers = $team->id;
        $project->project_status = 1;  
        $project->save();
            }
        }
        
        \Session::put('projectID', $project->id); 
        return redirect('create-project')->with('success','Project Created Successfully');
        }
             
        }
        else{
            return "Error";
        }
    }

    public function EditProject($id)
    {   
        $designations = DB::table('designations')->get();
        $locations =  DB::table('locations')->get();
        $projectTypes = DB::table('project_types')->get();
        $project = DB::table('projects')->where('id', decrypt($id))->first();
        $teams = DB::table('teams')
        ->join('projects','projects.id','=','teams.project_id')
        ->select('teams.*')
        ->where('projects.id', decrypt($id))->first();
        $categorys = DB::table('category')->get();
        return view('pages.project.edit',compact(['project','teams','projectTypes','locations','categorys','designations']));
    }

         public function updateProject(Request $request, $id)
    {

        

        //return $request->builder;
        $validate = $request->validate([
            'project_name' => 'required',
            // 'email' => 'required',
            'category' => 'required', 
            'location' => 'required',
            // 'team_name' => 'required',
            'sector' => 'required',
            // 'contact_number' => 'sometimes|numeric',
            // 'alternate_contact_number' => 'sometimes|numeric',
            // 'team_phone_number' => 'sometimes|numeric',
            // 'alternate_contact_number_team' => 'sometimes|numeric',
            'project_type' => 'required',  
        ]);

        $ProjectType = implode(',',$request->project_type ?? []);
        
        // if (preg_replace('/\s+/', '',$request->contact_number['main']) != null) { 
        //     $contactCountryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']); 
        //     $contactwithcountryCode = implode(', ',$contactCountryCode); 
        //     $contacfinalcountryCode = str_replace(',', '', $contactwithcountryCode);  
        //   } 
         
        // if (preg_replace('/\s+/', '',$request->alternate_contact_number['altmain']) != null) { 
        //     $alternateCountryCode = explode(preg_replace('/\s+/', '',$request->alternate_contact_number['altmain']), $request->alternate_contact_number['altfull']);
        //     $alternatewithcountryCode = implode(', ',$alternateCountryCode); 
        //     $alternatefinalcountryCode = str_replace(',', '', $alternatewithcountryCode);  
        //   }
        
        
        // $projectCC1 =  explode(',',$request->project_cc_mail); 
         
        // if ($projectCC1 == [""]) {
        //     $projectCC = null;
        // } else {
        //     $projectCC =  $projectCC1 ;
        // } 
        $projectCC[] = 'prakharsahay@homents.in';
        $projectCC[] ='pradeepmishra@homents.in';

        $data = array();
        $data['project_name'] = $request->project_name;
        $data['email'] = $request->email;
        // $data['project_cc_mail'] = $request->project_cc_mail;
        $data['project_category'] = $request->category;
        $data['location'] = $request->location;
        $data['sector'] = $request->sector;
        $data['rera_number'] = $request->rera_number; 
        $data['name_of_developers'] = $request->name_of_developer; 
       	$data['alternate_contact_number'] = preg_replace('/\s+/', '',$request->alternate_contact_number['altmain']) ?? null;
        $data['alt_country_code'] = $request->alternate_contact_number['AltCountryCode'] ?? null; 
        $data['contact_number'] = preg_replace('/\s+/', '',$request->contact_number['main']) ?? null;
        $data['contant_country_code'] = preg_replace('/\s+/', '',$request->contact_number['projectCCode']) ?? null;
        $data['total_no_of_units'] = $request->total_no_of_units;
        $data['total_no_of_occupied_units'] = $request->total_no_of_occupied_units;
        $data['total_no_of_unoccupied_units'] = $request->total_no_of_unoccupied_units;
        $data['total_occupancy'] = $request->total_occupancy;
        $data['project_launch_date'] = $request->project_launch_date;
        $data['project_completion_date'] = $request->project_completion_date;
        $data['project_website_link'] = $request->project_website_link;
        $data['project_fb_group_link'] = $request->project_fb_group_link;
        $data['project_status_id'] = $request->project_status;
        $data['size_of_apartment'] = $request->size_of_apartment;
        $data['price_of_apartment'] = $request->price_of_apartment;
        $data['project_plan_details'] = $request->project_plan_details;
        $data['project_type'] = $ProjectType;
        $data['project_status'] = 1; 
        
        
        
        
          
          $project = DB::table('projects')->where('id',decrypt($id))->first();
       

        if (($project->email == null &&  $request->email != null) || ($project->email != $request->email) ) { 
             
            $ProjectDetails = [
                'title' => 'Project Details Updated '. $request->project_name,
                'name' => 'Updated By: '. Auth::user()->name,
                'body' => 'This is an automated email as you have been create a new project. There is no need to reply to this email.'
            ]; 
            Mail::to($request->email)
                    ->cc($projectCC) 
                    ->send(new ProjectMail($ProjectDetails)); 
        } 

	    //dd($data);
        $data_history = array();
        $data_history['project_name'] = $request->project_name;
        $data_history['email'] = $request->email;
        $data_history['project_category'] = $request->category;
        $data_history['location'] = $request->location;
        $data_history['sector'] = $request->sector;
        $data_history['rera_number'] = $request->rera_number;
        $data_history['contact_number'] = $request->contact_number['main'];
        $data_history['name_of_developers'] = $request->name_of_developer;
        $data_history['price_of_apartment'] = $request->price_of_apartment;
        $data_history['alternate_contact_number'] = $request->alternate_contact_number['altmain'];
        //$data_history['contact_number'] = $request->contact_number;
        $data_history['project_id'] = decrypt($id);
        $data_history['project_type'] = $ProjectType;
        $data_history['created_at'] = Carbon::now(); 
        $data_history['created_by'] = \Auth::user()->id; 
        $data_history['total_no_of_units'] = $request->total_no_of_units;
        $data_history['total_no_of_occupied_units'] = $request->total_no_of_occupied_units;
        $data_history['total_no_of_unoccupied_units'] = $request->total_no_of_unoccupied_units;
        $data_history['total_occupancy'] = $request->total_occupancy;
        $data_history['project_status'] = $request->project_status;
        $data_history['project_plan_details']= $request->project_plan_details ?? null;

        // return $data;

          if($data)
          {
            // if($request->team_name == Null && $request->team_email == Null && $request->team_phone_number == Null && $request->alternate_contact_number_team == Null && $request->date_of_birth == Null && $request->wedding_anniversary == Null && $request->designation == Null)
    //         {
                
    //         }else
    //         {
    //             $team = DB::table('teams')->where('project_id',decrypt($id))->first();
    //             $team = array();
    //             $team['team_name'] = $request->team_name;
    //             $team['team_email'] = $request->team_email;
    //             $team['team_phone_number'] = $request->team_phone_number;
    //             $team['alternate_contact_number_team'] = $request->alternate_contact_number_team;
    //             $team['date_of_birth'] = $request->date_of_birth;
    //             $team['wedding_anniversary'] = $request->wedding_anniversary;
    //             $team['designation'] = $request->designation; 
    //             $team['builder_id'] = $request->builder;
    //             DB::table('teams')->where('project_id',decrypt($id))->update($team);
    //         } 
          }

        $project = DB::table('projects')->where('id',decrypt($id))->first();
         
        $image = $request->project_image;

        // return $image;
         if($image)
         {
            //   $position = strpos($image, ';');
            //   $sub = substr($image,0,$position);
            //   $ext = explode('/',$sub)[0]; 
            //   $name = time().".".$ext;
            //   $img = Image::make($image)->resize(240,200);
            //   $upload_path = 'public/backend/employee/';
            //   $img_url = $upload_path.$name; 
            //   $success = $img->save($img_url);
            $imageupdate = $request->file('project_image');
            $filename =date('Ymdhi').'.'.$imageupdate->getClientOriginalExtension();
            $path = public_path().'/backend/project/';  
            $imageupdate->move($path, $filename); 

            //dd($imageupdate);
             
              if($imageupdate)
              {
                $data['project_image'] = $filename;
                $img = DB::table('projects')->where('id',decrypt($id))->first();
                $image_path = $img->project_image; 
                if($image_path == null)
                { 
                    $data_history['project_image'] = $filename;
                    $projectHistory =  DB::table('project_historys')->insert($data_history);
                    $user = DB::table('projects')->where('id',decrypt($id))->update($data);
                }
                else
                {
                    // $done = unlink($image_path);
                    $data_history['project_image'] = $filename;
                    $user = DB::table('projects')->where('id',decrypt($id))->update($data);
                    $projectHistory =  DB::table('project_historys')->insert($data_history);
                }
 
              }
              
              return redirect()->back()->with('success','Project Updated Successfully');
             
         }
         else
         {
             
             
            $oldphoto = DB::table('projects')->where('id',decrypt($id))->select('projects.project_image')->first(); 

            $team = DB::table('teams')->where('project_id',decrypt($id))->first();

            if($team == null) 
            {
                $team = array();
                $team['team_name'] = $request->team_name;
                $team['team_email'] = $request->team_email;
                $team['team_phone_number'] = $request->team_phone_number;
                $team['alternate_contact_number_team'] = $request->alternate_contact_number_team;
                $team['date_of_birth'] = $request->date_of_birth;
                $team['wedding_anniversary'] = $request->wedding_anniversary;
                $team['designation'] = $request->designation;
                $team['builder_id'] = $request->builder;
                $team['project_id'] = decrypt($id);
                DB::table('teams')->insert($team);
            }else
            { 
            
            //     $team = array();
            //     $team['team_name'] = $request->team_name;
            //     $team['team_email'] = $request->team_email;
            //     $team['team_phone_number'] = $request->team_phone_number;
            //     $team['alternate_contact_number_team'] = $request->alternate_contact_number_team;
            //     $team['date_of_birth'] = $request->date_of_birth;
            //     $team['wedding_anniversary'] = $request->wedding_anniversary;
            //     $team['designation'] = $request->designation; 
            // DB::table('teams')->where('project_id',decrypt($id))->update($team);
            
               if ($request->team_name == null && $request->team_email == null &&  $request->team_phone_number == null) {
                         //  dd("hello");
                            $team = DB::table('teams')->where('project_id',decrypt($id))->first(); 
                            $IsTeamMember= DB::table('teams')->where('id',$team->id)->delete();
                        } else {
                           // dd("hello123");
                            $team = array();
                            $team['team_name'] = $request->team_name;
                            $team['team_email'] = $request->team_email;
                            $team['team_phone_number'] = $request->team_phone_number;
                            $team['alternate_contact_number_team'] = $request->alternate_contact_number_team;
                            $team['date_of_birth'] = $request->date_of_birth;
                            $team['wedding_anniversary'] = $request->wedding_anniversary;
                            $team['designation'] = $request->designation; 
                            $team['builder_id'] = $request->builder;
                            DB::table('teams')->where('project_id',decrypt($id))->update($team);
                        }
            }
            
            
            $data['project_image'] = $oldphoto->project_image;
            $projectHistory =  DB::table('project_historys')->insert($data_history);
            $user = DB::table('projects')->where('id',decrypt($id))->update($data);
 
            return redirect()->back()->with('success','Project Updated Successfully');
         }
    }

    public function DeleteProject($id)
    {
        $project = DB::table('projects')->where('id', decrypt($id))->first();
        //return $project->id;
        $teams = DB::table('teams')
        ->where('project_id','LIKE', "%$id%")->get();

         if (count($teams) == 1) { 
            $teamProjectUpdate = DB::table('teams')->where('id',$teams->id)->update(
                [
                    'project_id' => null,
                ]
            );
         }else
         {
           // return "asd";
            foreach($teams as $key => $team)
            {
                $selects= explode(',',$team->project_id); 
             
                foreach($selects as $item)
                {
                   
                    if($item == decrypt($id)){
                      
                    }
                    else
                    {
                        $test[] = $item;  
                     
                    }
                    
                    
                } 

            

                $teamProjectUpdate = DB::table('teams')->where('id',$team->id)->update(
                    [
                        'project_id' => implode(',',$test ?? []),  
                    ]
                );

                if($teamProjectUpdate == true)
                {
                    $test = array();
 
                } 
                 
              //  return $teamProjectUpdate;
            }
         }

 
          
        $photo = $project->project_image; 
        DB::table('projects')->where('id', decrypt($id))->delete();
        return redirect()->route('project-index')->with('delete','Project Deleted Successfully');
        // if($photo)
        // {
        //     unlink($photo);
        //     DB::table('projects')->where('id', $id)->delete();
        //     return redirect()->route('project-index')->with('delete','Project Deleted Successfully');
            
        // }
        // else
        // {
        //     DB::table('projects')->where('id', $id)->delete();
        //     return redirect()->route('project-index')->with('delete','Project Deleted Successfully');
        // }

    }

    public function SearchProject(Request $request)
     {
 
        $projectTeams= Project::where('project_name','LIKE','%'.$request->search.'%')->get();
        $output = "";
        if(count($projectTeams)>0)
        {
            return view('pages.project.search',compact(['projectTeams']));
        }
        else
        {
            return redirect('project')->with('NoSearch',$output .="No data Found");
        }
        
    } 

       public function BuyerSeller($id, $bs_id)
    {
        
         
                 $projectTeams = DB::table('projects')->where('id', decrypt($id))->first();
		$buyerseller = DB::table('buyer_sellers')->where('id', $bs_id)->first();
		$projectIDDetails = decrypt($id);
		$bsID = $bs_id;


	    
		// $projectBuyerSellers = DB::table('leads') 
		//     ->where('buyer_seller', $bs_id)
		//     ->select('leads.lead_name', 'leads.id', 'leads.project_id','assign_employee_id','leads.buyer_seller') 
		//     ->get();

		$projectBuyerSeller = DB::table('leads')
		    ->groupBy('assign_employee_id')
		    ->where('buyer_seller', $bs_id)
		    ->where(function ($query) use ($projectIDDetails) {
		        $query->where('project_id', $projectIDDetails)
		            ->orWhere('existing_property', $projectIDDetails);
		    }) 
		    ->get();


	    // dd($projectBuyerSeller);
	    
		// $projectBuyerSeller = []; // Initialize the array here
		// $empID = []; //
		// $empNames = []; // Initialize the array here
		// $customerCount = 0;
	 
		// foreach ($projectBuyerSellers as $BuyerSeller) {
		//     $BuyerSellerFilter = explode(',', $BuyerSeller->project_id);
		//     foreach ($BuyerSellerFilter as  $BuyerSellerFilterValue) {
		//         if ($BuyerSellerFilterValue == $projectIDDetails) { 
		//             if (!in_array($BuyerSeller->assign_employee_id,$empID)) {
		//                  $empID[] = $BuyerSeller->assign_employee_id;
		//                  $projectBuyerSeller[] = $BuyerSeller;
		                   
		//             }
		             
		//         } 
		//     }
		// }

		//   dd($empID);
	    
		$buyersellername = $buyerseller->name;
		 
		return view('pages.project.emp-wise-customer-type', compact('projectTeams', 'projectBuyerSeller', 'buyersellername','projectIDDetails','bsID','buyerseller'));
       
    }


     public function ProjectBuilderList($id)
     {

        $project = DB::table('projects')->where('id', decrypt($id))
        ->select('projects.*')->first();
        
        $projectID =  decrypt($id);
        $buildersNameProjectWises = DB::table('teams')  
        ->join('designations','teams.designation','designations.id')
        ->where('project_id','LIKE',"%$project->id%")
        ->select('teams.*','designations.designation_name')
        ->get();
        
        $buildersNameProjectWise=[];

               foreach ($buildersNameProjectWises as $value) { 
              $selected = explode(',', $value->project_id);  
             	
              if (in_array($project->id,$selected) == true) {
                  foreach ($selected as $select) {
                    if ($select == $project->id) {
                         $buildersNameProjectWise[] = $value;
                    }
                     
                  }
              } 
          } 
          
        return view('pages.project.project-builder-list',compact('buildersNameProjectWise','project'));
     }

     public function ProjectHistory($id)
     {
        $projectID = decrypt($id);
        $projectDetails = DB::table('projects')->where('id', decrypt($id))->first();
        $projectHistorys = DB::table('project_historys')
        ->where('project_id',decrypt($id))
        ->latest('project_historys.created_at')->get();
        return  view('pages.project.project-history',compact(['projectHistorys','projectID','projectDetails']));
     }

     public function ProjectHistoryExport($id)
    {
       
         return Excel::download(new ExportProject($id),'Project-Details.xlsx');
    }
    
    public function ProjectExportReports()
    {
        return Excel::download(new ProjectExportReports(),'Project-Reports.xlsx');
    }


    public function ProjectSearch(Request $request)
    {
        $filterProject = $request->filter_project; 
        $searchTrue = 1;

        if ($filterProject == null) { 
           return redirect()->back();
        }
       
        if(is_null($filterProject))
            {
                $projectTeams = DB::table('projects')->latest()->get();
                return view('pages.project.index',compact('projectTeams')); 
            }
            else
            {
                $locations = DB::table('locations')->get();
                foreach($locations as $key => $locationFilter)
                {
                   $filter[$key] = $locationFilter->location;
                }

                //return $filter;
                if (\Str::contains($filterProject, $filter) ) {
                    $projectTeams = DB::table('projects')
                    ->where('location','LIKE','%'.$filterProject.'%')
                    ->latest()->get(); 

                     
                return view('pages.project.index',compact(['projectTeams','searchTrue']));
                }
                else
                {
                    $projectTeams = DB::table('projects')
                    ->where('project_category','LIKE','%'.$filterProject.'%')->latest()->get();
 

                    return view('pages.project.index',compact(['projectTeams','searchTrue']));
                }
                
                
            } 
    }
    
    
    public function customerType($id, $empID, $bs_id)
    {

          $projectTeams = DB::table('projects')->where('id', decrypt($id))->first();
        $buyerseller = DB::table('buyer_sellers')->where('id', $bs_id)->first();
        $projectIDDetails = decrypt($id); 
        $bsID = $bs_id;
 
    
        // $projectBuyerSellers = DB::table('leads')
        //     ->where('buyer_seller', $bs_id)
        //     ->where('assign_employee_id',$empID)
        //     ->select('leads.lead_name', 'leads.id', 'leads.project_id','assign_employee_id') 
        //     ->get();

            $projectBuyerSeller = DB::table('leads')
            ->where('assign_employee_id', $empID)
            ->where('buyer_seller', $bs_id)
            ->where(function ($query) use ($projectIDDetails) {
                $query->where('project_id', $projectIDDetails)
                    ->orWhere('existing_property', $projectIDDetails);
            })
            ->get();





        //  dd($CustomerTypeLeadCount);
    
        // $projectBuyerSeller = []; // Initialize the array here
        // $empID = []; //
        // $empNames = []; // Initialize the array here
 
        // foreach ($projectBuyerSellers as $BuyerSeller) {
        //     $BuyerSellerFilter = explode(',', $BuyerSeller->project_id);
        //     foreach ($BuyerSellerFilter as  $BuyerSellerFilterValue) {
        //         if ($BuyerSellerFilterValue == $projectIDDetails) { 
        //               $projectBuyerSeller[] = $BuyerSeller;
        //         }
        //     }
        // }
    
        $buyersellername = $buyerseller->name;
        
         return view('pages.project.buyer-seller', compact('projectTeams', 'projectBuyerSeller', 'buyersellername','projectIDDetails','bsID')); 
    }
}
