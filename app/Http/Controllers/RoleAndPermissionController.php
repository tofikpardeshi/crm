<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Permission_tag;
use DB;
use Carbon\Carbon; 


class RoleAndPermissionController extends Controller
{

        
     function __construct()
     {
           $this->middleware('permission:Leads', ['only' => ['index','CreateRole']]);
           $this->middleware('permission:Leads', ['only' => ['CreateRole','index']]);
           $this->middleware('permission:Leads', ['only' => ['EditRole','updateRole']]);
           $this->middleware('permission:Leads', ['only' => ['DeleteRole']]);
     }

    public function index()
    {
    
    	
        $permissions = Permission::all();
        if (\Auth::user()->roles_id == 1) {
            $roles = DB::table('roles')->latest()->get();
        } 
        else
        {
            $roles = DB::table('roles')
            ->where('id','!=', 1)->latest()->get();
        }
  
        $role_has_pasemission = DB::table('role_has_permissions')->get();
        return view('pages.roles.index',compact(['permissions','roles',
        'role_has_pasemission']));
    }

    public function roles_create()
    {
        $contactTypes = Role::all();
        $permissions = Permission::where('name','!=', 'Create')
        ->where('name','!=', 'Update')
        ->where('name','!=', 'View')
        ->where('name','!=', 'Lead Reports')
        ->where('name','!=', 'Employee Reports')
        ->where('name','!=', 'Project Reports')
        ->where('name','!=','Developer Reports')
        ->where('name','!=','Deal Confirm Reports')
        ->where('name','!=','Location Reports')
        ->where('name','!=','Common Pool Reports')
        ->where('name','!=','Builder Reports')
        ->where('name','!=','Login Reports')
        ->where('name','!=','Employee Leads Reports') 
        ->where('name','!=', 'History-Update')
        ->where('name','!=', 'History-View')->get();
        return view('pages.roles.create',compact(['permissions','contactTypes']));
    }

     
    public function CreateRole(Request $request)
    {
        $this->validate($request, [
            'addRole' => 'required|unique:roles,name',
            'permission' => 'required',
            'role_access' => 'required'
        ],
        [
            'addRole.required' => 'Enter a new Role Name to add.',
            'permission.required' => 'Select Permission from the list provided.',
            'role_access.required' => 'Select Role Access from the dropdown.'
        ]);
        
         

         $role = Role::create([
             'name' => $request->get('addRole'),
             'created_by' => \Auth::user()->id, 
             'guard_name' => 'web',
            ]); 
            
         $role->syncPermissions($request->get('role_access'),$request->get('permission')); 
        //  $role->syncPermissions($request->input('role_access'),$request->input('permission'));
        return redirect()->route('roles_create')
                        ->with('success','Role created successfully');
    }

    public function DeleteRole($id)
    {   
        $role = Role::find($id); 
        if($role == ""){ 
            return redirect()->route('role-index');
        }
        else
        {

            $role->delete();
            $role_has = DB::table('role_has_permissions')
            ->where('role_id',$role->id)->delete();

            return redirect()->route('role-index')
                        ->with('delete','Role Delete Successfully');
        }

    }

    public function EditRole($id)
    {   
        $role = Role::find($id);
        $permission = Permission::where('name','!=', 'Create')
        ->where('name','!=', 'Update')
        ->where('name','!=', 'View')
        ->where('name','!=', 'History-Update')
        ->where('name','!=', 'History-View')->get();


        //  return $permission;
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->select('role_has_permissions.permission_id')->get();

        //   return $rolePermiss`ions;
            // ->pluck('role_has_permissions.permission_id')
            // ->all();
         // return $rolePermissions;
    
        return view('pages.roles.edit',compact('role','permission','rolePermissions'));
    }

    public function DetailsRole($id)
    {   
        //return $id;
        $roleDetails = Role::find($id);
        $permission_tag = Permission_tag::where('roleID',$roleDetails->id)->select('tag_name')->groupBy('tag_name')->get();

         //return $Permission_tag;
        if($roleDetails == ""){  
            return redirect()->route('role-index');
        }
        else
        {   
            $roleDetails = Role::find($id);
            $permissions = Permission::all();
            $role_has = DB::table('role_has_permissions')
            ->where('role_id',$roleDetails->id)->get();
             //return $role_has;
            return view('pages.roles.role-detail',compact(['permissions','roleDetails','role_has','permission_tag']));
        }

    } 

    public function updateRole(Request $request,$id)
    {
        // return $request;
        $this->validate($request, [ 
            'addRole' => 'required',
            'permission' => 'required',
            'role_access' => 'required'
        ],
        [
            'addRole.required' => 'Edit role field is required',
            'permission.required' => 'Edit permission field is required',
            'role_access.required' => 'Edit permission name field is required'
        ]); 
    
        $role = Role::find($id);
        $role->name = $request->input('addRole');
        $role->updated_by = \Auth::user()->id;
        $role->updated_at = Carbon::now();
        $role->save();
    
        $role->syncPermissions($request->input('role_access'),$request->input('permission'));
        // $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('role-index')
                        ->with('success','Role updated successfully');
          
    }

    
     

}

