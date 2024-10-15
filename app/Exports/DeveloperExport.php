<?php

namespace App\Exports;

use App\Models\Developer;
use Maatwebsite\Excel\Concerns\FromCollection;

class DeveloperExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \DB::table('name_of_developers')->select('name_of_developer')->get();
    }

    public function headings(): array
    {
        return [ 
            'Name', 
        ];
    }

}
