<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Columns\Column;

class CommonPoolExport implements FromCollection,WithHeadings,WithMapping,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        
        // if (\Auth::user()->roles_id == 1) {
            return DB::table('leads')
            ->where('common_pool_status',1)
            ->join('buyer_sellers','leads.buyer_seller', '=', 'buyer_sellers.id')
            ->join('employees','employees.id', '=', 'leads.assign_employee_id')
            ->join('locations','locations.id', '=', 'leads.location_of_leads')
            ->join('lead_statuses','lead_statuses.id', '=', 'leads.lead_status')
            // ->join('projects','projects.id', '=', 'leads.booking_project')
            ->join('lead_type_bifurcation','lead_type_bifurcation.id', '=', 'leads.lead_type_bifurcation_id')
            ->get(); 
        // }
        // else {
        //     $empId = DB::table('employees')->where('user_id',\Auth::user()->id)->first();
        //    // dd($empId);
        //     return DB::table('leads')
        //     ->join('buyer_sellers','leads.buyer_seller', '=', 'buyer_sellers.id')
        //     ->join('employees','employees.id', '=', 'leads.assign_employee_id')
        //     ->join('locations','locations.id', '=', 'leads.location_of_leads')
        //     ->join('lead_statuses','lead_statuses.id', '=', 'leads.lead_status')
        //     // ->join('projects','projects.id', '=', 'leads.booking_project')
        //     ->join('lead_type_bifurcation','lead_type_bifurcation.id', '=', 'leads.lead_type_bifurcation_id')
        //     ->where('leads.assign_employee_id',$empId->id)
        //     ->get(); 
        // }
               
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
        "Lead Generation Date",
        "Contact Number",
        "Customer Name",
        "Alt Contact Number 1",
        'Alt Contact Name',
        'Alt Contact Number 2', 
        'Customer Email',
        'Customer Type',
        'Rent Budget',
        'Customer Requirement',
        'Lead Assigned To',
        'Buying Location',
        'Source',
        'Lead Status',
        'Lead Type',
        'Number of Units',
        'Project Discussed',
        'Location of Customer',
        'Budget for Property',
        'Investment or End User',
        'Regular Investor',
        'Next Follow Up Date',
        'Relationship Contact Number',
        'Relationship Contact Name',
        // 'Relationship',
        'Booking Date',
        'Booking Project',
        'Booking Amount',
        'Reference Name',
        'Reference Contact Number',
        'Date of Birth',
        'Wedding Anniversary',
        'Customer Interaction',
        'Customer Profile',
        // 'Featured Lead', 
        ];
    }

    public function map($row): array{
        $Leads = DB::table('leads')
        ->where('project_id',$row->project_id)
        ->where('booking_project',$row->booking_project)->first(); 

        $selected = explode(',', $Leads->project_id); 
        $projectLists = DB::table('projects')->whereIn('id',$selected)->get();
        
        
        
        $listpenyakit = [];
          $fixpenyakit = "";
          foreach($projectLists as $penyakit){ 
             array_push($listpenyakit,$penyakit->project_name);  
          } 
          if (count($listpenyakit) > 1) {
              $fixpenyakit = implode(",",$listpenyakit); 
           } else {
               $fixpenyakit = implode($listpenyakit);
           }  

        //    booking project name 
        $selectedBookinNanme = explode(',', $Leads->booking_project); 
        $BokingProjectLists = DB::table('projects')->whereIn('id',$selectedBookinNanme)->get();
        $BokingProjectName = [];
        $projectName = "";
           foreach($BokingProjectLists as $BokingProject){ 
            array_push($BokingProjectName,$BokingProject->project_name);  
         } 
         if (count($BokingProjectName) > 1) {
             $projectName = implode(",",$BokingProjectName); 
          } else {
              $projectName = implode($BokingProjectName);
          } 
           
        return $fields = [
            $row->date, 
            $row->contact_number,
            $row->lead_name, 
            $row->alt_no_Whatsapp,
            $row->alt_contact_name,
            $row->alt_no_Whatsapp_2, 
            $row->lead_email,
            $row->name,
            $row->budget,
            $row->project_type, 
            $row->employee_name,
            $row->location,
            $row->source,
            $row->name,
            $row->lead_type_bifurcation,
            $row->number_of_units.' unit',
            $fixpenyakit,
            $row->location_of_client,
            $row->budget,
            $row->investment_or_end_user,
            $row->regular_investor,
            $row->next_follow_up_date,
            $row->emergeny_contact_number,
            $row->relationship,
            $row->booking_Date, 
            $projectName,
            // $row->booking_project,
            $row->booking_amount,
            $row->reference,
            $row->reference_contact_number,
            $row->dob,
            $row->wedding_anniversary,
            $row->customer_interaction,
            $row->about_customer,
            // $row->is_featured,

       ];

       dd($fields);
    }
}
