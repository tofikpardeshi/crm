<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Columns\Column;

class CPBuilderExport implements FromCollection,WithHeadings,WithMapping,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \DB::table('teams')
        ->join('designations','designations.id', '=', 'teams.designation')
        ->join('name_of_developers','name_of_developers.id','teams.name_of_developer')
        ->join('projects','projects.id','teams.project_id') 
        ->join('builders','builders.id','teams.builder_id') 
        ->get();
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
        "Sales Person Name",
        "Sales Person Contact Number",
        "Alternate Contact Number",
        "Sales Person Official Email",
        'Saler Person Alt Email',
        'CC Email', 
        'Name of Developer',
        'Project Assign',
        'Designation',
        'Date of Birth',
        'Wedding Anniversary',
        'Builder/CP/Individualn',
        'Remark', 
        ];
    }

    public function map($row): array{
              
        // $projectName = DB::table('teams') 
        // ->join('designations','designations.id', '=', 'teams.designation')
        // ->select('designations.project_name')
        // ->first();

       
        $projectLists = DB::table('projects')
        ->whereIn('id',explode(',',$row->project_id))->get();
        
         
        
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

        
           
        return $fields = [
            $row->team_name, 
            $row->team_phone_number, 
            $row->alternate_contact_number_team, 
            $row->team_email, 
            $row->saler_person_alt_email, 
            $row->builder_cc_mail, 
            $row->name_of_developer, 
            $fixpenyakit, 
            $row->designation_name,  
            $row->date_of_birth, 
            $row->wedding_anniversary, 
            $row->name, 
            $row->remark, 
       ];

       dd($fields);
}
}
