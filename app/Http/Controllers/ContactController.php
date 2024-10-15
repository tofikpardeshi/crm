<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
     { 
      $contacts = DB::table('contacts')
      ->join('roles', 'roles.id','contacts.role_id')
      ->select('contacts.*','roles.name')->get();
        return view('pages.contact.index',compact('contacts'));
     }

     public function CreateContactView()
     { 
        $roles = DB::table('roles')->get();
        $contactTypes = DB::table('contact_types')->get();
        return view('pages.contact.create',compact(['contactTypes','roles']));
     }

     public function CreateContact(Request $request)
     {
         $submit = $request['submit'];
         if($submit == 'submit')
         {
            $validation = $request->validate([
               'name' => 'required', 
               'email' => 'required',
               'contact_number' => 'required',
               'role_id' => 'required',
               'job_title' =>'required',
               'contact_type' => 'required'
            ]);

           $contact = new Contact;
           $contact->contact_name = $request->name;
           $contact->email =  $request->email;
           $contact->contact_number = $request->contact_number; 
           $contact->job_title = $request->job_title; 
           $contact->contact_type = $request->contact_type;
           $contact->role_id = $request->role_id; 
           $contact->status = 1;
           $contact->save();

           return redirect('create-contact')->with('success','Contact Cread Successfully');
         }
         else
         {
            return "error";
         }
     }

     public function EditContact($id)
     {
         $contacts = DB::table('contacts') 
         ->join('roles', 'roles.id','contacts.role_id')
         ->select('contacts.*','roles.name')->where('contacts.id', $id)->first(); 
         $roles = DB::table('roles')->get();
         $contactTypes = DB::table('contact_types')->get();
         return view('pages.contact.edit',compact(['contactTypes','roles','contacts'])); 
     }

     public function UpdateContact(Request $request, $id)
     { 
         $submit = $request['submit'];
         if($submit == 'submit')
         {
              
 
             $contact = array();
             $contact['contact_name'] = $request->name; 
             $contact['email'] =  $request->email;
             $contact['contact_number'] = $request->contact_number;
             $contact['job_title'] = $request->job_title;
             $contact['contact_type'] = $request->contact_type;
             $contact['role_id'] = $request->role_id;
             $contact['status'] = 1;  
 
             $data = DB::table('contacts')->where('id',$id)->update($contact);

             if($data == true)
            {
                return redirect('edit-leads/'.$id)->with('success','Contact Updated Successfully');
            }
            else
            {
                return redirect('edit-leads/'.$id)->with('success','Contact Nothing To Update');
            }
         }
    }
}
