<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Columns\Column;

class LoginExport implements FromCollection,WithHeadings,WithMapping,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \DB::table('log')
        ->join('users', 'users.id','log.user_id')
        ->select('users.name','log.*')->get();
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
        "Employee Ip",
        "Login Time/Date",
        ];
    }

    public function map($row): array{
       
        $Leads = \DB::table('log')
        ->join('users','users.id','log.user_id')
        ->select('users.name','log.*')->get();
           
        return $fields = [
            $row->name, 
            $row->ip_address,
            $row->created_at, 
       ];

       
    }
}
