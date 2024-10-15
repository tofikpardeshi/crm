<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Employee;
use DB;
use Auth;
use Hash;

class CrmToolsControllers extends Controller
{
    public function index()
    {
        $crmToolbars = DB::table('crm_toolbars')
        ->select('id as snumber','projects_id', 'website', 'email', 'password', 'created_by', 'created_at', 'updated_at')
        ->get();

        return view('pages.crmtoolbox.index',compact('crmToolbars'));
    }

    public function CreateIndex(Request $request)
    { 
        $projectTypes = DB::table('projects')->get();
        return view('pages.crmtoolbox.create',compact('projectTypes'));
    }


    public function CreateCrmTool(Request $request)
    {   
        $CreateCrmTool =  DB::table('crm_toolbars')->insert([ 
                'projects_id' => $request->project_type,
                'website' => $request->website ?? null,
                'email' => $request->email ?? null,
                'password' => $request->password ?? null,
                'created_by' => Auth::user()->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]); 

        \Session::flash('success', 'Record inserted successfully!'); 
        return redirect()->route('crm-toolbar-create'); 
    }

    public function editCrmTool($id)
    {
        // Find the record to be edited by its ID
        $crmToolbar = DB::table('crm_toolbars')->find($id);
        $projectTypes = DB::table('projects')->get(); 
        // Pass the data to the edit view
        return view('pages.crmtoolbox.edit', compact(['crmToolbar','projectTypes']));
    }

    public function UpdateCrmTollProject(Request $request,$id)
    { 
         
        $CreateCrmTool =  DB::table('crm_toolbars')
        ->where('id',$id)->update([ 
            'projects_id' => $request->project_type,
            'website' => $request->website ?? null,
            'email' => $request->email ?? null,
            'password' => $request->password ?? null,
            'created_by' => Auth::user()->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);  
        
        \Session::flash('success', 'Record Updated successfully!'); 
        return redirect()->route('crm-toolbar'); 
    }
    
    public function CrmTollDelete(Request $request,$id)
    {
       
        DB::table('crm_toolbars')->where('id',$id)->delete();

        return redirect('crm-toolbar')->with('success','CRM Toolbar Project Deleted Successfully');

     
    }
}

