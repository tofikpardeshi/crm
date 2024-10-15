<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProject implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
           $this->id = $id;
    }

    
    public function collection()
    {
            return DB::table('project_historys')
            ->join('category','project_historys.project_category', '=', 'category.id')
            ->join('name_of_developers', 'project_historys.name_of_developers', '=', 'name_of_developers.id')
            ->where('project_historys.project_id',$this->id)
            ->select('project_historys.*','category.category_name','name_of_developers.name_of_developer')->get(); 
    }

    public function headings(): array {
        return [
        "Project Name",
        "Registration Email",
        "Registration Contact Numbe",
        "Alternate Contact Number",
        'Category',
        'Sector', 
        'Location',
        'Project Type',
        'Rera Number',
        'Name of Developer',
        'Updated At'
        ];
    }

    public function map($row): array{
        return $fields = [
            $row->project_name, 
            $row->email,
            $row->contact_number, 
            $row->alternate_contact_number,
            $row->category_name, 
            $row->sector,
            $row->location,
            $row->project_type,
            $row->rera_number, 
            $row->name_of_developer,
            $row->created_at
       ];
}
}