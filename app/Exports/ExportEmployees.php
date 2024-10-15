<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportEmployees implements FromCollection,WithHeadings,WithMapping,WithStyles
{
//     protected $id;

//  function __construct($id) {
//         $this->id = $id;
//  }

 public function collection()
    {
        return DB::table('employees')
        ->join('users','users.id','employees.user_id')
        ->join('roles','roles.id','users.roles_id')
        ->join('departments','departments.id','employees.department')
       // ->join('locations','locations.id','employees.employee_location') 
        ->select('employees.*','users.email','users.roles_id','users.login_status','roles.name','departments.department_name')->get(); 
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 12]],
        ];
    }
 
    public function headings(): array {
        return [
            "Employee ID*",
            "Name",
            "Official Email *",
            "Personal Email", 
            'Role *', 
            'Official Phone Number',
            'Personal Phone Number',
            'Department',
            'Aadhaar Number',
            'PAN Number',
            'Date Of Joining',
            'Last working Date',
            'Date of Birth',
            'Marriage Anniversaryr',
            'Current Address',
            'Permanent Address',
            'Education Background',
            'Blood Group*',
            'Emergency Contact Name',
            'Emergency Contact Number',
            'Relationship',
            'Location*',
            // 'Login Status',
            // 'Choose Image',
            // 'Lead Assignment ',
            // 'Leave Organisation'
        ];
    }

    public function map($row): array{
        
        $EmpLocation = DB::table('employees')
        ->where('employee_location',$row->employee_location)->first(); 
        
        $selected = explode(',', $EmpLocation->employee_location);  
        $EmpLocationLists = DB::table('locations')->whereIn('id',$selected)->get();
       // dd($EmpLocationLists);
        $EmpLocationListsArray = [];
         $EmpLocationName = "";
          foreach($EmpLocationLists as $emp){ 
             array_push($EmpLocationListsArray,$emp->location);  
          } 
          if (count($EmpLocationListsArray) > 1) {
              $EmpLocationName = implode(",",$EmpLocationListsArray); 
           } else {
               $EmpLocationName = implode($EmpLocationListsArray);
           } 

       return $fields = [
        $row->employeeID,
        $row->employee_name,
        $row->email,
        $row->official_email,  
        $row->name,
        $row->official_phone_number,
        $row->personal_phone_number,
        $row->department_name, 
        $row->addhar_number,
        $row->pan_Number, 
        $row->date_joining,
        $row->leaving_date,
        $row->date_of_brith,
        $row->marriage_anniversary,
        $row->current_address,
        $row->permanent_address, 
        $row->education_background,
        $row->blood_group, 
        $row->emergeny_contact_name,
        $row->emergeny_contact_number,
        $row->relationship,
        $EmpLocationName,
        // $row->login_status,
        // $row->lead_assignment,
        // $row->organisation_leave

      ];
    //  return fields;
 }
 
    
}
