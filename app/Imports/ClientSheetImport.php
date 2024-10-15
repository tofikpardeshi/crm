<?php

namespace App\Imports;

use App\Models\ClientSheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError; 
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Carbon\Carbon; 
// use Maatwebsite\Excel\Concerns\WithUpserts;
use Validator;



class ClientSheetImport implements ToModel ,WithHeadingRow,SkipsOnError,WithValidation
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
 
      
        return new ClientSheet([


            // 's_no' => $row['s_no'],
            // 'reference' => $row['reference'],
            // 'mode_of_lead' => $row['mode_of_lead'],
            // 'lead_generation_date' =>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['lead_generation_date'])->format('d-m-Y'),
            // 'name_of_client' => $row['name_of_client'],
            // 'contact_no' => $row['contact_no'],
            // 'alt_no_whatsapp' => $row['alt_no_whatsapp'],
            // 'email_id' => $row['email_id'],
            // 'investment_or_end_user' => $row['investment_or_end_user'],
            // 'budget' => $row['budget'],
            // 'location_of_client' => $row['location_of_client'],
            // 'personal_details_if_any' => $row['personal_details_if_any'],
            // 'requirement' => $row['requirement'],
            // 'lead_assigned' => $row['lead_assigned'],
            // 'last_call_back_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_call_back_date'])->format('d-m-Y'),
            // 'remarks_of_caller' => $row['remarks_of_caller'],
            // 'project_names' => $row['project_names'],
            // 'ready_to_move_or_underconstruction' => $row['ready_to_move_or_underconstruction'],
            // 'rtm_shop' => $row['rtm_shop'],
            // 'uc_shop' => $row['uc_shop'],
            // 'sco' => $row['sco'],
            // 'flat' => $row['flat'],
            // 'floor' => $row['floor'],
            // 'penthouse' => $row['penthouse'],
            // 'villa' => $row['villa'],
            // 'plot' => $row['plot'],
            // 'affordable' => $row['affordable'],
            // 'others' => $row['others'],
            // 'resale' => $row['resale'],
            // 'rent' => $row['rent'],
            // 'office_site_visit_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['office_site_visit_date'])->format('d-m-Y'),
            // 'projects_visited_names' => $row['projects_visited_names'],
            // 'final_project_selected' => $row['final_project_selected'],
            // 'price_quoted' => $row['price_quoted'],
            // 'booking_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['booking_date'])->format('d-m-Y'),
            // 'follow_up_status_open_follow_up_close_purchased_from_some' => $row['follow_up_status_open_follow_up_close_purchased_from_some'] 
        ]);
    } 
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }

    

    public function rules(): array
    {
        return [  
                //  '*.email_id' => 'email|unique:client_sheets,email_id',
                //   '*.s_no' => 'required', 
            //    '*.reference' => 'required|exists:client_sheets,reference', 
            //   // '*.mode_of_lead' => 'required|exists:client_sheets,mode_of_lead',
            // //    '*.lead_generation_date' => 'required|exists:client_sheets,lead_generation_date',
            //    '*.name_of_client' => 'required|exists:client_sheets,name_of_client',
            //    '*.contact_no' => 'required|exists:client_sheets,contact_no',
            //    '*.alt_no_whatsapp' => 'required|exists:client_sheets,alt_no_whatsapp',
            // //    '*.email_id' => 'required|exists:client_sheets,email_id',
            //    '*.investment_or_end_user' => 'required|exists:client_sheets,investment_or_end_user',
            //    '*.budget' => 'required|exists:client_sheets,budget',
            //    '*.location_of_client' => 'required|exists:client_sheets,location_of_client',
            //    '*.personal_details_if_any' => 'required|exists:client_sheets,personal_details_if_any',
            //    '*.requirement' => 'required|exists:client_sheets,requirement',
            //    '*.lead_assigned' => 'required|exists:client_sheets,lead_assigned',
            // //    '*.last_call_back_date' => 'required|exists:client_sheets,last_call_back_date',
            //    '*.remarks_of_caller' => 'required|exists:client_sheets,remarks_of_caller',
            //    '*.project_names' => 'required|exists:client_sheets,project_names',
            //    '*.ready_to_move_or_underconstruction' => 'required|exists:client_sheets,ready_to_move_or_underconstruction',
            //    '*.rtm_shop' => 'required|exists:client_sheets,rtm_shop',
            //    '*.uc_shop' => 'required|exists:client_sheets,uc_shop',
            //    '*.sco' => 'required|exists:client_sheets,sco',
            //    '*.flat' => 'required|exists:client_sheets,flat',
            //    '*.floor' => 'required|exists:client_sheets,floor',
            //    '*.penthouse' => 'required|exists:client_sheets,penthouse',
            //    '*.villa' => 'required|exists:client_sheets,villa',
            //    '*.plot' => 'required|exists:client_sheets,plot',
            //    '*.affordable' => 'required|exists:client_sheets,affordable',
            //    '*.others' => 'required|exists:client_sheets,others',
            //    '*.resale' => 'required|exists:client_sheets,resale',
            //    '*.rent' => 'required|exists:client_sheets,rent',
            // //    '*.office_site_visit_date' => 'required|exists:client_sheets,office_site_visit_date',
            //    '*.projects_visited_names' => 'required|exists:client_sheets,projects_visited_names',
            //    '*.final_project_selected' => 'required|exists:client_sheets,final_project_selected',
            //    '*.price_quoted' => 'required|exists:client_sheets,price_quoted',
            // //    '*.booking_date' => 'required|exists:client_sheets,booking_date',
            //    '*.follow_up_status_open_follow_up_close_purchased_from_some' => 'required|exists:client_sheets,follow_up_status_open_follow_up_close_purchased_from_some'

        ];
         
    } 

    // public function uniqueBy()
    // {
    //     return 'Email ID';
    // }
 
}
