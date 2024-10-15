<?php

namespace App\Imports;

use App\Models\LeadSheet;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use DB;


class LeadsImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use Importable, SkipsErrors;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $contactNumbers = [];

    public function model(array $row)
    {
        
        
        $dateValue = isset($row['date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('d-m-Y') : date('d-m-Y');

           
       

            $leadContactNumberExist = \DB::table('lead_sheets')
            ->where('contact_number', $row['contact_number'])
            ->exists();

            $prefix = substr($row['contact_number'], 0, 10);

            if ($prefix === '91' || $prefix === '1'|| $prefix === '62') {
                 $contactNumberRemove = $prefix;
            } else {
                $contactNumberRemove = $row['contact_number'];
            }

 
            $projectTypes = \DB::table('project_types')
            ->whereIn('id', explode(',', $row['customer_requirement'])) 
            ->pluck('project_type'); // Pluck only the 'project_type' column 
 
            $commaSeparatedTypes = implode(', ', $projectTypes->toArray());

            $source = \DB::table('source_types')
            ->where('id', $row['source'])
            ->select('source_types')
            ->first();

            $lead_type = \DB::table('lead_type_bifurcation')
            ->where('id', $row['lead_type_bifurcation'])
            ->select('lead_type_bifurcation')
            ->first();

            $number_of_units = \DB::table('number_of_units')
            ->where('id', $row['number_of_units'])
            ->select('number_of_units')
            ->first();

            
 
 

 
            
            if ($leadContactNumberExist) {
                // Update the "is_exist" column to 1 for rows with the matching contact number
                // DB::table('lead_sheets')
                //     ->where('contact_number', $row['contact_number'])
                //     ->update(['is_exist' => 1]);
            } 
            else
            {
                // dd($row['existing_property']);
                return new LeadSheet([
                    'date' => $dateValue,
                    'lead_name' => $row['lead_name'] ?? null,
                    'contact_number' => $contactNumberRemove,
                    'country_code_bu' => $row['country_code_bu'] == null ? '+91' : $row['country_code_bu'],
                    'project_type' => $commaSeparatedTypes ?? null,
                    'location_of_leads' => $row['location_of_leads'],
                    'source' => $source ? $source->source_types : null,
                    'lead_status' => $row['lead_status'],
                    'is_exist' =>   0, 
                    'lead_type_bifurcation' => $lead_type ? $lead_type->lead_type_bifurcation : null,
                    'number_of_units' => $number_of_units ?  $number_of_units->number_of_units : null,
                    // 'property_requirement' => $row['customer_type'], 
                    'customer_interaction' => isset($row['customer_interaction']) ? $row['customer_interaction'] : null,
                    // 'project_type',
                    'customer_profile' => $row['customer_profile'], 
                    'existing_property' => $row['existing_property'] ?? null, 
                     'customer_type' => $row['customer_type'], 
                    'alt_contact_number_1' => isset($row['alt_contact_number_1']) ? $row['alt_contact_number_1'] : null, 
                    'alt_contact_name' => $row['alt_contact_name'], 
                    'alt_contact_number_2' => $row['alt_contact_number_2'], 
                    'customer_email' => $row['customer_email'], 
                ]);
            } 
        
    }
    

    private function contactNumberExists($contactNumber)
    {   
        return in_array($contactNumber, $this->contactNumbers);
    }

    private function addContactNumber($contactNumber)
    {
        $this->contactNumbers[] = $contactNumber;
    } 


    public function headings(): array
    {
        return [
            'Contact Number',
            'Customer Name',
            'Alt Contact Number 1',
            'Alt Contact Name',
            'Alt Contact Number 2',
            'Customer Email',
            'Customer Type',
            'Buying Location',
            'Source Lead Status',
            'Lead Type',
            'Existing Property',
            'Customer Interaction',
            'Customer Profile', 
        ]; 

    }


    
    // public function onError(\Throwable $e)
    // {
    //     // Handle the exception how you'd like.
    // }

    public function rules(): array
    {
            return [
            '*.contact_number' => 'unique:lead_sheets,contact_number',
        ];

    }
    // public function onFailure(Failure ...$failures)
    //     {
    //         // Handle validation failures if needed
    //         // This method will be called when validation fails for a row
    //     }

        public function onFailureSkipped(Failure ...$failures)
        {
            // Handle skipped rows due to validation failure if needed
            // This method will be called after the failed rows have been skipped
        }

      
}

