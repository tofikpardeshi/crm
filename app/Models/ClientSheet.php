<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSheet extends Model
{
    use HasFactory;
    protected $fillable = [
        's_no',
        'reference',
        'mode_of_lead',
        'lead_generation_date', 
        'name_of_client', 
        'contact_no', 
        'alt_no_whatsapp', 
        'email_id', 
        'investment_or_end_user', 
        'budget', 
        'location_of_client', 
        'personal_details_if_any', 
        'requirement', 
        'lead_assigned',  
        'last_call_back_date', 
        'remarks_of_caller', 
        'project_names', 
        'ready_to_move_or_underconstruction', 
        'rtm_shop', 
        'uc_shop', 
        'sco', 
        'flat', 
        'floor', 
        'penthouse',
        'villa',  
        'plot', 
        'affordable', 
        'others', 
        'resale', 
        'rent', 
        'office_site_visit_date', 
        'projects_visited_names', 
        'final_project_selected', 
        'price_quoted', 
        'booking_date', 
        'follow_up_status_open_follow_up_close_purchased_from_some',
    ];
}
