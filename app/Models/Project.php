<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{

    use HasFactory,Notifiable;

    protected $table= 'projects';

    protected $fillable = [
        'project_name',
        'email',
        'project_category',
        'location',
        'sector',
        'contact_number',
        'project_type',
        'assign_mumbers',
        'name_of_developers'
        // 'project_image',
        // 'project_status'
    ];
}
