<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_name',
        'contact_number', 
        'role_id',
        'user_id',
        'site_logo',
        'locations',
    ];
}
