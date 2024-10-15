<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Setting;
// use App\Models\Location;
use App\Models\Employee;
use Image;
use Spatie\Permission\Models\Role;
use Validation;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
use Auth;
use Hash;
use Session;
use DB; 
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Exports\LocationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan; 
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupEmail;
use App\Exports\TrailLogsReports;
use App\Exports\EmployeeProductivityReports;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Mail\MTDSendMail;
use App\Mail\MTDEmployeeSendMail;
use App\Mail\MTDMonthlyRepot;
use App\Mail\MTDMonthlyEmployeeReport;

class AdminController extends Controller
{
    
     public function login(Request $request)
     {  
  
        $submit = $request['submit'];
        if($submit == 'submit')
        {
                  $validate = $request->validate([
                  'email' => 'required',
                  'password' => 'required|min:8',
              ]);
              


             $remember_me = $request->has('remember_me') ? true : false; 
               
               if(Auth::attempt($request->only('email','password'), $remember_me))
              { 
              	
               //	Log Delete
               $DeleteLoginUserIPAfter =  date("Y-m-d H:i:s", strtotime("-30 days"));
               $userIpLogDelete = DB::table('log')->where('created_at','<',$DeleteLoginUserIPAfter)->delete();
               
                //	Log Delete
               
               $ip = request()->ip(); 
               
                  $position = "1.39.235.217"; 
               //$position = Location::get($ip);
               $sessionToken = \Str::random(40);
               
               DB::table('users')->where('id',Auth::user()->id)->update(['session_token' => $sessionToken]);

               $address = isset($position->cityName) .'-'. isset($position->regionName);
               
               $log['ip_address']  = $position ;
               $log['address']  = $address ?? null;
               $log['user_id'] = Auth::user()->id;
               $log['action'] = 'login';
               $log['session_token'] = $sessionToken;
               $log['created_at'] =  Carbon::now(); 
               DB::table('log')->insert($log);

                return redirect('/leads');
              }else
              {
                return redirect('/')->with('error','invalid Credentials');
              }
        }

         return view('auth.login');
     }

     public function loginPage()
     {
         return view('auth.login');
     }

     public function forgotPassword()
     {
        return view('auth.forgot');
     }

     public function ForgetPasswordStore(Request $request)
     {

      $submit = $request['submit'];
        if($submit == 'submit')
        {
           
         $request->validate([
            'email' => 'required|email|exists:users',
         ]);

         $token = Str::random(64);
         DB::table('password_resets')->insert([
               'email' => $request->email,
               'token' => $token,
               'created_at' => Carbon::now()
         ]); 

         Mail::send('auth.forget-password-email', ['token' => $token], function($message) use($request){
               $message->to($request->email);
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
               $message->subject('Password Reset Request for Homents CRM');
         });

            return back()->with('message', 'We have emailed your password reset link!');
        }
        else
        {
         return "Error";
        } 
            
     }

     public  function ForgetPasswordSend($token)
     {
        return view('auth.forget-password-send',['token' => $token]);
     }

     public function ResetPasswordStore(Request $request)
     {
       
      $request->validate([
         'email' => 'required|email|exists:users',
         'password' => 'required|min:8|confirmed',
         'password_confirmation' => 'required'
     ]);

     $update = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

   //   return $update;

     if(!$update){
         return back()->withInput()->with('error', 'Invalid token!');
     }

     $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

     // Delete password_resets record
     DB::table('password_resets')->where(['email'=> $request->email])->delete();

     return redirect('/')->with('message', 'Your password has been successfully changed!');

     }

     public function logout()
     {
        //\Session::flush();
        //Auth::logout();
       // return redirect('/');
       
        $sessionToken = Auth::user()->session_token;

         $logoutUserDetails = DB::table('log')
         ->where('session_token', $sessionToken)
         ->where('user_id', Auth::user()->id)
         ->update([
            'updated_at' => Carbon::now(),
            'action' => 'logout',
         ]);

         // Retrieve the login time
         // $loginLog = DB::table('log')
         //    ->where('user_id', Auth::user()->id)
         //    ->where('session_token', $sessionToken)
         //    ->where('action', 'login')
         //     ->orderBy('created_at', 'desc')
         //    ->first();

         // dd($loginLog);
   
         // if ($loginLog) {
         //    $loginTime = Carbon::parse($loginLog->created_at);
            
            // Log the logout time
            // $log = [
            //    'user_id' => Auth::user()->id,
            //    'action' => 'logout',
            //    'session_token' => $sessionToken,
            //    'updated_at' => Carbon::now(),
            // ];
            // DB::table('log')->insert($log);
             
            // Calculate the duration (time difference) between login and logout
            // $logoutTime = Carbon::now();
            // $durationInSeconds = $loginTime->diffInMinutes($logoutTime);
   
            //   dd($durationInSeconds);
            // You can now use $durationInSeconds to record the session duration or perform any other actions.
   
            // Logout the user
            \Session::flush();
            Artisan::call('cache:clear');
            Auth::logout(); 
            
            // Redirect to the desired page after logout
            return redirect('/');
         // } else { 
         //    // Handle the case where the login log is not found
         //    Auth::logout();
         //    return redirect('/');
         // }
     }

     public function dashboard(Request $request)
     { 
      

      // return $date;

          $leads = DB::table('leads')
       ->join('employees','employees.id','leads.assign_employee_id') 
        ->select('leads.*','employees.employee_name')
        ->where('leads.lead_status', '!=', 8)
         ->where('leads.lead_status', '!=', 9)
         ->where('leads.lead_status', '!=', 10)
         ->where('leads.lead_status', '!=', 11)
         ->where('leads.lead_status', '!=', 12)
         ->where('leads.lead_status', '!=', 14)
         ->where('leads.lead_status', '!=', 16)
         ->where('common_pool_status', 0)->latest()->paginate(10); 

       $leadsCount = DB::table('leads')->count(); 
      
       $dashboards = DB::table('locations')->get();
       $dashboard_filters = DB::table('dashboard_filter')->get();
 
       foreach($dashboards as $dashboard)
       {
          
           
          //   Dashboard Filter
       
            $filter = $request->filter_data; 
            if(is_null($filter))
            {
               $filter = "Weekly";
            }
            else
            {
               $filter = $request->filter_data;
            } 

            if ($filter == 'Weekly') {
               $date = \Carbon\Carbon::today()->subDays(7);
               $leads_location = DB::table('leads')
               ->where('location_of_leads',$dashboard->id)
               ->where('created_at', '>=', $date)->count(); 
               if($leads_location == null)
               {
                  $progessBar = 0; 
               }
               else
               {
                  $progessBar = $leads_location/$leadsCount * 100; 
               }
               $data = array(
                  'location_id' => $dashboard->id,
                  'location' => $dashboard->location,
                  'location_of_leads' => $leads_location,
                  'leads_count' => round($progessBar,2),
                  'loaction_id' => $dashboard->id,  
               );  
               $location_data[] = $data;  
            } else if ($filter == 'Monthly') {
               $date = \Carbon\Carbon::today()->subDays(30);
               $leads_location = DB::table('leads')
               ->where('location_of_leads',$dashboard->id)
               ->where('created_at', '>=', $date)->count(); 
               if($leads_location == null)
               {
                  $progessBar = 0; 
               }
               else
               {
                  $progessBar = $leads_location/$leadsCount * 100; 
               } 
               $data = array(
                  'location_id' => $dashboard->id,
                  'location' => $dashboard->location,
                  'location_of_leads' => $leads_location,
                  'leads_count' => round($progessBar,2),
                  'loaction_id' => $dashboard->id,  
               );  
               $location_data[] = $data; 
            } else if ($filter == 'Quarterly') {
               $date = \Carbon\Carbon::today()->subDays(120);
               $leads_location = DB::table('leads')
               ->where('location_of_leads',$dashboard->id)
               ->where('created_at', '>=', $date)->count(); 
               if($leads_location == null)
               {
                  $progessBar = 0; 
               }
               else
               {
                  $progessBar = $leads_location/$leadsCount * 100; 
               } 
               $data = array(
                  'location_id' => $dashboard->id,
                  'location' => $dashboard->location,
                  'location_of_leads' => $leads_location,
                  'leads_count' => round($progessBar,2),
                  'loaction_id' => $dashboard->id,  
               );  
               $location_data[] = $data;  
            } else if ($filter == 'Yearly') {
               $date = \Carbon\Carbon::today()->subDays(365); 
               $leads_location = DB::table('leads')
               ->where('location_of_leads',$dashboard->id)
               ->where('created_at', '>=', $date)->count();  
               if($leads_location == null)
               {
                  $progessBar = 0; 
               }
               else
               {
                  $progessBar = $leads_location/$leadsCount * 100; 
               }
               $data = array(
                  'location_id' => $dashboard->id,
                  'location' => $dashboard->location,
                  'location_of_leads' => $leads_location,
                  'leads_count' => round($progessBar,2),
                  'loaction_id' => $dashboard->id,  
               );  
               $location_data[] = $data; 
            } else if ($filter == 'Life time') { 
               $date = \Carbon\Carbon::today()->subDays(730);
               $leads_location = DB::table('leads')
               ->where('location_of_leads',$dashboard->id)
               ->where('created_at', '>=', $date)->count(); 
               if($leads_location == null)
               {
                  $progessBar = 0; 
               }
               else
               {
                  $progessBar = $leads_location/$leadsCount * 100; 
               }
               $data = array(
                  'location_id' => $dashboard->id,
                  'location' => $dashboard->location,
                  'location_of_leads' => $leads_location,
                  'leads_count' => round($progessBar,2),
                  'loaction_id' => $dashboard->id,  
               );  


               // return $data;
               $location_data[] = $data;  

               
            } 
            else {
               $date = \Carbon\Carbon::today()->subDays(30);
            }
      
            //   Dashboard Filter End   
       }   

      //   return $location_data[0]['location_id'];


        return view('dashboard',compact('leads','location_data','date','dashboard_filters','filter'));
     }
     
     
        public function EmployeeDashboard(Request $request)
     { 
       
       $employeeDeshboard = DB::table('employees')->get();  

       foreach($employeeDeshboard as $employeeDash)
       {
         $empLead = DB::table('leads')->where('assign_employee_id',$employeeDash->id)->first();
         $EmpLeadCount = DB::table('leads')
         ->where('assign_employee_id',$employeeDash->id)
         ->where('common_pool_status', '!=' ,1)
         ->where('lead_status', '!=' ,14)
         ->where('lead_status', '!=' ,16) 
         ->where('lead_status', '!=' ,8) 
         ->where('lead_status', '!=' ,9) 
         ->where('lead_status', '!=' ,10) 
         ->where('lead_status', '!=' ,11) 
         ->where('lead_status', '!=' ,12)->count();
 
         $data = array( 
            'leadCount' => $EmpLeadCount, 
            'emp_name' => $employeeDash->employee_name,
            'emp_location' => $employeeDash->employee_location,
            'emaployee_id' => $employeeDash->id
             );  

            $employeeData[] = $data; 
       }

      //  dd($employeeData);
       
        return view('pages.admin.employee-dashboard',compact('employeeData'));
     }


     public function EmployeeLocationWiseLead(Request $request,$id)
     {
       
      $emp = DB::table('employees')->where('id', decrypt($id))->first();
      
      $employeeLocation = explode(',', $emp->employee_location);
      
      $locationWise = DB::table('locations')
    ->join('employees', function ($join) use ($employeeLocation, $id) {
        $join->on('employees.employee_location', 'LIKE', DB::raw('CONCAT("%", locations.id, "%")'))
            ->where('employees.id', decrypt($id));
    })
    ->whereIn('locations.id', $employeeLocation)
    ->select('locations.location', 'locations.id', 'employees.id as eid', 'employees.employee_name')
    ->get();

  
      //  dd($locationWise);
      return view('pages.admin.emp-location-wise-lead', compact(['emp','locationWise']));

     }

     public function FilterData(Request $request){

      $submit = $request['submit'];  
         if($submit == 'submit')
         {
            return redirect()->back();
         }
         else
         {
            return "Error";
         }
     }

