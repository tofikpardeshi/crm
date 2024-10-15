<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Location;
use Maatwebsite\Excel\Facades\Excel; 
use App\Notifications\EmployeeNotification;
use App\Exports\ExportEmployees;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon; 
use Auth;
use Image;
use Hash;
use App\Exports\LoginExport;  
// use Stevebauman\Location\Facades\Location;
use DB;


class EmployeesController extends Controller
{
    public function index()
    {
        
        $employees = DB::table('employees')
        ->join('roles','employees.role_id','roles.id')
        ->join('users','users.id','employees.user_id') 
        ->select('employees.*','roles.name','users.email','users.login_status')->latest()->get();
       //return $employees;
        $relations = DB::table('relationship')->get();
        //return $relations;
        return view('pages.employees.index',compact(['employees','relations']));
    }


     public function createEmployeeView()
     {
        
        $roles = Role::all();
        $locations = Location::all();
        $relations = DB::table('relationship')->get();
        $bloobgroups = DB::table('blood_groups')->get();
        return view('pages.employees.create',compact(['roles','locations','bloobgroups','relations']));
     }


    public function createEmployee(Request $request)
    {
            
        // return $request;
        $submit = $request['submit'];
        if($submit == 'submit')
        {
            $validate = $request->validate([
              'employee_name' => 'required',
              'employeeID' => 'required',
             'employee_location' => 'required',
              'official_email' => 'required',
             'role_id' => 'required',
            //   'marriage_anniversary' => 'date|date_format:Y-m-d|before:'.now(),
              'main_password' => 'required|required_with:confirm_password|same:confirm_password|min:8',
              'confirm_password' => 'required|min:8',
             'blood_group' =>'required',
            //   'personal_phone_number' => 'required|digits:10',
              'pan_Number' => 'max:10', 
                   
            ],
            [   
                'employee_name.required' => "Employee Name can't be left blank.",
                'employeeID.required' => "Employee id field can't be left blank. Enter Correct Employee ID.",
                 'employee_location.required' => 'Select One or Multiple Locations for the Employee from Dropdown.',
                 'official_email.required' => 'Enter official email id of the user.',
                //  'official_email.regex' => 'Official email format is invalid please use this (@homents.in)',
                'main_password.required' => 'The password must be of at least eight characters, including at least one number and includes both lower and uppercase letters and special characters.',  
                  'role_id.required' => 'Select Role from the dropdown.',
                // 'personal_phone_number.required' => 'Personal phone number field is required',
                // 'pan_Number.required' => 'Employee id field is required',
                'blood_group.required' =>'Select Blood Group from the dropdown.',
            ]
        );


        if (preg_replace('/\s+/', '',$request->official_phone_number['official_cc']) != null) { 
            $officialcountryCode = explode(preg_replace('/\s+/', '',$request->official_phone_number['official_cc']), $request->official_phone_number['cc']); 
           
            $officialwithcountryCode = implode(', ',$officialcountryCode); 
            $officialfinalcountryCode = str_replace(',', '', $officialwithcountryCode);  
          }
          else
          { 

            $officialfinalcountryCode = null; 
          }
            
    
            if ($request->photo) {
                $position = strpos($request->photo, ';');
                $sub = substr($request->photo,0,$position);
                $ext = explode('/',$sub)[0];

                $name = time().".".$ext;
                $img = Image::make($request->photo)->resize(240,200);
                $upload_path = 'public/backend/employee/';
                $img_url = $upload_path.$name;
                $img->save($img_url);

            $user = new User;
            $user->name = $request->employee_name;
            $user->email = $request->official_email;
            $user->password = Hash::make($request->main_password);
            $user->profile_picture = $img_url;
            $user->roles_id =$request->role_id;
            $user->save();

            $employeeLocation = implode(',', $request->employee_location ?? []);
 
            $emp = new Employee;
            $emp->employee_name = $request->employee_name;
            $emp->full_emp_name = $request->full_emp_name;
            $emp->user_id =  $user->id; 
            $emp->role_id = $request->role_id;
            $emp->employeeID = $request->employeeID;
            $emp->personal_email = $request->personal_email;
            $emp->official_email = $request->official_email;
            $emp->emergeny_contact_number = $request->emergeny_contact_number;
            $emp->current_address = $request->current_address;
            $emp->permanent_address = $request->permanent_address;
            $emp->emp_country_code = $officialfinalcountryCode;
            $emp->official_phone_number = $request->official_phone_number['official_cc'];
            $emp->personal_phone_number = $request->personal_phone_number;
            $emp->department = $request->department;
            $emp->date_joining = $request->date_joining;
            $emp->leaving_date = $request->leaving_date;
            $emp->marriage_anniversary = $request->marriage_anniversary;
            $emp->relationship = $request->relationship;
            $emp->employee_location = $employeeLocation;
            $emp->lead_assignment = $request->lead_assignment;
            $emp->marriage_anniversary = $request->marriage_anniversary;
            $emp->addhar_number = $request->addhar_number;
            $emp->personal_phone_number = $request->personal_phone_number; 
            $emp->pan_Number = $request->pan_Number;
            $emp->education_background = $request->education_background;
            $emp->date_of_brith = $request->date_of_brith;
            $emp->blood_group = $request->blood_group;
            $emp->emergeny_contact_name = $request->emergeny_contact_name; 
            $emp->status = $request->status; 
            $emp->emplayees_photo = $img_url;
            $emp->save(); 

            $assignRole = array();
                     $assignRole['model_id'] = $user->id;
                     $assignRole['role_id'] = $request->role_id;
                     $assignRole['model_type'] = "App\Models\User";
                     DB::table('model_has_roles')->insert($assignRole);

            return redirect('create-employee-view')->with('success','Employee Created Successfully');
            }
            else
            {

                $user = new User;
                $user->name = $request->employee_name;
                $user->email = $request->official_email;
                $user->password = Hash::make($request->main_password);
                $user->roles_id =$request->role_id;
                $user->save();

               // return $user;
                  
               $employeeLocation = implode(',', $request->employee_location ?? []);
               

                $emp = new Employee;
                $emp->employee_name = $request->employee_name;
                $emp->full_emp_name = $request->full_emp_name;
                $emp->user_id =  $user->id; 
                $emp->role_id = $request->role_id;
                $emp->employeeID = $request->employeeID;
                $emp->official_email = $request->official_email;
                $emp->emergeny_contact_number = $request->emergeny_contact_number;
                $emp->current_address = $request->current_address;
                $emp->personal_email = $request->personal_email;
                $emp->permanent_address = $request->permanent_address;
                $emp->emp_country_code = $officialfinalcountryCode;
                $emp->official_phone_number = $request->official_phone_number['official_cc'];
                $emp->personal_phone_number = $request->personal_phone_number;
                $emp->department = $request->department;
                $emp->date_joining = $request->date_joining;
                $emp->leaving_date = $request->leaving_date;
                $emp->marriage_anniversary = $request->marriage_anniversary;
                $emp->relationship = $request->relationship;
                $emp->employee_location = $employeeLocation;
                $emp->lead_assignment = $request->lead_assignment;
                $emp->marriage_anniversary = $request->marriage_anniversary;
                $emp->addhar_number = $request->addhar_number;
                $emp->personal_phone_number = $request->personal_phone_number; 
                $emp->pan_Number = $request->pan_Number;
                $emp->education_background = $request->education_background;
                $emp->date_of_brith = $request->date_of_brith;
                $emp->blood_group = $request->blood_group;
                $emp->emergeny_contact_name = $request->emergeny_contact_name; 
                $emp->status = $request->status;  
                $emp->save(); 
 
                $assignRole = array();
                     $assignRole['model_id'] = $user->id;
                     $assignRole['role_id'] = $request->role_id;
                     $assignRole['model_type'] = "App\Models\User";
                     DB::table('model_has_roles')->insert($assignRole);
                

                return redirect('create-employee-view')->with('success','Employee Created Successfully');
            }

        }else{
            return "error";
        }     
    }

