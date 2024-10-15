<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Team;
use App\Models\Project;
use Mail; 
use App\Mail\ProjectMail;
use App\Exports\CPBuilderExport;
use Maatwebsite\Excel\Facades\Excel;

class BuilderController extends Controller
{
    public function index()
    {
        $builders = DB::table('teams')
        ->join('designations','teams.designation','designations.id') 
        ->select('teams.*','designations.designation_name')
        ->latest()->get();
   
        return view('pages.builders.index',compact(['builders']));
    }

    public function CreateBuilder()
    {
        $designations = DB::table('designations')->get();
        $projects = DB::table('projects')->get();
        return view('pages.builders.create',compact(['projects','designations']));
    }

    public function BuilderCreate(Request $request)
    {
        $submit = $request['submit'];
        if($submit == 'submit')
        {
            $validate = $request->validate([ 
                'team_name' => 'required',
                // 'team_email' => 'required',
                'team_phone_number' => 'required',
               // 'project' => 'required',
                // 'designation' => 'required',
                //'name_of_developer' => 'required'

            ],
            [
                 'team_name.required' => "Sales Person Name can't be left blank.",
                //  'team_email.required' => 'Email field is required',
                 'team_phone_number.required' => "Enter Sales Person's correct number.", 
                // 'project.required' => 'Project field is required',
                 //'name_of_developer.required' => 'Select Name of Developer from dropdown.', 
            ]); 
            $projectMultipleid = implode(',', $request->project ?? []);

            //return $projectMultipleid;

                $team = new Team;
                $team->team_name = $request->team_name;
                $team->team_email = $request->team_email;
                $team->builder_cc_mail = $request->builder_cc_mail;
                $team->team_phone_number = $request->team_phone_number;
                $team->alternate_contact_number_team = $request->alternate_contact_number_team;
                $team->date_of_birth = $request->date_of_birth;
                $team->wedding_anniversary = $request->wedding_anniversary;
                $team->project_id = $projectMultipleid;
                $team->saler_person_alt_email = $request->saler_person_alt_email;
                $team->name_of_developer = $request->name_of_developer;
                $team->builder_id = $request->builder;
                $team->designation = $request->designation;
                $team->remark = $request->remark;
                
                // $builderCC1 =  explode(',',$request->builder_cc_mail); 
         
                // if ($builderCC1 == [""]) {
                //     $BuilderCC = null;
                // } else {
                //     $BuilderCC =  $builderCC1 ;
                // } 
                $BuilderCC[] = 'prakharsahay@homents.in';
                $BuilderCC[] ='pradeepmishra@homents.in';


                if($request->team_email == null)
                {

                }else
                {
                    $ProjectDetails = [
                        'title' => 'New Builder Created '. $request->team_name,
                        'body' => 'This is an automated email as you have been create a new project. There is no need to reply to this email.'
                    ]; 
                    Mail::to($request->team_email)
                            ->cc($BuilderCC) 
                            ->send(new ProjectMail($ProjectDetails));
                }
                
                $team->save();

                return redirect('builder')->with('success','Builder Created Successfully');
        }
        else
        {
            return "Error";
        }
    }

    public function EditBuilder($id ,Request $request)
    {
        $designations = DB::table('designations')->get();
        $projects = DB::table('projects')->get();
        $BuilderDetails = DB::table('teams')->where('id', $id)->first();
        return view('pages.builders.edit',compact(['designations','projects','BuilderDetails']));
    }

    public function updateBuilder($id ,Request $request)
    {
        $submit = $request['submit'];
        if($submit == 'submit')
        { 
            $validate = $request->validate([ 
                'team_name' => 'required',
                // 'team_email' => 'required',
                'team_phone_number' => 'required',
                // 'project' => 'required',
                // 'designation' => 'required',
                //'name_of_developer' => 'required' 
            ],
            [ 
                 'team_name.required' => 'Team name field is required',
                //  'team_email.required' => 'Email field is required',
                 'team_phone_number.required' => 'Contact number field is required', 
                // 'project.required' => 'Project Assign field is required',
                //  'designation.required' => 'Designation field is required', 
                //'name_of_developer.required' => 'Name of Developer field is required' 
            ]);

            $projectMultipleid = implode(',', $request->project ?? []);

            $team = array();
            $team['team_name'] = $request->team_name;
            $team['team_email'] = $request->team_email;
            $team['team_phone_number'] = $request->team_phone_number;
            $team['alternate_contact_number_team'] = $request->alternate_contact_number_team;
            $team['date_of_birth'] = $request->date_of_birth;
            $team['wedding_anniversary'] = $request->wedding_anniversary;
            $team['project_id'] = $projectMultipleid;
            $team['name_of_developer'] = $request->name_of_developer;
            $team['saler_person_alt_email'] = $request->saler_person_alt_email;
            // $team['builder_cc_mail'] = $request->builder_cc_mail;
            $team['builder_id'] = $request->builder;
            $team['designation'] = $request->designation; 
            $team['remark'] = $request->remark;
            //return $builder;
            
            $builder = DB::table('teams')->where('id',$id)->first();
            // $builderCC1 =  explode(',',$request->builder_cc_mail); 
         
            // if ($builderCC1 == [""]) {
            //     $BuilderCC = null;
            // } else {
            //     $BuilderCC =  $builderCC1 ;
            // } 
            $BuilderCC[] = 'prakharsahay@homents.in';
            $BuilderCC[] ='pradeepmishra@homents.in';

            

            if (($builder->team_email == null &&  $request->team_email != null) || ($builder->team_email != $request->team_email)) { 
             
            $ProjectDetails = [
                'title' => 'Builder Details Updated '. $request->team_name,
                'body' => 'This is an automated email as you have been create a new project. There is no need to reply to this email.'
            ]; 
            Mail::to($request->team_email)
                    ->cc($BuilderCC) 
                    ->send(new ProjectMail($ProjectDetails)); 

            
        } 
            $data = DB::table('teams')->where('id',$id)->update($team);

            return redirect('edit-builder/'.$id)->with('success','Builder Updated Successfully');
        }
        else
        {
            return "Error";
        } 
    }
    
     public function BuilderDetails($id)
    {
        $designations = DB::table('designations')->get();
        $projects = DB::table('projects')->get();
        $BuilderDetails = DB::table('teams')->where('id', $id)->first();
        return view('pages.builders.builder-info',compact(['designations','projects','BuilderDetails']));
    }

    public function BuilderDelete($id)
    {
        DB::table('teams')->where('id', $id)->delete();
        return redirect('builder')->with('delete','Builder Deleted Successfully');
    }

    public function SearchByNameOfDeloper(Request $request, $id)
     {
 
        $SearchByProjectName= Project::where('name_of_developers','LIKE','%'.$id.'%')
        ->select('projects.project_name','projects.id')->get();
        
         
        return $SearchByProjectName;

        if($SearchByProjectName == "")
        {
            return "";
        }
        else
        {
            return  $SearchByProjectName; 
        } 
    } 
    
    public function BuilderReportsDownload()
    {
        return Excel::download(new CPBuilderExport, 'Builder.xlsx');
    }

}
