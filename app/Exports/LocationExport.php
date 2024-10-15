<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class LocationExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \DB::table('locations')->select('location')->get();
    }

    public function headings(): array
    {
        return [ 
            'Name', 
        ];
    }
}