     public function adminProfie()
     {
      // if (Auth::user()->id == 1) {
      //    $employeeData = User::where('id',Auth::user()->id)->first();
      //    if(is_null($employeeData))
      //   {
      //        //return "Hello";
      //    $employeeData = array(
      //       'addhar_number' => '', 
      //       'pan_Number' => '',
      //       'date_of_brith' => '',
      //       'current_address' => '',
      //       'education_background' => '',
      //       'blood_group' => '',
      //       'emergeny_contact_name' => '',
      //       'personal_email' => '',
      //       'profile_pic' => ''
      //    );
         
      //    return view('pages.admin.profile',compact('employeeData'));
      //   }
      //   else
      //   {
      //        //return "By";
             
      //    return view('pages.admin.profile',compact('employeeData'));
      //   }
      // } else {
      //    $employeeData = Employee::where('user_id',Auth::user()->id)->first(); 
      //    //return $employeeDatas;
          
      //   if(is_null($employeeData))
      //   {
      //        //return "Hello";
      //    $employeeData = array(
      //       'addhar_number' => '', 
      //       'pan_Number' => '',
      //       'date_of_brith' => '',
      //       'current_address' => '',
      //       'education_background' => '',
      //       'blood_group' => '',
      //       'emergeny_contact_name' => '',
      //       'personal_email' => '',
      //       'profile_pic' => ''
      //    );
         
      //    return view('pages.admin.profile',compact('employeeData'));
      //   }
      //   else
      //   {
      //        //return "By";
             
      //    return view('pages.admin.profile',compact('employeeData'));
      //   }
      // }

      $employee = User::Join('employees','users.id','employees.user_id')
      ->where('users.id',Auth::user()->id)->first();
      // $employee = DB::table('employees')
      //     ->join('users','users.id','employees.user_id')
      //   ->select('employees.*','users.email','users.login_status')
      //    ->where('employees.id', Auth::user()->id)->first();
        //dd($employee);
        $roles = Role::all(); 
        $bloobgroups = DB::table('blood_groups')->get();
        $relations = DB::table('relationship')->get();
        return view('pages.admin.profile',compact(['bloobgroups','employee','roles','relations']));
       
     }

     public function adminSetting()
     {

        $roles =  DB::table('roles')->get();
        $locations =  DB::table('locations')->get();
        $settings = DB::table('settings')->where('user_id',Auth::user()->id)->first();
        
        return view('pages.admin.setting',compact(['locations','roles','settings']));
         
      //   if( $settings == null)
      //   { 
      //      return view('pages.admin.setting', compact(['locations','roles','settings'])); 
      //   }
      //   else
      //   {
      //        return view('pages.admin.setting',compact(['locations','roles','settings']));
      //   }
         
       
     }

     public function submitResetPasswordForm(Request $request)
      {

         $submit = $request['submit'];
         if($submit == 'submit')
         {
            $request->validate([
               'old_password' => 'required',
               'new_password' => 'required|string|min:6|max:100',
               'confirm_password' => 'required|same:new_password',
               ],
               [
                  'old_password.required' => 'Enter your old/existing password.',
                  'new_password.required' => 'Enter the new password you want to set.',
                  'confirm_password.required' => 'Confirm the new password you want to set.',
               ]
         );
            
            $user = Auth::user();
            
            if (!Hash::check($request->old_password, $user->password)) {
              //return "password not matched";
               return redirect('/admin-profile')->with('error', 'Current password does not match!');
            }  
            $user->password =  Hash::make($request->new_password);
            $user->password =  Hash::make($request->confirm_password);
            
            $user->save();
            \Session::flush();
            return redirect('/')->with('success', 'Password Change Successfully');
        }else
        {
           return "error";
        } 
      } 

      public function updateProfile(Request $request)
      {
         $submit = $request['submit'];
         if($submit == 'submit')
         {
            if (Auth::user()->id == 1) 
            {
               $userAdmin = array(); 
               $userAdmin['name'] = $request->name;
               $userAdmin['current_address'] = $request->current_address; 
               $userAdmin['pan_Number'] = $request->pan_Number;
               $userAdmin['education_background'] = $request->education_background;
               $userAdmin['date_of_brith'] = $request->date_of_brith;
               $userAdmin['blood_group'] = $request->blood_group;
               $userAdmin['emergeny_contact_name'] = $request->emergeny_contact_name;
               $userAdmin['personal_email'] = $request->personal_email; 
               $userAdmin['addhar_number'] = $request->addhar_number; 
                
               $image = $request->profile_pic;
 
               if($image)
               {
             
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
                   
                $img = DB::table('users')->where('id',Auth::user()->id)->first();
                if($img->profile_picture == null)
                {
                //  return "Hello";
                  $image_path = $img->profile_picture;
                  $userAdmin['profile_picture'] = $img_url;
                  $user1 = DB::table('users')->where('id',Auth::user()->id)->update($userAdmin); 
                  return redirect('admin-profile')->with('success','Profile Updated Successfully');
                }
                else
                {
                  //return "BY";
                  $image_path = $img->profile_picture;
                  $userAdmin['profile_picture'] = $img_url;
                  $done = unlink($image_path);
                  $user1 = DB::table('users')->where('id',Auth::user()->id)->update($userAdmin); 
                  return redirect('admin-profile')->with('success','Profile Updated Successfully');
                }
                
              }
               }  
         }
         else
         {
            $user_id = Auth::user()->id;
            $user['email'] = $request->email;
            $user['name'] = $request->name; 
            $userData = DB::table('users')->where('id',$user_id)->update($user);
              DB::table('employees')->where('user_id',$user_id)->update($data);
            return redirect('admin-profile')->with('success','Profile Updated Successfully');
         } 
                DB::table('users')->where('id',Auth::user()->id)->update($userAdmin);
                return redirect('admin-profile')->with('success','Profile Updated Successfully');
            }  
            
            $data = array();
            $data['employee_name'] = $request->name; 
            $data['current_address'] = $request->current_address;
            $data['addhar_number'] = $request->addhar_number;
            // $data['phone_number'] = $request->phone_number;
            // $data['personal_addhar_number'] = $request->personal_addhar_number;
            $data['pan_Number'] = $request->pan_Number;
            $data['education_background'] = $request->education_background;
            $data['date_of_brith'] = $request->date_of_brith;
            $data['blood_group'] = $request->blood_group;
            $data['emergeny_contact_name'] = $request->emergeny_contact_name;
            $data['personal_email'] = $request->personal_email;
            $data['user_id'] = Auth::user()->id;
            
            $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first();
              
            $image = $request->profile_pic; 
            $user_id = Auth::user()->id;

 
            
