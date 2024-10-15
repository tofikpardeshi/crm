<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Columns\Column;

class TrailLogsReports implements FromCollection,WithHeadings,WithMapping,WithStyles

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
         return \DB::table('global_search')->get();

        
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
        "Employee Name",
        "Search Number",
        "Count",
        "Status",
        "Date",
        ];
    }

    public function map($row): array{

        
          $Counts = DB::table('global_search')
          ->where('gs_mobile_number', $row->gs_mobile_number)
          ->count();

          $IsNumberExist = DB::table('leads')
          ->where('contact_number',$row->gs_mobile_number)->exists();


          $employeesName = DB::table('employees')
          ->where('user_id',$row->emp_id)
          ->select('employees.employee_name')
          ->first();
 
            
          $TrailsLogReporst = \DB::table('global_search')
          ->join('employees', 'employees.id','global_search.emp_id')
          ->where('employees.id',$row->emp_id)
          ->select('employees.employee_name','global_search.*')->get();

           
        
        return $fields = [
            $employeesName->employee_name, 
            $row->gs_mobile_number,
            $Counts, 
            $IsNumberExist == true ? "Yes" : "No" , 
            $row->created_at
       ];

       
    }
}