    public function EditEmployee($id)
    {
         
        $employee = DB::table('employees')
          ->join('users','users.id','employees.user_id')
        ->select('employees.*','users.email','users.login_status')
         ->where('employees.id', decrypt($id))->first();

        $EmpCountryCodeIso = DB::table('country')
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $employee->emp_country_code))
         ->first();
         ///return $employee;
        $roles = Role::all();
        $locations = Location::all();
        $bloobgroups = DB::table('blood_groups')->get();
        $relations = DB::table('relationship')->get();
        return view('pages.employees.edit',compact(['bloobgroups','employee','roles','locations','relations','EmpCountryCodeIso']));
    }
    
     public function EmployeeDetails( $id)
    {

       
        $employee = DB::table('employees')
          ->join('users','users.id','employees.user_id')
        ->select('employees.*','users.email','users.login_status')
         ->where('employees.id', decrypt($id))->first();
        //dd($employee);
        $roles = Role::all();
        $locations = Location::all();
        $bloobgroups = DB::table('blood_groups')->get();
        $relations = DB::table('relationship')->get();
        return view('pages.employees.employee-details',compact(['bloobgroups','employee','roles','locations','relations']));
    }

    public function updateEmployee(Request $request, $id)
    {
         
       /// return $request->employee_location;
        $validate = $request->validate([
            'employee_name' => 'required',
            'employeeID' => 'required',
            'employee_location' => 'required',
            // 'date_of_brith' => 'date|date_format:Y-m-d|before:'.now()->subYears(18)->toDateString(),
            // 'marriage_anniversary' => 'date|date_format:Y-m-d|before:'.now(),
            'official_email' => 'required',
            'password' => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:8',
            // 'official_phone_number' =>'required|digits:10',
            // 'personal_phone_number' => 'required|digits:10',
            'pan_Number' => 'max:10',
            'blood_group' =>'required', 
                 
        ],
        [
            // 'official_email.regex' => 'Official email format is invalid please use this (@homents.in)',
        ]
    );

   

     //  if (preg_replace('/\s+/', '',$request->official_phone_number['official_cc']) != null) { 
    //     $officialcountryCode = explode(preg_replace('/\s+/', '',$request->official_phone_number['official_cc']), $request->official_phone_number['cc']);  
    //     $officialwithcountryCode = implode(', ',$officialcountryCode); 
    //     $officialfinalcountryCode = str_replace(',', '', $officialwithcountryCode); 
    //   }
    //   else
    //   { 

    //     $officialfinalcountryCode = null; 
    //   }

        if($request->organisationLeave == 1)
        {
            $commonPooll = array();
            $commonPooll['common_pool_status'] = 1;
            $leadsMoveCommonPool = DB::table('leads')->where('assign_employee_id',$id)->update($commonPooll);
            
        }
       //return $request;
       
       
       $employeeLocation = implode(',', $request->employee_location ?? []);
      
        $data = array(); 
        $data['employee_name'] = $request->employee_name;
        $data['full_emp_name'] = $request->full_emp_name;
        $data['employeeID'] = $request->employeeID; 
        $data['official_email'] = $request->official_email;
        $data['personal_email'] = $request->personal_email;
        $data['role_id'] = $request->role_id;
        $data['personal_phone_number'] = $request->personal_phone_number; 
        $data['emp_country_code'] = $request->official_phone_number['emp_country_code'] ?? null;
        $data['official_phone_number'] =preg_replace('/\s+/', '',$request->official_phone_number['official_cc']) ?? null;
        $data['pan_Number'] = $request->pan_Number;
        $data['emergeny_contact_number'] = $request->emergeny_contact_number;
        $data['education_background'] = $request->education_background;
        $data['department'] = $request->department;
        $data['date_of_brith'] = $request->date_of_brith;
        $data['blood_group'] = $request->blood_group;
        $data['emergeny_contact_name'] = $request->emergeny_contact_name;
        $data['addhar_number'] = $request->addhar_number;
        $data['date_joining'] = $request->date_joining; 
        $data['leaving_date'] = $request->leaving_date;
        $data['marriage_anniversary'] = $request->marriage_anniversary; 
        $data['relationship'] = $request->relationship;
        $data['current_address'] = $request->current_address;  
        $data['employee_location'] =  $employeeLocation;
        $data['permanent_address'] = $request->permanent_address;
        $data['lead_assignment']  = $request->lead_assignment; 
        $data['organisation_leave'] = $request->organisationLeave ?? 0;

      
        if($data['organisation_leave'] == 1)
        {
             
              $currentDateTime = Carbon::now();
            $futureDateTime = $currentDateTime->addHours(2);
            
            $commonPooll = array();
            $commonPooll['common_pool_status'] = 1;
            $commonPooll['lead_status'] = 16;
            $commonPooll['updated_at'] = $futureDateTime;
            
            $leadsMoveCommonPool = DB::table('leads')->where('assign_employee_id',$id)->update($commonPooll);
            
        }
        $image = $request->photo;
        
        // return $image;
         if($image)
         {
              //return "Hello";
              $position = strpos($image, ';');
              $sub = substr($image,0,$position);
              $ext = explode('/',$sub)[0]; 
              $name = time().".".$ext;
              $img = Image::make($image)->resize(240,200);
              $upload_path = 'public/backend/employee/';
              $img_url = $upload_path.$name; 
              $success = $img->save($img_url);
             
              if($success)
              {
                $data['emplayees_photo'] = $img_url;
                $img = DB::table('employees')->where('id',$id)->first();
                if($img->emplayees_photo == null )
                {
                   
                    $user['name'] = $request->employee_name;
                    $user['email'] = $request->official_email; 
                    $user['roles_id'] = $request->role_id;
                    $user['profile_picture'] =  $img_url;
                    if ($request->organisationLeave == 1) {
                        $user['login_status']  = 0;
                    }
                    else
                    {
                        $user['login_status']  = 1;
                    } 
                    // dd($img->user_id);
                    $userData = DB::table('users')->where('id',$img->user_id)->update($user);

                    // DB::table('model_has_roles')->where('model_id',$id)->delete();
                     
                    $user = DB::table('employees')->where('id',$id)->update($data);
                    $oldphoto = DB::table('employees')->where('id',$id)->first();

                    // $assignRole = array();
                    //  $assignRole['model_id'] = $oldphoto->user_id;
                    //  $assignRole['role_id'] = $request->role_id;
                    //  $assignRole['model_type'] = "App\Models\User";
                    //  DB::table('model_has_roles')->update($assignRole);
                }else
                {
                    $image_path = $img->emplayees_photo;

                    $user['name'] = $request->employee_name;
                    $user['email'] = $request->official_email; 
                    $user['roles_id'] = $request->role_id;
                    $user['profile_picture'] =  $img_url;
                    if ($request->organisationLeave == 1) {
                        $user['login_status']  = 0;
                    }
                    else
                    {
                        $user['login_status']  = 1;
                    } 
                    // dd($img->user_id);
                    $userData = DB::table('users')->where('id',$img->user_id)->update($user);

                    // DB::table('model_has_roles')->where('model_id',$id)->delete();
                    $oldphoto = DB::table('employees')->where('id',$id)->first();
                    // $assignRole = array();
                    // $assignRole['model_id'] = $oldphoto->user_id;
                    // $assignRole['role_id'] = $request->role_id;
                    // $assignRole['model_type'] = "App\Models\User";
                    // DB::table('model_has_roles')->insert($assignRole);
                    //return $userData;

                //    $done = unlink($image_path);
                    $user = DB::table('employees')->where('id',$id)->update($data);
                }
                 
                
              }
              return redirect('edit-employee/'.encrypt($id))->with('success','Employee Updated Successfully');
         }
         else
         {
            //  return  "Hello";

            $oldphoto = DB::table('employees')->where('id',$id)->first();
             

             $data['emplayees_photo'] = $oldphoto->emplayees_photo;

         
            DB::table('employees')->where('id',$id)->update($data);
 
                    $user['name'] = $request->employee_name;
                    $user['email'] = $request->official_email; 
                    $user['roles_id'] = $request->role_id; 
                    if ($request->organisationLeave == 1) {
                        $user['login_status']  = 0;
                    }
                    else
                    {
                        $user['login_status']  = 1;
                    } 
                    $userData = DB::table('users')->where('id',$oldphoto->user_id)->update($user); 
                       DB::table('model_has_roles')->where('model_id',$oldphoto->user_id)->delete();

         
                     $assignRole = array();
                     $assignRole['model_id'] = $oldphoto->user_id;
                     $assignRole['role_id'] = $request->role_id;
                     $assignRole['model_type'] = "App\Models\User";
                     DB::table('model_has_roles')->insert($assignRole);


            return redirect('edit-employee/'.encrypt($id))->with('success','Employee Updated Successfully');
         }
    }

    public function DeleteEmployee($id)
    {
        // return $id;
        $employee = DB::table('employees')->where('id', $id)->first(); 
        $photo = $employee->emplayees_photo; 

        //return $photo;
        if($photo)
        {
            unlink($photo);
            DB::table('employees')->where('id', $id)->delete();
            DB::table('users')->where('id', $employee->user_id)->delete();
            return redirect('employees')->with('success','Employee Deleted Successfully');
        }
        else
        {
            DB::table('employees')->where('id', $id)->delete();
            DB::table('users')->where('id', $employee->user_id)->delete();
            return redirect('employees')->with('success','Employee Deleted Successfully');
        }
 
    } 

    public function SearchEmployee(Request $request)
     {
 
        $employees= Employee::where('employee_name','LIKE','%'.$request->search.'%') 
            ->join('roles','employees.role_id','roles.id')
            ->join('users','users.id','employees.user_id')
            ->select('employees.*','roles.name','users.email')->get();
        $output = "";
        if(count($employees)>0)
        {
            
            return view('pages.employees.search',compact(['employees']));
        }
        else
        {
            return redirect('employees')->with('NoSearch',$output .="No data Found");
        }
        
    } 

     
    
    
     public function EmployeeExport()
    {
       
         return Excel::download(new ExportEmployees(),'Empoloyee-Reports.xlsx');
    }


    public function EmployeeBrithday()
    {
        // return "test";
        $next_days = now()->addDays(3); 
        // dd($next_days);
        // $employees = DB::table('employees')->whereBetween('date_of_brith', array($next_days->month))->get();
        // ->whereMonth('date_of_brith', $next_days->month)
        // ->whereDay('date_of_brith', '>=' ,$next_days->day)
        // ->get();

        $employees = Employee::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_brith) AND DAYOFYEAR(curdate()) + 3 >=  dayofyear(date_of_brith)')
                        ->orderByRaw('DAYOFYEAR(date_of_brith)')
                        ->get();

         $allNotification = DB::table('employees')->get(); 
        //dd($allNotification);
        foreach ($employees as $employee) {
            $employeeN = DB::table('employees')->where('user_id',$employee->user_id)->first();  
            // dd($employeeN->employee_name); 
            $employeeNotification = [ 
                'employee_name' => $employeeN->employee_name,
                'employeeID' => $employee->id
            ];  
            
             Notification::sendNow($allNotification, new EmployeeNotification($employeeNotification));  
            
        } 
    }

    public function EmployeeLocation(Request $request)
    {
        $employees = DB::table('employees')
        ->join('roles','employees.role_id','roles.id')
        ->join('users','users.id','employees.user_id') 
        ->select('employees.*','roles.name','users.email','users.login_status')->latest()->get();
       //return $employees;
        $relations = DB::table('relationship')->get();
        //return $relations;
        return view('pages.employees.login-location',compact(['employees','relations'])); 

    }
    
    
     public function LoginReportsDownload()
     {
         return Excel::download(new LoginExport, 'LoginReports.xlsx');
     }

}
