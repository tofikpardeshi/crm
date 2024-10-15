<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $table = "employees";
    protected $fillable = [
        'employee_name',
        'email',
        'role_id',
        'password',
        // 'address',
        // 'addhar_number',
        // 'phone_number',
        // 'personal_addhar_number',
        // 'pan_Number', 
        // 'education_background', 
        // 'personal_email',
        
    ];
}
