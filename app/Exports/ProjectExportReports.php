<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithStyles; 
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectExportReports implements FromCollection,WithHeadings,WithMapping,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
              return DB::table('projects')
            // ->join('category','projects.project_category', '=', 'category.id')
            // ->join('teams','projects.name_of_developers', '=', 'teams.id')
            //   ->join('project_status','projects.project_status_id', '=', 'project_status.id')
            //  ->join('name_of_developers','projects.name_of_developers', '=', 'name_of_developers.id')
            //  ->join('designations','teams.designation', '=', 'designations.id')
            ->get(); 

        //    dd($project);


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
        "Project Name",
        "Registration Email",
        "Registration Contact Number",
        "Alternate Contact Number",
        'Category',
        'Sector', 
        'Location',
        'Project Type',
        'Rera Number',
        'Name of Developer',
        'No. of Units',
        'No. of Occupied Units',
        'No. of UnOccupied Units',
        'Total Occupancy %',
        'Project Launch Date',
        'Project Completion Date',
        'Project Website Link',
        'Project FB Group Link',
        'Project Status',
        'Size of Apartment',
        'Price of Apartment',
        //'Upload Document',
        'Sales Person Name',
        'Sales Person Email',
        'Sales Person Contact Number',
        'Alternate Contact Number',
        'Date of Birth',
        'Wedding Anniversary',
        'Designation',
        'Builder/CP/Individual'
        ];
    }

        	 


    public function map($row): array{

        
       
        // dd($row);
        //   $teamnmaes = DB::table('teams')
        //  ->join('builders','builders.id','teams.builder_id')
        //  ->where('builders.id',$row->builder_id)
        //   ->get();
        

        $categoryNmae = DB::table('category')->where('id',$row->project_category)->select('category_name')->first();
        $teamName = DB::table('teams')
        ->when($row->name_of_developers, function ($query) use ($row) {
            return $query->where('id', $row->name_of_developers);
        })
        ->first(); 
        $buildersName = DB::table('builders')
        ->when($teamName, function ($query) use ($teamName) {
            return $query->where('id', $teamName->builder_id);
        })
        ->select('name')
        ->first();
        $designationsName = DB::table('designations')
        ->when($teamName && $teamName->designation, function ($query) use ($teamName) {
            return $query->where('id', $teamName->designation);
        })
        ->select('designation_name')
        ->first();

        $nameOfDevelopersName =  DB::table('name_of_developers')->where('id',$row->name_of_developers)->select('name_of_developer') ->first(); 
        $projectStatusName = DB::table('project_status')
        ->when($row->project_status_id, function ($query) use ($row) {
            return $query->where('id', $row->project_status_id);
        })
        ->select('status_name')
        ->first();

        
       
        
         // ->join('category','projects.project_category', '=', 'category.id')
            // ->join('teams','projects.name_of_developers', '=', 'teams.id')
            //   ->join('project_status','projects.project_status_id', '=', 'project_status.id')
            //  ->join('name_of_developers','projects.name_of_developers', '=', 'name_of_developers.id')
            //  ->join('designations','teams.designation', '=', 'designations.id')

        // $EmpLocationListsArray = []; 
        //   foreach($teamnmaes as $emp){ 
        //       $EmpLocationListsArray = $emp->name;
                
        //   } 
 
        return $fields = [
            $row->project_name ?? null, 
            $row->email ?? null,
            $row->contact_number ?? null, 
            $row->alternate_contact_number ?? null,
            $categoryNmae->category_name ?? null, 
            $row->sector ?? null,
            $row->location ?? null,
            $row->project_type ?? null,
            $row->rera_number ?? null, 
            $nameOfDevelopersName->name_of_developer ?? null,
            $row->total_no_of_units ?? null,
            $row->total_no_of_occupied_units ?? null,
            $row->total_no_of_unoccupied_units ?? null,
            $row->total_occupancy ?? null,
            $row->project_launch_date ?? null,
            $row->project_completion_date ?? null,
            $row->project_website_link ?? null,
            $row->project_fb_group_link ?? null,
            $projectStatusName->status_name ?? null,
            $row->size_of_apartment ?? null,
            $row->price_of_apartment ?? null,
            // $row->assign_mumbers,
           // $row->project_image,
            // $row->project_status,
            $teamName->team_name ?? null,  
            $teamName->team_email ?? null,
              $teamName->team_phone_number ?? null, 
              $teamName->alternate_contact_number_team ?? null, 
              $teamName->date_of_birth ?? null, 
              $teamName->wedding_anniversary ?? null,
              $designationsName->designation_name ?? null, 
              $buildersName->name ?? null,
            //   $EmpLocationListsArray ?? '',
            // $row->created_at
       ];
}
}

