<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'lead_name',
        'contact_number',
        'project_type',
        'location_of_leads',
        'source',
        'lead_status',
        'country_code_bu',
        'is_exist',
        'lead_type_bifurcation',
        'number_of_units',
        'property_requirement',
        'customer_interaction',
        'customer_email',
        'customer_profile',
        'alt_contact_number_1',
        'existing_property',
        'customer_type',
        'alt_contact_name',
        'alt_contact_number_2'
    ];
}

