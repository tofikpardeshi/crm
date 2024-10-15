<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeveloperExport;

class DeveloperControllers extends Controller
{
    public function index()
    {
        $Developers = DB::table('name_of_developers')->orderBy('id','DESC')->get();  
        return view('pages.developer.index',compact(['Developers']));
    }

    public function CreateDeveloper(Request $request)
     {
         $submit = $request['submit'];
         if($submit == 'submit')
         {
            // return $request->nameOfDev;
            $validation = $request->validate([
               'nameOfDev' => 'required | unique:name_of_developers,name_of_developer',
            ],
            [
               'nameOfDev.required' => "Name of developers can't be left blank.",
            ]);

            $nameOfDev = DB::table('name_of_developers')->insert(
               [
                  "name_of_developer" => $request->nameOfDev,
                  'created_at' =>  Carbon::now(),
                  'updated_at' =>  Carbon::now()
               ]
            );

            return redirect('developer/index')->with('success','Name of developer Created Successfully');
         }
         else
         {
            return "Error";
         }
     }

    public function DeveloperUpdate(Request $request)
     {
      $submit = $request['submit'];
      if($submit == 'submit')
      {
         
         $validation = $request->validate([
            'nameOfDeveloper' => 'required',
         ]);

         $locationupdate = array();
         $locationUpdate['name_of_developer'] =  $request->nameOfDeveloper;
         $locationUpdate['updated_at'] =  Carbon::now();

         DB::table('name_of_developers')
         ->where('id',$request->updatenameOfDeveloperID)
         ->update($locationUpdate);

         return redirect('developer/index')->with('success','Name Of Developers Updated');

      }
      else
      {
         return "Error";
      }
     }

     public function DeveloperDeletes(Request $request,$id)
     {
        
         DB::table('name_of_developers')->where('id',$id)->delete();

         return redirect('developer/index')->with('success','Name Of Developers Deleted');

      
     }


     public function download()
     {
         return Excel::download(new DeveloperExport, 'developers.xlsx');
     }
}

