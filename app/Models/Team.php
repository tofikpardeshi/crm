<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Team extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'team_name',
        'team_email', 
        'team_phone_number',
        'designation',
        'project_id',
        'name_of_developer'
    ];
}
