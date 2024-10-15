<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;

class BulkExport implements FromCollection, WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return  $LeadSheets = DB::table('lead_sheets')
        ->join('buyer_sellers', 'buyer_sellers.id', '=', 'lead_sheets.customer_type')
        ->join('lead_statuses', 'lead_statuses.id', '=', 'lead_sheets.lead_status')
        ->join('projects','projects.id', '=' ,'lead_sheets.existing_property')
        ->join('locations','locations.id', '=' ,'lead_sheets.location_of_leads')
        ->get();
    }

    public function headings(): array {
        return [
        "Date",
        "Bulk Status",
        "Contact Number",
        "Customer Name",
        "Alt Contact Number 1",
        'Alt Contact Name',
        'Alt Contact Number 2',
        'Customer Email',
        'Customer Type',
        'Customer Requirement',
        'Buying Location',
        'Source',
        'Lead Status',
        'Lead Type',
        'Number of Units',
        'Existing Property',
        'Customer Interaction',
        'Customer Profile'
        ];
    }

    public function map($row): array{
       
        // $LeadSheets = DB::table('lead_sheets')
        // ->join('buyer_sellers', 'buyer_sellers.id', '=', 'lead_sheets.customer_type')
        // ->join('lead_statuses', 'lead_statuses.id', '=', 'lead_sheets.lead_status')
        // ->join('projects','projects.id', '=' ,'lead_sheets.existing_property')
        // ->join('locations','locations.id', '=' ,'lead_sheets.location_of_leads')
        // ->select('locations.location')
        // ->get();
 
        
        $leadStatusName = DB::table('lead_statuses')  
            ->where('lead_statuses.id', $row->lead_status)
            ->select('name')
            ->first();
 
        
        $customerType = DB::table('buyer_sellers')  
            ->where('buyer_sellers.id', $row->customer_type)
            ->select('name')
            ->first();
              
        
        $location = DB::table('locations')
             ->where('id', $row->location_of_leads)
            ->select('location')
            ->first();

        $exiProject = DB::table('projects')
            ->whereIn('id', explode(',', $row->existing_property))
            ->select('project_name')
            ->get();  

        foreach($exiProject as $projectName)
        {
          
            $projectNames[] = $projectName->project_name;
        } 
        
           
        return $fields = [
            $row->date,  
            $row->is_exist == 1 ? 'Yes' : 'No', 
            // $row->country_code_bu,
            $row->contact_number,
            $row->lead_name,
            $row->alt_contact_number_1,
            $row->alt_contact_name,
            $row->alt_contact_number_2,
            $row->customer_email,
            $customerType->name,
            $row->project_type,
            $location->location,
            $row->source, 
            $leadStatusName->name,
            $row->lead_type_bifurcation,
            $row->number_of_units,
            implode(',', $projectNames),
            $row->customer_interaction,
            $row->customer_profile, 
       ]; 
    }

    
     
}
