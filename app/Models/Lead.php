<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Lead extends Model
{

    use HasFactory,Notifiable;

    protected $fillable = [
        'date',
        'lead_name',
        'contact_number',
        'assign_employee_id',
    ];

     /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    // public function routeNotificationFor()
    // {
    //     return $this->email;
    // }
}