        // return $image;
         if($image)
         {
             
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
                  
                
                  
                $user['profile_picture'] = $img_url;
                $user['email'] = $request->email;
                $user['name'] = $request->name;
                $data['emplayees_photo'] = $img_url; 
                if($emp == null)
                 {
                     DB::table('employees')->insert($data);
                 } 
                $img = DB::table('users')->where('id',$user_id)->first();
                if($img->profile_picture == null)
                {
                 // return "Hello";
                  $image_path = $img->profile_picture;
                  $user1 = DB::table('users')->where('id',$user_id)->update($user);
                  $userData = DB::table('employees')->where('user_id',$user_id)->update($data);
                  return redirect('admin-profile')->with('success','Profile Updated Successfully');
                }
                else
                {
                  // return "BY";
                  $image_path = $img->profile_picture;
                  $done = unlink($image_path);
                  $user1 = DB::table('users')->where('id',$user_id)->update($user);
                  
                  $userData = DB::table('employees')->where('user_id',$user_id)->update($data);
                  return redirect('admin-profile')->with('success','Profile Updated Successfully');
                }
                
              }
              
         }
         else
         {
          //return "Test";
            $user['email'] = $request->email;
            $user['name'] = $request->name;
            // $oldphoto = $request->profile_pic;
            // $user['profile_picture'] = $oldphoto;
            $userData = DB::table('users')->where('id',$user_id)->update($user);
              DB::table('employees')->where('user_id',$user_id)->update($data);
            return redirect('admin-profile')->with('success','Profile Updated Successfully');   
         } 
      }


      public function setting(Request $request)
      {
          //return $request;
         $submit = $request['submit'];
         if($submit == 'submit')
         {
            $validate = $request->validate([
                'site_name' => 'required',
                'contact_number' => 'required',
                'role_id' => 'required', 
                'locations' => 'required',  
            ],
            [
               'site_name.required' => 'Enter the name you want to show on CRM Website.',
            ]);

              $rest = Setting::where('user_id', Auth::user()->id)->first();

             
                  if (!is_null($rest))
                  {
                     if ($request->site_logo) 
                     {
                        $position = strpos($request->site_logo, ';');
                        $sub = substr($request->site_logo,0,$position);
                        $ext = explode('/',$sub)[0];
         
                        $name = time().".".$ext;
                        $img = Image::make($request->site_logo)->resize(240,200);
                        $upload_path = 'public/backend/setting/';
                        $img_url = $upload_path.$name;
                        $img->save($img_url); 

                        // return "byy";
                        $setting = array();
                        $setting['site_name'] = $request->site_name;
                        $setting['contact_number'] = $request->contact_number;
                          $setting['role_id'] = $request->role_id;
                        // $setting['user_id'] = Auth::user()->id;
                        $setting['locations'] = $request->locations;
                        $setting['site_logo'] = $img_url; 

                        $settingUpdate = DB::table('settings')
                        ->where('user_id',Auth::user()->id)
                        ->update($setting); 
                        return redirect('admin-setting')->with('success','Settings Updated Successfully');
                      } 
                     else
                      { 
                        // return "byyjkhsdf";
                        $setting = array();
                        $setting['site_name'] = $request->site_name;
                        $setting['contact_number'] = $request->contact_number;
                         $setting['role_id'] = $request->role_id;
                        // $setting['user_id'] = Auth::user()->id;
                        $setting['locations'] = $request->locations;  
                        $settingUpdate = DB::table('settings')
                        ->where('user_id',Auth::user()->id)
                        ->update($setting); 
                        return redirect('admin-setting')->with('success','Settings Updated Successfully');
                      }  
                     }
                  else
                     { 

               //return "say than";
               if ($request->site_logo) {
                  
                  //return $request->role_id;

                  $position = strpos($request->site_logo, ';');
                  $sub = substr($request->site_logo,0,$position);
                  $ext = explode('/',$sub)[0];
   
                  $name = time().".".$ext;
                  $img = Image::make($request->site_logo)->resize(240,200);
                  $upload_path = 'public/backend/setting/';
                  $img_url = $upload_path.$name;
                  $img->save($img_url); 
   
                  $setting = new Setting;
                  $setting->site_name = $request->site_name;
                  $setting->contact_number = $request->contact_number;
                  $setting->role_id = $request->role_id;
                  $setting->user_id = Auth::user()->id;
                  $setting->locations = $request->locations;
                  $setting->site_logo = $img_url; 
                  $setting->save(); 
                  return redirect('admin-setting')->with('success','Settings Updated Successfully');
               } 
               else
               { 
                  $setting = new Setting;
                  $setting->site_name = $request->site_name;
                  $setting->contact_number = $request->contact_number;
                  $setting->role_id = $request->role_id;
                  $setting->user_id = Auth::user()->id;
                  $setting->locations = $request->locations; 
                  $setting->save();
                  return redirect('admin-setting')->with('success','Settings Updated Successfully');
               }
            } 
           
               
         }
         else
         {
           return "Error Here";
         }

      }

       public function location()
     {
        
        $locations = DB::table('locations')->latest()->get();
      
        return view('pages.admin.location',compact('locations'));
     }

     public function LocationUpdate(Request $request)
     {
      $submit = $request['submit'];
      if($submit == 'submit')
      {
        
         $validation = $request->validate([
            'locationUpdate' => 'required',
         ]);

         $locationupdate = array();
         $locationUpdate['location'] =  $request->locationUpdate;

         DB::table('locations')
         ->where('id',$request->updatelocationID)
         ->update($locationUpdate);

         return redirect('location')->with('location','Location Updated');

      }
      else
      {
         return "Error";
      }
     }

   
     

     public function CreateLocation(Request $request)
     {
         $submit = $request['submit'];
         if($submit == 'submit')
         {
            $validation = $request->validate([
               'location' => 'required | unique:locations,location',
            ],
            [
               'location.required' => "Location can't be left blank.",
            ]);

            $location = DB::table('locations')->insert(
               [
                  "location" => $request->location
               ]
            );

            return redirect('location')->with('success','Location Created Successfully');
         }
         else
         {
            return "Error";
         }
     }

     public function locationwise($id)
     {
        $location = DB::table('locations')->where('id',decrypt($id))->first();
         //  return $location->id;
        $locationWise = DB::table('employees') 
      //   ->join('leads','employees.id','leads.assign_employee_id')
        ->where('employees.employee_location','LIKE','%'.decrypt($id).'%')
         // ->where('leads.common_pool_status',0)
         ->select('employees.employee_name','employees.id',)
         //->select(DB::raw('count(*) as total'),'employees.employee_name','employees.id','leads.location_of_leads')
      //   ->groupBY('leads.assign_employee_id')
        ->get();

           //return $locationWise; 
         if($locationWise->isNotEmpty())
         {
            foreach($locationWise as $locationWiseCount)
            {

               $empLeads = DB::table('leads')->where('assign_employee_id',$locationWiseCount->id)->first();
               if (is_null($empLeads)) {
                  $data = array(
                     'employee_name' => $locationWiseCount->employee_name,
                     'employee_lead_count' => 0,
                     'leads_count' => 0,
                     'location_id' => decrypt($id),
                     'emaployee_id' => $locationWiseCount->id
                      );  
         
                     $employeeData[] = $data;

                     $locationName = $location->location; 
                  //return redirect('dashboard')->with('Note','Lead Not Assigned ' .$locationName .' location');

               }
               else{
                  $locationwiseuserCount = DB::table('leads')
                  ->where('assign_employee_id',$locationWiseCount->id)
                  ->where('common_pool_status', '!=' ,1)
                  ->where('lead_status', '!=' ,14)
                  ->where('lead_status', '!=' ,16) 
                  ->where('lead_status', '!=' ,8) 
                  ->where('lead_status', '!=' ,9) 
                  ->where('lead_status', '!=' ,10) 
                  ->where('lead_status', '!=' ,11) 
                  ->where('lead_status', '!=' ,12) 
                  ->where('location_of_leads',decrypt($id))->count();
              
               // return $locationwiseuserCount;
               $EmployeeLeadcount = DB::table('leads')->where('location_of_leads',decrypt($id))->count();
                
               if ($EmployeeLeadcount == 0) {
                  return redirect('dashboard')->with('Note','Lead Not Assigned ');
               }
               $progessBar = $locationwiseuserCount/$EmployeeLeadcount * 100; 
               //return $progessBar;
               $data = array(
               'employee_name' => $locationWiseCount->employee_name,
               'employee_lead_count' => $locationwiseuserCount,
               'leads_count' => round($progessBar,2),
               'location_id' => decrypt($id),
               'emaployee_id' => $locationWiseCount->id
                );  
   
               $employeeData[] = $data;
               }

              
         }
          // return $employeeData;
            $locationName = $location->location; 
            return view('pages.admin.location-wise',compact(['employeeData','locationName',]));
         }

         else{
            $locationName = $location->location; 
             return redirect('dashboard')->with('Note','Lead Not Assigned ' .$locationName .' location');
         }
        
     }

      public function EmployeeLead($id,$l_id)
      {
 
         $emplyeeLeadData = DB::table('leads')
         ->where('leads.assign_employee_id',decrypt($id))
         ->where('leads.location_of_leads',decrypt($l_id))
         ->where('leads.common_pool_status',0)
         ->where('leads.lead_status','!=',14)
         ->join('employees', 'employees.id','leads.assign_employee_id')
         ->select('leads.*','employees.employee_name')->latest()->get();

          
         $emp_names = DB::table('employees')->where('id',decrypt($id))->first();
         $location_names = DB::table('locations')->where('id',decrypt($l_id))->first();
         // return $location_names->location;
         //return $emp_name;
         $LeadStatus = DB::table('lead_statuses')->get();
         return view('pages.admin.employee-leads',compact(['emplyeeLeadData','location_names','emp_names','LeadStatus']));
      }

      public function UserWiseLeads($id)
      {
 
         $emplyeeLeadData = DB::table('leads')
         ->where('leads.assign_employee_id',$id) 
         ->where('leads.common_pool_status',0)
         ->join('employees', 'employees.id','leads.assign_employee_id')
         ->select('leads.*','employees.employee_name')->latest()->paginate(10);
         
         
         $emp_names = DB::table('employees')->where('id',$id)->first();
         
         $LeadStatus = DB::table('lead_statuses')->get();
         return view('pages.admin.user-wise-leads',compact(['emplyeeLeadData','emp_names','LeadStatus']));
      }

      // employee location wise lead 

      public function employeeLocationWiseLeadShow($id,$lwls)
      {
         // $emplyeeLeadData = DB::table('leads')
         // ->where('location_of_leads',$lwls)
         // ->where('leads.assign_employee_id',decrypt($id)) 
         // ->where('leads.common_pool_status',0)
         // ->join('employees', 'employees.id','leads.assign_employee_id')
         // ->select('leads.*','employees.employee_name')->latest()->paginate(10);

         $emplyeeLeadData = DB::table('leads')
         ->where('leads.assign_employee_id',decrypt($id))
         ->where('leads.location_of_leads',decrypt($lwls))
         ->where('leads.common_pool_status',0)
         ->where('leads.lead_status','!=',14)
         ->join('employees', 'employees.id','leads.assign_employee_id')
         ->select('leads.*','employees.employee_name')->latest()->paginate(10);
 
         
         // dd($emplyeeLeadData);
         $emp_names = DB::table('employees')->where('id',decrypt($id))->first();
         
         $LeadStatus = DB::table('lead_statuses')->get();
         return view('pages.admin.user-wise-leads',compact(['emplyeeLeadData','emp_names','LeadStatus']));
      }

      // employee location wise lead end

        public function LeadStatusUpdate($id)
      {

   
           $Leaddata = DB::table('leads')
         // ->whereNotIn('lead_status',[8,9,10,11,12,13,16])
         ->where('id',decrypt($id))
         ->first(); 

         $countryCodeMainIso = DB::table('country')  
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $Leaddata->country_code))
         ->first();
         
         $countryCodeAltIso = DB::table('country')  
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $Leaddata->alit_country_code))
         ->first();


         $countryCodeAlt2Iso = DB::table('country')
         ->where('phonecode', '!=', 0)
         ->where('phonecode', '=', str_replace(' ', '', $Leaddata->alt_country_code1))
         ->first();
 
         

         if ($Leaddata->lead_status == 2) {
            $LeadsNotes = "Lead has been Opened";
         }
         // elseif($Leaddata->lead_status == 5)
         // {
         //    $LeadsNotes = "Next Follow Up Updated";
         // }
         elseif (in_array($Leaddata->lead_status, ['8', '9','10', '11','12', '13','16'])) {
            $LeadsNotes =  "Closed Lead has been Opened";
         }
           
         // dd($LeadsNotes);
         $currentDateTime = Carbon::now();
         $futureDateTime = $currentDateTime->addHours(2);
 
         // if ($Leaddata == null)
         // {
         //     //return "jkj";
         //     $LeadStatus = DB::table('lead_statuses')->get();
         //     $Leads = DB::table('leads')->where('id',decrypt($id))->first();
         //     $emp_id = DB::table('employees')->where('id',$Leads->assign_employee_id)->first();
         //     $location_id = DB::table('locations')->where('id',$Leads->location_of_leads)->first();
         //     $selected = explode(',', $Leads->project_id);
         //     $buyerSellers = DB::table('buyer_sellers')->get();
         //     $projectTypes = DB::table('project_types')->get(); 
             
         //     $projectLists = DB::table('projects')
         //     ->orderBy('project_name','ASC')
         //     ->get();
         //    // dd($projectLists);
         //     return view('pages.admin.status-update',compact(['LeadStatus','Leads','emp_id','location_id','projectLists','projectTypes','buyerSellers']));
         // }
         // else
         // {
             
            $isLeadPendding = DB::table('lead_status_histories')->where('lead_id',$Leaddata->id)->count();
            if ($isLeadPendding == 2 || $isLeadPendding == 1) {
               $nextFollowUp['lead_status'] = 5;
               $nextFollowUp['next_follow_up_date'] = $Leaddata->next_follow_up_date ?? $futureDateTime;
               $nextFollowUp['common_pool_status'] = 0;
               $nextFollowUpdate = DB::table('leads')->where('id',$Leaddata->id)->update($nextFollowUp);
   
               $leadsHistory['lead_id'] = $Leaddata->id;
               $leadsHistory['date'] = $Leaddata->date; 
               $leadsHistory['next_follow_up_date'] = $Leaddata->next_follow_up_date ?? $futureDateTime;
               $leadsHistory['status_id'] = 5;
               $leadsHistory['project_id'] = $Leaddata->project_id;
               $leadsHistory['customer_interaction'] =   isset($LeadsNotes) ? $LeadsNotes : null ;
               $leadsHistory['created_by'] = Auth::user()->id;  
               $leadsHistory['created_at'] = Carbon::now(); 
               // $test = DB::table('lead_status_histories')->insertGetId($leadsHistory);
               
   
            }
            elseif($Leaddata->lead_status == 8 || $Leaddata->lead_status == 9 || $Leaddata->lead_status == 10 || $Leaddata->lead_status == 11 || $Leaddata->lead_status == 12 || $Leaddata->lead_status == 13 || $Leaddata->lead_status == 16)
            { 
               $nextFollowUp['lead_status'] = 5;
               $nextFollowUp['next_follow_up_date'] = $Leaddata->next_follow_up_date ?? $futureDateTime;
               $nextFollowUp['common_pool_status'] = 0;
               $nextFollowUpdate = DB::table('leads')->where('id',$Leaddata->id)->update($nextFollowUp);
   
               $leadsHistory['lead_id'] = $Leaddata->id;
               $leadsHistory['date'] = $Leaddata->date; 
               $leadsHistory['next_follow_up_date'] = $Leaddata->next_follow_up_date ?? $futureDateTime;
               $leadsHistory['status_id'] = 5;
               $leadsHistory['project_id'] = $Leaddata->project_id;
               $leadsHistory['customer_interaction'] =  isset($LeadsNotes) ? $LeadsNotes : null;
               $leadsHistory['created_by'] = Auth::user()->id;  
               $leadsHistory['created_at'] = Carbon::now(); 
               $test = DB::table('lead_status_histories')->insertGetId($leadsHistory);
            } 
            $LeadStatus = DB::table('lead_statuses')->get();
            $Leads = DB::table('leads')->where('id',decrypt($id))->first();
            $emp_id = DB::table('employees')->where('id',$Leads->assign_employee_id)->first();
            $location_id = DB::table('locations')->where('id',$Leads->location_of_leads)->first();
            $selected = explode(',', $Leads->project_id);
            $buyerSellers = DB::table('buyer_sellers')->get();
            $projectTypes = DB::table('project_types')->get(); 
            $leadTypeBifurcations = DB::table('lead_type_bifurcation')->get();
            $number_of_units = DB::table('number_of_units')->get();
            $Budgets = DB::table('budget')->get();
            
            $projectLists = DB::table('projects')
            ->orderBy('project_name','ASC')
            ->get();
           // dd($projectLists);
            return view('pages.admin.status-update',compact(['LeadStatus','Leads','emp_id','location_id','projectLists','projectTypes','buyerSellers','leadTypeBifurcations','number_of_units','Budgets','countryCodeAltIso','countryCodeAlt2Iso','countryCodeMainIso']));
         // }
      }
       

       public function statusUpdate(Request $request,$id)
      {
           
            
         $submit = $request['submit'];
         if($submit)
         {   
            
         //  dd(strip_tags($request->customer_interaction));
            
            $validation = $request->validate([
               'status_date' => 'required',
                'assign_employee_id' => 'required',
               'customer_interaction' => 'required',
               'buying_location' => 'required'
               // 'booking_amount' => 'required_if:lead_status,==,14',
               // 'booking_project' => 'required_if:lead_status,==,14',
               // 'booking_date' => 'required_if:lead_status,==,14',
            ],
            [
               // 'booking_amount.required_if' => 'The booking amount field is required when lead status is Booking Confirmed',
               //  'booking_project.required_if' => 'The booking project field is required when lead status is Booking Confirmed',
               //  'booking_date.required_if' => 'The booking date field is required when lead status is Booking Confirmed',
            ]);


            

            $projectMultiple = implode(',',$request->project_name ?? []);
            $isAwaCheck = $request->rwa;
            //return $projectMultiple;

              $next_follow_up_date =date('Y-m-d H:i:s', strtotime($request->next_follow_up_date));
              $bookingDate = date('Y-m-d H:i:s', strtotime($request->booking_date));
             // dd($next_follow_up_date);
              $status_date =date('Y-m-d H:i:s', strtotime($request->status_date));
              $projectBooking = implode(',',$request->booking_project ?? []);
               $existingProperty = implode(',', $request->existing_property ?? []); 
               $emp = DB::table('employees')->where('user_id',Auth::user()->id)->first(); 
            //   return $id;
              $ReminderUser = DB::table('leads')->where('leads.id',$id)->first(); 
               
              $userRiminder = User::where('id',$ReminderUser->user_id)->first(); 

              $reminderDate = Carbon::parse($next_follow_up_date)->subMinute(1);
               
              $user = User::first(); 
              $employeeN = DB::table('employees')->where('id',$ReminderUser->assign_employee_id)->first(); 
            
              $employeeCurrent = DB::table('employees')->where('id',$request->assign_employee_id)->first();
              $empNotification = User::where('id',$employeeN->user_id)->first(); 

              $buyer_seller_customerType = DB::table('leads')
                   ->join('buyer_sellers', 'leads.buyer_seller','buyer_sellers.id')
                   ->where('buyer_sellers.id',$ReminderUser->buyer_seller)
                   ->select('buyer_sellers.name')->first();
               
               $LeadAssinglaction = DB::table('locations')
                ->leftjoin('leads', 'leads.location_of_leads','=','locations.id')
                ->where('locations.id', $ReminderUser->location_of_leads)
                ->select('locations.location')->first();
                
                
                $buyer_seller_customerType = DB::table('leads')
                   ->join('buyer_sellers', 'leads.buyer_seller','buyer_sellers.id')
                   ->where('buyer_sellers.id',$ReminderUser->buyer_seller)
                   ->select('buyer_sellers.name')->first();
                  
               $buyerSelllerName = DB::table('leads')
                   ->join('buyer_sellers', 'leads.buyer_seller','=','buyer_sellers.id')
                   ->where('buyer_sellers.id', $ReminderUser->buyer_seller)
                   ->select('buyer_sellers.name')->first();
               
               $LeadAssinglaction = DB::table('locations')
                ->leftjoin('leads', 'leads.location_of_leads','=','locations.id')
                ->where('locations.id', $ReminderUser->location_of_leads)
                ->select('locations.location')->first();
                
                 $countryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']);
               $withcountryCode = implode(', ',$countryCode);
               $finalcountryCode = str_replace(',', '', $withcountryCode); 
   
               if (preg_replace('/\s+/', '',$request->contact_number['main']) != null) { 
               
                  $MaincountryCode = explode(preg_replace('/\s+/', '',$request->contact_number['main']), $request->contact_number['full']);
                  $MainwithcountryCode = implode(', ',$MaincountryCode);
                  $MainfinalcountryCode = str_replace(',', '', $MainwithcountryCode);  
               } 


               // dd($request->alt_contact_number['altmain']);
                
                if (preg_replace('/\s+/', '',isset($request->alt_contact_number['altmain'])) != null) { 
                  $altcountryCode = explode(preg_replace('/\s+/', '',$request->alt_contact_number['altmain']), $request->alt_contact_number['altfull']);
                  $altwithcountryCode = implode(', ',$altcountryCode);
                  $altfinalcountryCode = str_replace(',', '', $altwithcountryCode); 
                }
                if (preg_replace('/\s+/', '',isset($request->alt_contact_number_2['altmain2'])) != null) { 
            
                  $altcountryCode2 = explode(preg_replace('/\s+/', '',$request->alt_contact_number_2['altmain2']), $request->alt_contact_number_2['altfull2']);
                  $altwithcountryCode2 = implode(', ',$altcountryCode2);
                  $altfinalcountryCode2 = str_replace(',', '', $altwithcountryCode2); 
                }
                
                $AdminNotification = User::where('id', 22)->first();

                        //  Is Customer Type Seller When Send Email & Notification Of Employee
             if($request->buyer_seller == 2 || $request->buyer_seller == 11 ||$request->buyer_seller == 4 || $request->buyer_seller == 5 ||$request->buyer_seller == 12 || $request->buyer_seller == 13)
             { 
              
                 $IsLeadSellers = DB::table('employees')
                ->join('users','employees.user_id','users.id')
                ->where('employees.employee_location','LIKE','%'.$request->buying_location.'%')
                ->where('organisation_leave',0)
                ->select('employees.employee_location','users.email','users.id')
                ->get();

                $authenticatedUserEmail = auth()->user()->email; // Get the authenticated user's email
               $AssingEmployeeEmail = $employeeN->official_email;


                foreach ($IsLeadSellers as $emp) { 
                    $EmployeeLocations = explode(',', $emp->employee_location); 
                    foreach ($EmployeeLocations as $empLocation) { 
                        if ($empLocation == $request->buying_location && $emp->email != $authenticatedUserEmail && $emp->email != $AssingEmployeeEmail && $emp->email != $AdminNotification->email) { 
                            $IsSeller[] = $emp->email;
                            $empNoty[] = $emp->id;
                        }
                    }  
                }   
 
             }  
                
                 $originalArray = $request->project_name ?? []; 
                $elementToRemoves = $request->booking_project ?? []; 
                foreach ($elementToRemoves as $elementToRemove) { 
                  $filteredArray = array_values(array_diff($originalArray, [$elementToRemove])); 
                    $originalArrayEP = $request->existing_property ?? [];  
                    $originalArrayEProject = array_unshift($originalArrayEP, $elementToRemove); 
                } 
                 
                $ExistProjectIsConfirm1 = implode(',',$originalArrayEP ?? []);
                $projectV = implode(',',$filteredArray ?? []);


                $originalArrayDis = $request->project_name ?? []; 
                $elementToRemovesDV = $request->existing_property ?? []; 
                foreach ($elementToRemovesDV as $elementToRemoveDisVis) { 
                  $filteredPDV = array_values(array_diff($originalArrayDis, [$elementToRemoveDisVis])); 
                    $originalProjectDis= $request->existing_property ?? [];  
                    $originalArrayEProject = array_unshift($originalProjectDis, $elementToRemoveDisVis); 
                } 
                 
                $ExistProjectIsConfirm = implode(',',$originalProjectDis?? []);
                $projectDis = implode(',',$filteredPDV ?? []);
                
               
            //     if ($request->lead_status == 14) { 
            //    $details = [ 
            //       'subject' => 'Lead Status Updated by: ' .Auth::user()->name .':'.$ReminderUser->lead_name.' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $ReminderUser->number_of_units .' unit' .' | ' .$ReminderUser->budget ,
            //       // 'greeting' => 'Hi '.$lead['lead_name'],
            //       'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
            //       'thanks' => 'Thank you for choosing Homent.',  
            //       'leads' => $ReminderUser->lead_name
            //  ]; 
            //     Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));  
            //     Notification::sendNow($user, new WelcomeNotification($details)); 
            //     }

            $employeeNo = DB::table('employees')->where('id',$ReminderUser->assign_employee_id)->first(); 
             if(Auth::user()->id == $employeeNo->user_id)
             {
               if ($ReminderUser->budget == null ) {
                   $budget = "NA";
               } else {
                  $budget = $ReminderUser->budget;
               }
                
                        
                     //$user = User::first();
                        $employeeN = DB::table('employees')->where('id',$ReminderUser->assign_employee_id)->first();  
                        $empNotification = User::where('id',$employeeN->user_id)->first(); 
                        $rwaNotification = User::where('id',$request->rwa ?? $ReminderUser->rwa)->first();
                        $coFollowUpNotification = User::where('id',$request->co_follow_up ?? $ReminderUser->co_follow_up)->first();
                        $isCP = Auth::user()->roles_id == 10 ? "CP " : "";
                        $details = [
                           'subject' => 'Lead Updated By ' . $isCP .Auth::user()->name .' | '.$ReminderUser->lead_name.' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $ReminderUser->number_of_units .'Unit'. ' | '  .$ReminderUser->budget,
                            // 'greeting' => 'Hi '.$lead['lead_name'],
                            'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                            'thanks' => 'Thank you for choosing Homent.',  
                            'leads' => $ReminderUser->lead_name,
                            'EmployeeName' => Auth::user()->id, 
                            'leads_status' => $request->lead_status  ?? $ReminderUser->lead_status,
                            'leadsID' =>  $id, 
                            'property_requirement' => $buyer_seller_customerType->name,
                            'location' => $LeadAssinglaction->location, 
                             'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                            'number_of_units' => $ReminderUser->number_of_units,
                            'privios_emp' => $ReminderUser->assign_employee_id,
                            'budget' =>  $budget, 
                            'current_empid' => $ReminderUser->assign_employee_id,
                            'messageUpdateBy' => 'Lead Updated by '. $isCP . \Auth::user()->name,
                            //'cc' => [],
                            'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                            'co_follow_up' => $request->co_follow_up ?? null,
                       ];  
                     
                       
                      Notification::sendNow([$AdminNotification],new WelcomeNotification($details));
                      if ($rwaNotification != null ) {
                        Notification::sendNow($rwaNotification, new WelcomeNotification($details)); 
                      } 
                      if($coFollowUpNotification != null )
                      {
                        Notification::sendNow($coFollowUpNotification, new WelcomeNotification($details));
                      }

                     
             }else
             {
 
                // Another employee by IF lead update when nofication send Assign Employyed
               
                  if ($ReminderUser->budget == null ) {
                     $budget = "NA";
                  } else {
                     $budget = $ReminderUser->budget;
                  }
                    
                        
                        
                        $employeeN = DB::table('employees')->where('id',$ReminderUser->assign_employee_id)->first();  
                        $empNotification = User::where('id',$employeeN->user_id)->first(); 
                        $rwaNotification = User::where('id',$request->rwa ?? $ReminderUser->rwa )->first();
                        $coFollowUpNotification = User::where('id',$request->co_follow_up ?? $ReminderUser->co_follow_up)->first();
                        $isCP = Auth::user()->roles_id == 10 ? "CP " : "";
                        $details = [
                           'subject' => 'Lead Updated By '. $isCP .Auth::user()->name .' | '.$ReminderUser->lead_name.' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $ReminderUser->number_of_units .' Units'  . ' | '  .$ReminderUser->budget,
                           // 'greeting' => 'Hi '.$lead['lead_name'],
                           'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.', 
                           'thanks' => 'Thank you for choosing Homent.',  
                           'leads' => $ReminderUser->lead_name,
                           'EmployeeName' => Auth::user()->id, 
                           'leads_status' => $request->lead_status ?? $ReminderUser->lead_status,
                           'leadsID' =>  $id, 
                           'property_requirement' => $buyer_seller_customerType->name,
                           'location' => $LeadAssinglaction->location, 
                            'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                           'number_of_units' => $ReminderUser->number_of_units,
                           'privios_emp' => $ReminderUser->assign_employee_id,
                           'budget' =>  $budget, 
                           'current_empid' => $ReminderUser->assign_employee_id,
                           'messageUpdateBy' => 'Lead Updated by '. $isCP . \Auth::user()->name,
                           'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                           //'cc' => [],
                           'co_follow_up' => $request->co_follow_up ?? null,
                      ];  
                    
                        
                          Notification::sendNow([$empNotification,$AdminNotification], new WelcomeNotification($details));
                            if ($rwaNotification != null) {
                              Notification::sendNow($rwaNotification, new WelcomeNotification($details)); 
                            }
                            if($coFollowUpNotification != null )
                           {
                              Notification::sendNow($coFollowUpNotification, new WelcomeNotification($details));
                           }
                         
                            
                 

             }

             $leads['lead_id'] = $id;
             $leads['date'] = $status_date;
             if ($request->lead_status == 8 || $request->lead_status == 9 || $request->lead_status == 10 || $request->lead_status == 11 ||$request->lead_status == 12) { 
               $leads['next_follow_up_date'] = null;
            }
             elseif($request->lead_status == 14 && $ReminderUser->lead_status != 14)
            {

                
               // First, fetch the existing record using the $id
               // $existingBookingConfirm = DB::table('booking_confirms')->where('lead_id', $id)->first();
               //  dd($existingBookingConfirm);

               // Check if the record exists before attempting to update it
               // if ($existingBookingConfirm) {
                 
               //    DB::table('booking_confirms')
               //       ->where('id', $id)
               //       ->update([
               //             'booking_status' => $request->lead_status ?? $ReminderUser->lead_status,
               //             'booking_date' => $bookingDate,
               //             'booking_project' => $projectBooking,
               //             'booking_amount' => $request->booking_amount,
               //             'lead_assign_to' => $request->assign_employee_id ?? $ReminderUser->assign_employee_id,
               //             'buying_location' => $request->buying_location ?? $ReminderUser->location_of_leads,
               //             'rwa' => $request->rwa ?? $ReminderUser->rwa,
               //             'updated_at' => Carbon::now(),
               //       ]);
               // }

                
                 $bookingConfirm =  DB::table('booking_confirms')->insert([
                     'lead_id' => $id,
                     'booking_status' => $request->lead_status,
                     'booking_date' =>  $bookingDate,
                     'booking_project' => $projectBooking,
                     'booking_amount' => $request->booking_amount,
                     'lead_assign_to' => $request->assign_employee_id ?? $ReminderUser->assign_employee_id,
                     'buying_location' => $request->buying_location ?? $ReminderUser->location_of_leads,
                     'rwa' => $request->rwa ??  $ReminderUser->rwa ,
                     // 'co_follow_up' => $request->co_follow_up ?? null,
                     'created_at' => Carbon::now(),
                     'updated_at' => Carbon::now(),
                  ]);
                  
                  $IsDealConf = DB::table('leads')->where('id',$id)->update([ 
                      'project_id' => $projectV ?? null,
                      'existing_property' => $ExistProjectIsConfirm1 ?? null,
                  ]);
                  
                  

                   // $leads['lead_id'] = $id;
                  // $leads['date'] = $status_date;
                  // $leads['status_id'] = $request->lead_status ?? $ReminderUser->lead_status;
                  // $array1 = $request->project_name ?? [];
                  // $array2 = explode(',',$ReminderUser->project_id) ?? [];
                  
                  // if (empty(array_diff($array1, $array2)) && empty(array_diff($array2, $array1))) {
                  //    $leads['project_id'] = null;
                  // } else {
                  //    $leads['project_id'] = $projectMultiple ?? null;
                  // }
                  // // $leads['co_follow_up'] = $request->co_follow_up ?? null;
                  // $leads['created_by'] = Auth::user()->id;
                  // $leads['customer_interaction'] = $request->customer_interaction ?? "This Booking Confirmed";
                  // $leads['follow_up_time'] = $request->follow_up_time;
                  // $leads['created_at'] = Carbon::now();
                  // $leads['next_follow_up_date'] = null;
                  // $test = DB::table('lead_status_histories')->insertGetId($leads);
            }

            elseif($request->lead_status == 1 || $request->lead_status == 2 || $request->lead_status == 3 || $request->lead_status == 4 || $request->lead_status == 5 ||  $request->lead_status == 6 ||  $request->lead_status == 7)
            {
               $leads['next_follow_up_date'] = $next_follow_up_date; 
               $bookingDate= null;
               
               if ($ReminderUser->assign_employee_id != $request->assign_employee_id) {
 
                
                  $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
                   $emPrevious = DB::table('employees')
                             ->where('id',$ReminderUser->assign_employee_id)
                             ->first();
                   $empNotification = User::where('id',$employeeN->user_id)->first(); 
                 
                   $details = [
                       'subject' => $emPrevious->employee_name. ' Lead Assigned to ' . $employeeN->employee_name .' | '.$ReminderUser->lead_name.' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $ReminderUser->number_of_units . 'Unit'. ' | '  .$ReminderUser->budget,
                       // 'greeting' => 'Hi '.$datastatus['lead_name'],
                       'body' => ' This is an automated email as the existing lead of '. isset($employeeN->employee_name) .' has been assigned to you for follow-up. There is no need to reply to this email', 
                       'thanks' => 'Thank you for choosing Homent.',  
                       'leads' => $ReminderUser->lead_name,
                       'EmployeeName' => Auth::user()->id,
                       'leads_status' => $request->lead_status,
                       'leadsID' =>  $id,
                       'property_requirement' => $buyer_seller_customerType->name,
                       'location' => $LeadAssinglaction->location, 
                       'number_of_units' => $request->number_of_units ?? $ReminderUser->number_of_units,
                       'privios_emp' => $ReminderUser->assign_employee_id,
                       'budget' => $ReminderUser->budget, 
                        'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                       'current_empid' => $request->assign_employee_id,
                        'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                        'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
                        'co_follow_up' => $request->co_follow_up ?? null,
                  ]; 
 
                      if (Auth::user()->roles_id != 10) {
                       
                         Notification::sendNow([$empNotification,$AdminNotification],new WelcomeNotification($details));
                      }

                    
                      
                  }
            }
            else
            { 
                  $leads['next_follow_up_date'] = $next_follow_up_date;
            }  
             $leads['status_id'] = $request->lead_status ?? $ReminderUser->lead_status;
             
             $array1 = $request->project_name ?? [];
             $array2 = explode(',',$ReminderUser->project_id) ?? [];
            
            if (empty(array_diff($array1, $array2)) && empty(array_diff($array2, $array1))) {
               $leads['project_id'] = null;
            } else {
               $leads['project_id'] = $projectMultiple ?? null;
            }
            
             $leads['created_by'] = Auth::user()->id;
            //$leads['customer_interaction'] = $request->customer_interaction;
             $leads['follow_up_time'] = $request->follow_up_time;
             
             
             
              $firstTimeRemoveCoFollowUp = true; // Flag to track the first time co_follow_up is removed
             $firstTimeRemoveRwa = true; // Flag to track the first time rwa is removed
             
             // Co Follow Up logic
             if (!is_null($ReminderUser->co_follow_up) && $request->co_follow_up == null) {
               

                 $coFollowUp = User::find($ReminderUser->co_follow_up);
                 $cupAction = $coFollowUp ? Auth::user()->name . " Removed follow-up Buddy " . $coFollowUp->name : "";
                 $customerInteraction = $request->customer_interaction;
                 $firstTimeRemoveCoFollowUp = false; // Set the flag to false after the first "Remove follow-up Buddy" message is shown
             } elseif ($request->co_follow_up == $ReminderUser->co_follow_up || $request->co_follow_up == null) { 
               $customerInteraction = $request->customer_interaction;
               $cupAction = "";  // No action needed for co_follow_up
             } else {
                 $coFollowUp = User::find($request->co_follow_up);
                 $cupAction = $coFollowUp ? Auth::user()->name . " Made follow-up Buddy " . $coFollowUp->name : "";
                 $customerInteraction = $request->customer_interaction;
             }
             
             
             
             //   Changed Assigned By  
               if ($request->assign_employee_id == $ReminderUser->assign_employee_id) { 
               $customerInteraction = $request->customer_interaction;
               $changeEmp = "";  // No action needed for co_follow_up
            } else { 
            
            $emPrevious = DB::table('employees')
               ->where('id',$ReminderUser->assign_employee_id)
               ->first();

               $changeEmp =  " Lead Assigned  from ". $emPrevious->employee_name . " to " . $employeeCurrent->employee_name ;
               $customerInteraction = $request->customer_interaction;
               
            }
             
             // RWA logic
             
              
             if (!is_null($ReminderUser->rwa) && $request->rwa == null && ($request->rwa !== $ReminderUser->rwa)) {
                  $coFollowUp = User::find($ReminderUser->rwa);
                  
                   $cpAction = $coFollowUp ? Auth::user()->name . " Removed Channel Partner " . $coFollowUp->name : "";
                  $customerInteraction = $request->customer_interaction;
                  $firstTimeRemoveRwa = false; // Set the flag to false after the first "Remove Channel Partner" message is shown
             } elseif ($request->rwa == $ReminderUser->rwa || $request->rwa == null) { 
                 $cpAction = "";  // No action needed for rwa
                 $customerInteraction = $request->customer_interaction;
 
             } else {
                 $cpname = User::find($request->rwa);
                 $cpAction = Auth::user()->name . " Made Channel Partner " . $cpname->name;
                 $customerInteraction = $request->customer_interaction;
             }
             
              if ($request->project_name == null) {
                $projectMultiple = "";
             }else
              
               $projectNames = DB::table('projects')
               ->whereIn('id', $request->project_name)
               ->pluck('project_name')
               ->toArray();
                
               if ($projectMultiple == $ReminderUser->project_id || $projectMultiple == null) { 
                    $projectIsDis = "";  // No action needed for projectMultiple
                    $customerInteraction = $request->customer_interaction;
  
                 } else { 
                   $projectIsDis = "Project Discussed/Visited: ". implode(', ', $projectNames);
                    $customerInteraction = $request->customer_interaction;
                 }
                
                if ($changeEmp !== "") {
                  // Remove the first comma from $projectIsDis if it exists
                  $projectIsDis = preg_replace('/,/', '', $projectIsDis, 1);
              
                  // Construct $removeComma with $changeEmp and $projectIsDis
                  $removeComma = $changeEmp . ($projectIsDis !== "" ? ' , ' : '') . $projectIsDis;
              } else {
                  // If $changeEmp is empty, use $projectIsDis as is
                  $removeComma = $projectIsDis;
              }
              
             $leads['customer_interaction'] = str_replace("<br>", "", $customerInteraction) . $cupAction . $cpAction . $removeComma;
             $leads['created_at'] = Carbon::now();
             $test = DB::table('lead_status_histories')->insertGetId($leads);

             
              

             //return $test;
             $datastatus = array();
             if ($request->lead_status == 8 || $request->lead_status == 9 || $request->lead_status == 10 || $request->lead_status == 11 ||$request->lead_status == 12) {
               $datastatus['lead_status'] = $request->lead_status;
               $datastatus['common_pool_status'] = 1 ?? 0;
               $leads['next_follow_up_date'] = null;
               $datastatus['updated_at'] = Carbon::now();
            }else
            {
                  $datastatus['lead_status'] = $request->lead_status;
                  $datastatus['common_pool_status'] = $request->common_pool_status;
                  $leads['next_follow_up_date'] = $next_follow_up_date;
            } 
            
             $datastatus['customer_interaction'] = $request->customer_interaction;
             //$datastatus['common_pool_status'] = $request->common_pool_status ?? 0 ;
             $datastatus['last_contacted'] = $status_date;
             $datastatus['created_at'] = Carbon::now();
             $datastatus['next_follow_up_date'] = $next_follow_up_date;
             $datastatus['booking_date'] = $bookingDate;
             $datastatus['booking_project'] = $projectBooking;
             if ($request->lead_status == 14) {
               $datastatus['project_id'] = $projectV;
             }else
             {
               if($projectDis == "")
               { 
                  $datastatus['project_id'] = $projectMultiple;
               }
               else
               { 
                  $datastatus['project_id'] = $projectDis;
               }
              
             } 

            //  $datastatus['project_id'];

            //  $datastatus['project_id'] =  isset($projectV)  ? $projectV : $projectMultiple;
             //$datastatus['project_id'] = $projectMultiple;
             $datastatus['booking_amount'] = $request->booking_amount;
             $datastatus['updated_at'] = Carbon::now();
             //$datastatus['rwa'] = $request->rwa ?? $ReminderUser->rwa;
             //$datastatus['co_follow_up'] = $request->co_follow_up ?? $ReminderUser->co_follow_up;
             
              if ($request->has('rwa')) {
                $datastatus['rwa'] = $request->rwa;
            } elseif ($ReminderUser->rwa !== null) {
                   $datastatus['rwa'] = $ReminderUser->rwa;
            } else {
                   $datastatus['rwa'] = null;
            }
             
             if ($request->has('co_follow_up')) {
               $datastatus['co_follow_up'] = $request->co_follow_up;
            } elseif ($ReminderUser->co_follow_up !== null) {
                     $datastatus['co_follow_up'] = $ReminderUser->co_follow_up;
            } else {
                     $datastatus['co_follow_up'] = null;
            }
            
             $datastatus['location_of_leads'] = $request->buying_location ?? $ReminderUser->location_of_leads;
             $datastatus['assign_employee_id'] =$request->assign_employee_id ?? $ReminderUser->assign_employee_id; 
             if($request->lead_status == 14){  
               $datastatus['existing_property'] = $ExistProjectIsConfirm1;
             }
             else
             {
                
                  $datastatus['existing_property'] = $existingProperty == "" ? null : $existingProperty;

                  // $idsToUpdate = $request->existing_property; // ["1","3","5"]
                 
                  $idsToUpdate = explode(',', $existingProperty);

                  if (count($idsToUpdate) > 1) {
                     DB::table('leads')
                        ->where('id', $id)
                        ->update(['regular_investor' => 'YES']);
                  }else
                  {
                     DB::table('leads')
                     ->where('id', $id)
                     ->update(['regular_investor' => 'NO']);
                  }
                
             }
             //$datastatus['existing_property'] = $existingProperty == "" ? null : $existingProperty; 
             $datastatus['dnd'] = $request->is_dnd ?? 0;
             $datastatus['is_featured'] = $request->is_featured ?? 0;
             $datastatus['buyer_seller'] = $request->buyer_seller ?? 0;
             $datastatus['rent'] = $request->rent ?? null;
             $datastatus['project_type'] = implode(',', $request->customer_requirement ?? []);
             
              if (isset($request->alt_contact_number_2['altmain2']) != null) {
               $datastatus['alt_no_Whatsapp_2'] = str_replace(' ', '', $request->alt_contact_number_2['altmain2']);
               $datastatus['alt_country_code1'] = $altfinalcountryCode2;
           } else {
               $datastatus['alt_no_Whatsapp_2'] = null;
               $datastatus['alt_country_code1'] = null;
           }

           if (isset($request->alt_contact_number['altmain']) != null) {
               $datastatus['alt_no_Whatsapp'] = str_replace(' ', '', $request->alt_contact_number['altmain']);
               $datastatus['alit_country_code'] = $altfinalcountryCode;
           } else {
               $datastatus['alt_no_Whatsapp'] = null;
               $datastatus['alit_country_code'] = null;
           } 
             
           $datastatus['alt_contact_name'] = $request->alt_contact_name;
           $datastatus['alt_contact_name_2'] = $request->alt_contact_name_2; 
           $datastatus['budget'] = $request->budget;
           $datastatus['lead_type_bifurcation_id'] = $request->lead_type;
           $datastatus['number_of_units'] = $request->number_of_units;
           $datastatus['location_of_client'] = $request->location_of_customer;
           $datastatus['lead_name'] = $request->lead_name;
           $datastatus['contact_number'] = str_replace(' ', '', $request->contact_number['main']); 
           $datastatus['country_code'] = $MainfinalcountryCode;
             
              
            
             $isStatusCaseClose =  DB::table('leads')->where('id',$id)->update($datastatus);

             if($request->hasfile('file_uploads'))
             {  
                foreach($request->file('file_uploads') as $file)
                {  
                    $name = $file->getClientOriginalName(); 
                    $file->move(public_path().'/files/', $name);  
                    $files = $name; 
                    DB::table('lead_gallerys')->insert([
                        'lead_id' => $id, 
                        'documents' => $request->documents ?? 3 ,
                        'images' =>  $files,
                        'uploaded_by' => Auth::user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                      
                }
             }
	
            //  Co FOllow Up Buddy Mail end Notification

            if ($ReminderUser->co_follow_up != $request->co_follow_up  && $request->co_follow_up != null) {

                 
               $employeeN = DB::table('employees')->where('user_id',$request->assign_employee_id)->first();  
               $employeeCoFollowUp = DB::table('employees')->where('user_id',$request->co_follow_up)->first(); 
               $employeeCoFollowUpRemove = DB::table('employees')->where('user_id',$ReminderUser->co_follow_up)->first();

             
                $emPrevious = DB::table('employees')
                          ->where('id',$ReminderUser->assign_employee_id)
                          ->first();
               //  $empNotification = User::where('id',$employeeN->user_id)->first();
                $coFollowUpNotification = User::where('id',$request->co_follow_up)->first();
                $RimdercoFollowUpNotification = User::where('id',$ReminderUser->co_follow_up)->first();
               //   dd($rwaNotification);
               if($coFollowUpNotification)
               {
                     $details = [
                        'subject' => $emPrevious->employee_name . "  made $employeeCoFollowUp->employee_name  Follow-Up Buddy | $ReminderUser->lead_name | $buyer_seller_customerType->name | $LeadAssinglaction->location | $ReminderUser->number_of_units " ." Units",
                        // 'greeting' => 'Hi '.$datastatus['lead_name'],
                        'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                        'thanks' => 'Thank you for choosing Homent.',  
                        'leads' => $ReminderUser->lead_name,
                        'EmployeeName' => Auth::user()->id,
                        'leads_status' => $request->lead_status ?? $ReminderUser->lead_status,
                        'leadsID' =>  $id,
                        'property_requirement' => $buyer_seller_customerType->name,
                        'location' => $LeadAssinglaction->location, 
                        'number_of_units' => $ReminderUser->number_of_units . " Unit",
                        'privios_emp' => $ReminderUser->assign_employee_id,
                        'budget' => $request->budget, 
                        'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                        'current_empid' => $request->assign_employee_id,
                         'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
                         'co_follow_up' => $request->co_follow_up ?? null,
                         'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                        'co_follow_up' => $request->co_follow_up ?? null,
                  ]; 

                  
                  
                  Notification::sendNow($coFollowUpNotification,new WelcomeNotification($details));
                  }

                  if($RimdercoFollowUpNotification)
                  {
                     
                     $details = [
                        'subject' => $emPrevious->employee_name ." removed $employeeCoFollowUpRemove->employee_name  "." Follow-Up Buddy | $ReminderUser->lead_name | .$buyer_seller_customerType->name | $LeadAssinglaction->location | $ReminderUser->number_of_units " ." Units",
                        // 'greeting' => 'Hi '.$datastatus['lead_name'],
                        'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                        'thanks' => 'Thank you for choosing Homent.',  
                        'leads' => $ReminderUser->lead_name,
                        'EmployeeName' => Auth::user()->id,
                        'leads_status' => $request->lead_status ?? $ReminderUser->lead_status,
                        'leadsID' =>  $id,
                        'property_requirement' => $buyer_seller_customerType->name,
                        'location' => $LeadAssinglaction->location, 
                        'number_of_units' => $ReminderUser->number_of_units . " Unit" ,
                        'privios_emp' => $ReminderUser->assign_employee_id,
                        'budget' => $request->budget, 
                        'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                        'current_empid' => $request->assign_employee_id,
                         'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
                         'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                        'co_follow_up' => $request->co_follow_up ?? null,
                     ]; 


                      
                  
                     Notification::sendNow($RimdercoFollowUpNotification,new WelcomeNotification($details));
                  }
               }

 

            //  RWA Notification end mail
             if ($ReminderUser->rwa != $request->rwa && $request->rwa != null) {

                
                
               $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
               $employeeRwa = DB::table('employees')->where('user_id',$request->rwa)->first(); 
               $employeeRwaRemove = DB::table('employees')->where('user_id',$ReminderUser->rwa)->first();
                $emPrevious = DB::table('employees')
                          ->where('id',$ReminderUser->assign_employee_id)
                          ->first(); 

                $rwaNotification = User::where('id',$request->rwa)->first();
                $RimderRwaNotification = User::where('id',$ReminderUser->rwa)->first();
               if ($rwaNotification) {
                 
                  $details = [
                     'subject' =>  "CP " .  Auth::user()->name. " Assigned lead to $employeeRwa->employee_name | $ReminderUser->lead_name | $buyer_seller_customerType->name | $LeadAssinglaction->location | $ReminderUser->number_of_units ".'Unit' ,
                     // 'greeting' => 'Hi '.$datastatus['lead_name'],
                     'body' => ' This is an automated email as the existing lead of '. $emPrevious->employee_name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                         'thanks' => 'Thank you for choosing Homent.',  
                         'leads' => $ReminderUser->lead_name,
                         'EmployeeName' => Auth::user()->id,
                         'leads_status' => $request->lead_status ?? $ReminderUser->lead_status,
                         'leadsID' =>  $id,
                         'property_requirement' => $buyer_seller_customerType->name,
                         'location' => $LeadAssinglaction->location, 
                         'number_of_units' => $ReminderUser->number_of_units . " Unit" ,
                         'privios_emp' => $ReminderUser->assign_employee_id,
                         'budget' => $request->budget, 
                         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                         'current_empid' => $request->assign_employee_id,
                          'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
                          'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                         'co_follow_up' => $request->co_follow_up ?? null,
                ]; 
                
                
                 Notification::sendNow($rwaNotification,new WelcomeNotification($details));
               } 
               if ($RimderRwaNotification) {
                 
                  $details = [
                     'subject' =>  "CP " . Auth::user()->name. " Removed $employeeRwaRemove->employee_name | $ReminderUser->lead_name | $buyer_seller_customerType->name | $LeadAssinglaction->location | $ReminderUser->number_of_units " .'Unit' ,
                     // 'greeting' => 'Hi '.$datastatus['lead_name'],
                     'body' => ' This is an automated email as the existing lead of '. Auth::user()->name .' has been assigned to you for follow-up. There is no need to reply to this email', 
                         'thanks' => 'Thank you for choosing Homent.',  
                         'leads' => $ReminderUser->lead_name,
                         'EmployeeName' => Auth::user()->id,
                         'leads_status' => $request->lead_status ?? $ReminderUser->lead_status,
                         'leadsID' =>  $id,
                         'property_requirement' => $buyer_seller_customerType->name,
                         'location' => $LeadAssinglaction->location, 
                         'number_of_units' => $ReminderUser->number_of_units . " Unit" ,
                         'privios_emp' => $ReminderUser->assign_employee_id,
                         'budget' => $request->budget, 
                         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
                         'current_empid' => $request->assign_employee_id,
                          'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
                          'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
                          'co_follow_up' => $request->co_follow_up ?? null,
                ]; 

                
                 Notification::sendNow($RimderRwaNotification,new WelcomeNotification($details));
               }  
               
                
               }
             
             //  Status Update Change Employee Mail & Notification

            //  if ($ReminderUser->assign_employee_id != $request->assign_employee_id) {
                
            //   $employeeN = DB::table('employees')->where('id',$request->assign_employee_id)->first();  
            //    $emPrevious = DB::table('employees')
            //              ->where('id',$ReminderUser->assign_employee_id)
            //              ->first();
            //    $empNotification = User::where('id',isset($employeeN->user_id))->first();  
            //    $details = [
            //        'subject' => $emPrevious->employee_name. ' Lead Assigned to ' . isset($employeeN->employee_name) ? $employeeN->employee_name : '' .' | '.$ReminderUser->lead_name.' | '.$buyer_seller_customerType->name.' | '. $LeadAssinglaction->location . ' | ' . $ReminderUser->number_of_units . 'Unit'. ' | '  .$ReminderUser->budget,
            //        // 'greeting' => 'Hi '.$datastatus['lead_name'],
            //        'body' => ' This is an automated email as the existing lead of '. isset($employeeN->employee_name) .' has been assigned to you for follow-up. There is no need to reply to this email', 
            //        'thanks' => 'Thank you for choosing Homent.',  
            //        'leads' => $request->lead_name,
            //        'EmployeeName' => Auth::user()->id,
            //        'leads_status' => $request->lead_status,
            //        'leadsID' =>  $id,
            //        'property_requirement' => $buyer_seller_customerType->name,
            //        'location' => $LeadAssinglaction->location, 
            //        'number_of_units' => $request->number_of_units,
            //        'privios_emp' => $ReminderUser->assign_employee_id,
            //        'budget' => $request->budget, 
            //         'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($id),
            //        'current_empid' => $request->assign_employee_id,
            //         'cc' => $ReminderUser->buyer_seller != $request->buyer_seller ?  ['pradeepmishra@homents.in',isset($IsSeller) ? $IsSeller : []] : 					    ['pradeepmishra@homents.in'] , 
            //         'messageUpdateBy' => 'Lead Updated by '. \Auth::user()->name,
            //         'co_follow_up' => $request->co_follow_up ?? null,
            //   ]; 
           
            
            //       if (Auth::user()->roles_id != 10) {
            //          Notification::sendNow([$empNotification,$AdminNotification],new WelcomeNotification($details));
            //       } 
            //   }

             if($datastatus['common_pool_status'] == 1)
             {
             	return redirect()->route('leads-index')->with('success','Case Closed Succesfully');
              // return redirect()->route('common-pool')->with('success','Case Closed Succesfully');
             }
             elseif($datastatus['lead_status'] == 14 && Auth::user()->roles_id == 1)
             {
               return redirect()->route('booking-index')->with('success','Booking Confirm Succesfully');
             }
             elseif($datastatus['lead_status'] == 14)
             {
               return redirect()->route('leads-index')->with('success','Booking Confirm Succesfully');
             }
             else
             {
               //return redirect()->back()->with('success','status update');
               return redirect()->route('lead-status',encrypt($id))->with('success','status update');
             }


             
              
            }
            else
            {
               return "Error";
            }
      }

      public function edit($id)
      {
         $value = DB::table('leads')->where('id',$id)->first();

         return response()->json([
            'status' => $id,
         ]);
      }

      public function LockScreen()
      {
         session(['locked' => 'true']);
         return view('pages.lockscreen.index');
      }
      public function Unlock(Request $request)
      {
         
         $password = $request->password;

         $this->validate($request, [
               'password' => 'required|string',
         ],[
               'password.required' => 'Password field is required.',
         ]);

         if(\Hash::check($password, \Auth::user()->password)){
                session()->forget('locked');

               //  session()->put('last_request',time());  
               return redirect('/dashboard');
         }

         return redirect()->route('lock-screen')->with('lockscreen','Password does not match. Please try again.');
      }
      
      
      public function our_backup_database()
      {

         $filename = "dbbackup-" . Carbon::now()->format('Y-m-d') . ".sql";
   
         $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;  
         exec($command);
         
         

         return redirect()->route('admin-setting')->with('success','Database Backup Successfully' ); 
      }
      
      public function LocationDownload()
     {
         return Excel::download(new LocationExport, 'location.xlsx');
     }

     public function generateCSV()
    {
        $data = [
            ['Name', 'Given Name', 'Additional Name', 'Family Name', 'Yomi Name', 'Given Name Yomi', 'Additional Name Yomi', 'Family Name Yomi', 'Name Prefix', 'Name suffix', 'Initials', 'Nickname', 'Short Name', 'Maiden Name', 'Birthday', 'Gender', 'Location', 'Billing Information', 'Directory Server', 'Mileage', 'Occupation', 'Hobby', 'Sensitivity', 'Priority', 'Subject', 'Notes', 'Language', 'Photo', 'Group MenberShip', 'E-mail 1 Type', 'E-mail 1- value', 'Phone 1- Type', 'Phone 1-value', 'Phone 2-Type', 'Phone 2-value','link'],
           
        ];

        $csvData = '';

        $leads =DB::table('leads')->where('common_pool_status',0)->get();
        foreach ($leads as $key => $lead) {
         $buyer_seller_name =DB::table('buyer_sellers')->where('id',$lead->buyer_seller)->first();
	$LeadUrl = url('lead-status/'.encrypt($lead->id)); 
	
         $ecportCSV =array(
            $buyer_seller_name->name,$buyer_seller_name->name,'','','','','','','',$lead->lead_name,'','','','','','','','','','','','','','','','','','','','Home',$lead->lead_email,'Home',$lead->contact_number,'Home',$lead->alt_no_Whatsapp.':::'.$lead->alt_no_Whatsapp_2,$LeadUrl
         );
         $data[]=$ecportCSV;
        }

        // Convert data to CSV format
        foreach ($data as $row) {
            $csvData .= implode(',', $row) . "\n";
        }

        $fileName = 'contact.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return Response::make($csvData, 200, $headers);
    }
    
    
     public function Productivity()
     {
	      $employeeDeshboard = DB::table('employees')
      ->where('organisation_leave',0)
      ->orderBy('employee_name', 'ASC')->get();


      $filterType = 0;

   foreach ($employeeDeshboard as $employeeDash) {
         $empLead = DB::table('leads')
         ->where('assign_employee_id', $employeeDash->id)
         ->first();
         
         $currentDate = Carbon::today();
         $oneWeekAgo = $currentDate->subWeek(); 
         $EmpLeadCount = DB::table('leads')
         ->where('assign_employee_id', $employeeDash->id)
         ->whereDate('leads.created_at', Carbon::today())
         // ->whereDate('leads.created_at','>=', $oneWeekAgo)
         // ->whereDate('created_at', '>=', $oneWeekAgo)
         // ->whereDate('created_at', '<=', $currentDate)
         ->where('common_pool_status', '!=', 1)
         ->whereNotIn('lead_status', [14, 16, 8, 9, 10, 11, 12])
         ->orderByDesc('count') // Add this line to sort by count in descending order
         ->count();

      // dd($EmpLeadCount);

         $date =  Carbon::today();
         $data = array(
            'leadCount' => $EmpLeadCount,
            'emp_name' => $employeeDash->employee_name,
            'emp_location' => $employeeDash->employee_location,
            'emaployee_id' => $employeeDash->id,
            'emaployee_user_id'  => $employeeDash->user_id
         );

         $employeeData[] = $data;
      }

      return view('pages.admin.emp-productivity', compact(['employeeData','date','filterType','empLead']));
     }


     public function ProductivityStatus($id)
     {  
      $LeadStatus = DB::table('lead_statuses')->get();
      $employeeData = []; // Initialize the $employeeData array outside the loop
     	$date =  Carbon::today();
      $empLeadProd = DB::table('employees')
              ->where('employees.id', decrypt($id))
              ->first();

      
      if (!empty($empLeadProd)) {
          return view('pages.admin.productivity-status', compact(['empLeadProd','date']));
      } else {
          return redirect()->back()->with('error', 'Today Not Status Update');
      }
      
     }

     public function ProductivityLeadTable($id,$status_id,$filterType)
     {  

          $empLead = DB::table('leads')
         ->join('employees', 'employees.id', 'leads.assign_employee_id')
         ->select('leads.*', 'employees.employee_name')
         ->where('assign_employee_id',decrypt($id))
         ->first();

         $currentDate = Carbon::today();
         $ifFitler = $currentDate->subDays($filterType); 
     
         $leads = DB::table('leads')
             ->where('assign_employee_id', decrypt($id))
             ->where('lead_status', $status_id)
             ->join('employees', 'employees.id', 'leads.assign_employee_id')
             ->select('leads.*', 'employees.employee_name','employees.official_phone_number')  
             ->whereDate('leads.created_at', '>=', $ifFitler)
             ->get();
         
       
         return view('pages.admin.emp-productivity-leads', compact(['leads','empLead']));
          
     }

     public function getProductivityData(Request $request, $employee_id)
      {
         // Retrieve the selected employee ID from the route parameter
         // $employee_id = $request->employee_id; // Not needed because we are already passing it as a parameter

         // Fetch the updated data based on the selected employee ID
         // Modify this query according to your data structure
         $updatedData = DB::table('employees')->where('id', $employee_id)->get();

         // Return the updated data as JSON response
         return response()->json($updatedData);
      }
      
      
      public function EmployeeDaywiseProd($id,$from,$to){ 
          
          $LeadDayWise = DB::table('leads')
         ->where('leads.created_at', '>', $from)
         ->where('leads.created_at', '<', $to)
         //->where('assign_employee_id', decrypt($id))
         ->where('common_pool_status',0)
         ->orderBy('created_at', 'asc') 
          ->groupBy(\DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
         ->get();


         // dd($LeadDayWise);
          
         $empId = DB::table('employees')
            ->where('id',decrypt($id)) 
            ->first(); 

         return view('pages.admin.emp-daywise-prod',compact('LeadDayWise','from','to','id','empId')); 
       }
      
        public function ProductivityFilterEmployeeStatus(Request $request){

            $submit = $request['submit']; 
  
           if($submit == 'submit')
           { 
               //  $employeefilter = $request->employee; 
               //  $datefilter =$request->filter_type;
               //   if ($request->employee == null)
               //   {
                    
               //       return redirect()->back()->with('error','Please select anyone' );
               //   }
               //   else
               //   { 
 
               //       $empLeadProd = DB::table('employees')->where('id', $employeefilter)->first(); 
               //       $date = Carbon::now()->subDays($datefilter); 
                        
               //       if (!empty($empLeadProd)) {
               //          return view('pages.admin.productivity-status',compact(['empLeadProd','date'])); 
               //       } else {
               //             return redirect()->back()->with('error', 'Today Not Status Update');
               //       }
                        
               //    }
               
               $filterType = $request->input('filter_type');
               $employeeDeshboard = DB::table('employees')
               ->where('organisation_leave',0)
               ->orderBy('employee_name', 'ASC')->get();
         
            foreach ($employeeDeshboard as $employeeDash) {

               $startDate = Carbon::now()->subDays($filterType); 

              
            //   dd($startDate);
                  $empLead = DB::table('leads')
                  ->where('assign_employee_id', $employeeDash->id)
                  ->first();
          
                  $EmpLeadCount = DB::table('leads')
                  ->where('assign_employee_id', $employeeDash->id)
                  ->where('created_at', '>=', $startDate)
                  ->where('common_pool_status', '!=', 1) 
                  // ->orderByDesc('count') // Add this line to sort by count in descending order
                  ->count();
         
                //dd($EmpLeadCount);
         
                  $date =  $startDate;
                  $data = array(
                     'leadCount' => $EmpLeadCount,
                     'emp_name' => $employeeDash->employee_name,
                     'emp_location' => $employeeDash->employee_location,
                     'emaployee_id' => $employeeDash->id,
                      'emaployee_user_id'  => $employeeDash->user_id
                  );
         
                  $employeeData[] = $data;

                  // dd($employeeData);
               }
         
               return view('pages.admin.emp-productivity', compact(['employeeData','date','filterType','empLead']));
                
           }
           else
           {
              return redirect()->back();
           }
       }
       
       
       public function FromProductivityTo(Request $request)
         {
            $from = $request->input('from')." 00:00:00";
            $to = $request->input('to')." 23:59:00";

          

            $fromDate = Carbon::parse($request->from); // Assuming $request->from contains the "from" date
            $toDate = Carbon::parse($request->to);     // Assuming $request->to contains the "to" date

            $filterType = $toDate->diffInDays($fromDate);
 
               $employeeDeshboard = DB::table('employees')
               ->where('organisation_leave',0)
               ->orderBy('employee_name', 'ASC')->get();
         
            foreach ($employeeDeshboard as $employeeDash) {

                
                  $empLead = DB::table('leads')
                  ->where('assign_employee_id', $employeeDash->id)
                  ->first();
          
                  $EmpLeadCount = DB::table('leads')
                  ->where('assign_employee_id', $employeeDash->id)
                  ->where('created_at', '>=', $from)
                  ->where('created_at', '<=', $to)
                  ->where('common_pool_status', '!=', 1) 
                  // ->orderByDesc('count') // Add this line to sort by count in descending order
                  ->count();
         
                //dd($EmpLeadCount);
         
                
                  $data = array(
                     'leadCount' => $EmpLeadCount,
                     'emp_name' => $employeeDash->employee_name,
                     'emp_location' => $employeeDash->employee_location,
                     'emaployee_id' => $employeeDash->id,
                     'emaployee_user_id'  => $employeeDash->user_id
                  );
         
                  $employeeData[] = $data;

                  // dd($employeeData);
               }
         
               return view('pages.admin.productivity-from-to', compact(['employeeData','filterType','from','to','empLead']));
         }
       
       
        public function ForACropjob(){ 
         $add60mint = date("Y-m-d H:i", strtotime('+1 hours'));
         $add30mint =date("Y-m-d H:i", strtotime('+25 minutes')); 
         $reminders =DB::table('leads')->where('next_follow_up_date', 'like', '%' . $add60mint . '%')->get();
         // dd($add30mint);
         foreach ($reminders as $reminder) {
            
            $employee = DB::table('employees')
                ->where('id', $reminder->assign_employee_id)
                ->first();

            $empNotification = User::where('id', $employee->user_id)->first();

            $buyerSellerName = DB::table('leads')
                ->join('buyer_sellers', 'leads.buyer_seller', '=', 'buyer_sellers.id')
                ->where('buyer_sellers.id', $reminder->buyer_seller)
                ->select('buyer_sellers.name')
                ->first();

            $leadAssinglaction = DB::table('locations')
                ->join('leads', 'leads.location_of_leads', '=', 'locations.id')
                ->where('locations.id', $reminder->location_of_leads)
                ->select('locations.location')
                ->first();

            $details = [
                'subject' => 'Next Follow Up Notification',
                'greeting' => 'Hi ' . $employee->employee_name,
                'body' => 'This is an automated email as you have been assigned a new lead. There is no need to reply to this email.',
                'leads' => $reminder->lead_name,
                'leads_status' => $reminder->lead_status,
                'property_requirement' => $buyerSellerName->name,
                'number_of_units' => $reminder->number_of_units,
                'leadsID' => $reminder->id,
                'privios_emp' => $reminder->assign_employee_id,
                'current_empid' => $reminder->assign_employee_id,
                'budget' => 'NA',
                'EmployeeName' => $reminder->assign_employee_id,
                'location' => $leadAssinglaction->location,
                 'lead_info' => 'https://crm.homents.in/lead-status/'.encrypt($reminder->id),
                'cc' => ['pradeepmishra@homents.in'],
            ];

            Notification::sendNow($empNotification, new WelcomeNotification($details));
         }
      }
      
      
      public function employeeLeadStatus($id,$status_id)
     {  

           if ($status_id == "expired-leads") {

            $empIdStatus = DB::table('leads')
            ->join('employees', 'employees.id', 'leads.assign_employee_id')
            ->select('leads.*', 'employees.employee_name')
            ->where('assign_employee_id',decrypt($id))
            ->first();

            $currentDate = Carbon::now();

            $leads = DB::table('leads') 
            ->join('employees', 'employees.id', 'leads.assign_employee_id')
            ->select('leads.*', 'employees.employee_name','employees.official_phone_number','employees.emp_country_code')
            ->where('assign_employee_id',decrypt($id)) 
            ->where('next_follow_up_date','<',$currentDate)
            ->where('common_pool_status',0)
             ->where('lead_status','!=', 14)
            ->get();
 
         }
         elseif($status_id == "co-follow-up")
         {
               //$empIdStatus = DB::table('employees')->where('user_id',Auth::user()->id)->first();

             
               $empID = DB::table('leads')
               ->where('common_pool_status',0)
               ->where('assign_employee_id',decrypt($id))
               ->first();

               $empIdStatus = DB::table('employees')->where('id',$empID->assign_employee_id)->first();

               // dd($empIdStatusID->user_id);
               
               $empLead = DB::table('leads')
               ->join('employees', 'employees.id', 'leads.assign_employee_id')
               ->select('leads.*', 'employees.employee_name','employees.official_phone_number')
               ->where('co_follow_up', $empIdStatus->user_id)
               ->where('common_pool_status',0) 
               ->first();

            // dd($empLead);
               $leads = DB::table('leads')
               ->join('employees', 'employees.id', 'leads.assign_employee_id')
               ->select('leads.*', 'employees.employee_name','employees.official_phone_number','employees.emp_country_code')
               ->where('co_follow_up', $empIdStatus->user_id)
               ->whereNotIn('lead_status', [8, 9, 10, 11, 12])
               ->get();

               //  dd($leads);
         } 
         elseif($status_id == "channel-partner")
         {
              
               $empID = DB::table('leads')
               ->where('common_pool_status',0)
               ->where('assign_employee_id',decrypt($id))
               ->first();

                

               $empIdStatus = DB::table('employees')->where('id',$empID->assign_employee_id)->first();

                  
               
               //  $empLead = DB::table('leads')
               //  ->join('employees', 'employees.id', 'leads.assign_employee_id')
               //  ->select('leads.*', 'employees.employee_name','employees.official_phone_number')
               //  ->where('rwa', $empIdStatus->rwa)
               //  ->where('assign_employee_id',decrypt($id))
               //  ->where('common_pool_status',0)
               //  ->get();

               //   dd($empLead);

                 
               $leads = DB::table('leads')
               ->join('employees', 'employees.id', 'leads.assign_employee_id')
               ->select('leads.*', 'employees.employee_name','employees.official_phone_number','employees.emp_country_code')
               ->where('rwa','!=',null)   
               ->where('assign_employee_id',decrypt($id))
               ->groupBy('rwa')->get();

               
               //   dd($leads);
         }
         else
           {
 
            $empIdStatus = DB::table('leads')
            ->join('employees', 'employees.id', 'leads.assign_employee_id')
            ->select('leads.*', 'employees.employee_name')
            ->where('assign_employee_id',decrypt($id))
            ->first();
   
            $currentDate = Carbon::today();
            $oneWeekAgo = $currentDate->subWeek(); 
   
            // dd($empLead->lead_status);
            $leads = DB::table('leads')
            // ->whereDate('leads.created_at', Carbon::today())
            // ->whereDate('leads.created_at','>=', $oneWeekAgo)
            ->where('assign_employee_id',decrypt($id))
             ->where('lead_status',$status_id)
            ->join('employees', 'employees.id', 'leads.assign_employee_id')
            ->select('leads.*', 'employees.employee_name','employees.official_phone_number','employees.emp_country_code')
            ->where('common_pool_status',0)
            ->orderBy('leads.next_follow_up_date','DESC')
            ->get();
          }
         
 
         return view('pages.admin.emp-productivity-leads', compact(['leads','empIdStatus']));
          
     }

      
        public function isLeadConfirmedDataSend()
     {
         $Confireds = DB::table('leads')->where('lead_status',14)->get();

         foreach($Confireds as $Confired)
         {  
           
            $bookingConfirm =  DB::table('booking_confirms')->insert([
               'lead_id' => $Confired->id,
               'booking_status' => $Confired->lead_status,
               'booking_date' =>  $Confired->booking_Date,
               'booking_project' => $Confired->booking_project,
               'booking_amount' => $Confired->booking_amount ?? null,
               'lead_assign_to' => $Confired->assign_employee_id,
               'buying_location' => $Confired->location_of_leads ?? null,
               'rwa' => $Confired->rwa ?? null, 
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
            ]);
            

         }

         return "done";
     }
     
     
     public function createBackup()
      {
 	$backupFileName = 'crm_backup' . now()->format('Y-m-d') . '.sql';
         $backupFilePath = $backupFileName;

         $databaseName = config('database.connections.mysql.database');
         $dbUsername = config('database.connections.mysql.username');
         $dbPassword = config('database.connections.mysql.password');

         exec("mysqldump -u$dbUsername -p$dbPassword $databaseName > $backupFilePath");

         // Upload the backup to Google Drive
         // $googleDrivePath = 'backup/' . $backupFileName;
         Storage::disk('google')->put($backupFileName, file_get_contents($backupFilePath));

         // Delete the local backup file after successful upload
         unlink($backupFilePath);

         return response()->json(['message' => 'Database backup created and uploaded to Google Drive successfully.']);
      }

     
     
         public function TrailLogsFilter(Request $request)
    	{
       
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date'); 
        $GlobalSearchss = DB::table('employees')->get(); 

         foreach ($GlobalSearchss as $GlobalSearchs)
         { 
            $GlobalSearchs = DB::table('global_search') 
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate) 
            ->get();  
         } 
         if ($GlobalSearchs->isEmpty()) {
            \Session::flash('success', 'Data Not Found'); 
            return redirect()->back(); 
            
         }
         return view('pages.admin.global-search',compact(['GlobalSearchs']));
         
    	}
    	
    	public function TrailsLogReportsDownload()
     	{
         return Excel::download(new TrailLogsReports, 'TrailsLogsReports.xlsx');
     	}
     	
     	public function IsLocationFilter(Request $request,$id)
     {
         $emp = DB::table('employees')->where('id', decrypt($id))->first();

         $locationFilter = $request->locationFilter;
         
         $employeeLocation = explode(',', $emp->employee_location);
         
         $locationWise = DB::table('locations')
            ->join('employees', function ($join) use ($employeeLocation, $id) {
               $join->on('employees.employee_location', 'LIKE', DB::raw('CONCAT("%", locations.id, "%")'))
                     ->where('employees.id', decrypt($id));
            })
            ->whereIn('locations.id', $employeeLocation)
            ->when($locationFilter, function ($query) use ($locationFilter) {
               $query->where('locations.id', $locationFilter);
            })
            ->select('locations.location', 'locations.id', 'employees.id as eid', 'employees.employee_name')
            ->get();
         
         if ($locationWise->isEmpty()) {
            return redirect()->back()->with('message', 'No results found for the selected location.');
         }
         
         return view('pages.admin.emp-location-wise-lead', compact(['emp', 'locationWise']));
      
     } 
     
     public function IsEmployeeFilter(Request $request)
     {
 
         $employeeDeshboard = DB::table('employees')
         ->where('id',$request->empFilter) 
         ->get();    

       foreach($employeeDeshboard as $employeeDash)
       {
         $empLead = DB::table('leads')->where('assign_employee_id',$employeeDash->id)->first();
         $EmpLeadCount = DB::table('leads')
         ->where('assign_employee_id',$employeeDash->id)
         ->where('common_pool_status', '!=' ,1)
         ->where('lead_status', '!=' ,14)
         ->where('lead_status', '!=' ,16) 
         ->where('lead_status', '!=' ,8) 
         ->where('lead_status', '!=' ,9) 
         ->where('lead_status', '!=' ,10) 
         ->where('lead_status', '!=' ,11) 
         ->where('lead_status', '!=' ,12)->count();
 
         $data = array( 
            'leadCount' => $EmpLeadCount, 
            'emp_name' => $employeeDash->employee_name,
            'emp_location' => $employeeDash->employee_location,
            'emaployee_id' => $employeeDash->id
             );  

            $employeeData[] = $data; 
       }

       return view('pages.admin.employee-dashboard',compact('employeeData'));
 
 
     }
     
      public function exportEmployeeReports($id,$from, $to)
     {
      
         return Excel::download(new EmployeeProductivityReports($id,$from,$to), 'employee_productivity_reports.xlsx');
     }
     
     public function GetNotification()
     {
      $notificationData = auth()->user()->notifications()->paginate(30);
      return view('pages.admin.notification',compact('notificationData'));
     }

     public function MTDSendMail()
    {
       

      
        $email = DB::table('users')->where('roles_id',1)->pluck('email')->toArray();
      
         $today = now()->format('d-m-y');
         $yesterday = now()->subDay(1)->format('Y-m-d','00:00:00');
         $yesterday2 = now()->subDay(1)->format('Y-m-d','23:59:00');
             
       $MTDSendMail = \DB::table('leads')
          ->leftjoin('employees', 'employees.id', '=', 'leads.assign_employee_id') 
          ->whereDate('leads.created_at','>=' , $yesterday)
          ->whereDate('leads.created_at','<=', $yesterday2) 
          ->orderBy('leads.created_at', 'asc')
          ->select(
             'leads.lead_name',
             'leads.lead_status',
             'leads.assign_employee_id',
             'employees.employee_name',
             'employees.user_id',
             'leads.created_at', 
             'employees.official_email'
          ) 
            ->groupBy('employees.employee_name')
          ->get();

           
         foreach ($MTDSendMail as $employee) {
         
            Mail::to($employee->official_email)->send(new MTDEmployeeSendMail($employee));
          }     
        
        Mail::to($email)->send(new MTDSendMail($MTDSendMail));

        DB::table('cron_test')->insert([
            'status' => 'Daily Mail Send',
            'created_at' => new \DateTime('now'),
            'updated_at' => new \DateTime('now'),
        ]);
   
        dd("Mail has been sent successfully");
    }

    public function MTDMonthlyMailReports()
    {
      $email = DB::table('users')->where('roles_id', 1)->pluck('email')->toArray();
 
      
      $MTDMonthlyMailReports = \DB::table('leads')
          ->leftjoin('employees', 'employees.id', '=', 'leads.assign_employee_id')
          ->whereMonth('leads.created_at', '=', now()->month) // Filter by the current month
         //  ->where('employees.organisation_leave',0)
          ->orderBy('leads.created_at', 'asc')
          ->select(
              'leads.lead_name',
              'leads.lead_status',
              'leads.assign_employee_id',
              'employees.employee_name',
              'employees.user_id',
              'leads.created_at',
              'employees.official_email'
          )
          ->groupBy('employees.employee_name')
          ->get();

       
          
           foreach ($MTDMonthlyMailReports as $employee) {
              Mail::to($employee->official_email)->send(new MTDMonthlyEmployeeReport($employee));
          }    

        
        Mail::to($email)->send(new MTDMonthlyRepot($MTDMonthlyMailReports));

        DB::table('cron_test')->insert([
         'status' => 'Monthly Mail Send',
         'created_at' => new \DateTime('now'),
         'updated_at' => new \DateTime('now'),
     ]);
   
        dd("MTD Monthly Mail Reports has been sent successfully");
    }


    public function CallHistory($id)
    {

      $GetLeadData = DB::table('leads')
      ->join('employees', 'employees.id', 'leads.assign_employee_id')
      ->select('employees.official_phone_number','employees.employee_name','leads.lead_name','leads.contact_number')
      ->where('leads.id',decrypt($id))->first();
      
      $LeadCallHistory = DB::table('call_logs') 
      ->where('callernumber', $GetLeadData->contact_number)
      // ->where('agentnumber',$GetLeadData->official_phone_number)
      ->latest() 
      ->paginate(50); 

      return view('pages.leads.call-history',compact('LeadCallHistory','GetLeadData'));
    }

    public function indexLink()  
    {  
        $shortLinks = DB::table('short_links')->latest()->get();  
     
        return view('pages.admin.shor-link', compact('shortLinks'));  
    } 

    public function storeLink(Request $request)  
    {  
 
        $request->validate([  
            'link' => 'required|url'  
        ]);  
        
        $link = $request->link;
        $code = \Str::random(6);;
        
        DB::table('short_links')->insert([
            'link' => $link,
            'code' => $code,
        ]);
    
        return redirect('generate-shorten-link')  
             ->with('success', 'Shorten Link Generated Successfully!');  
    }  

    public function shortenLinkFind($code)  
    {   
        $find = DB::table('short_links')->where('code', $code)->first();   
        
      //   return redirect($find->link);  
    }  
 
}
