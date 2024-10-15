<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission_tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_name',
        'permission_tag_id', 
    ];
}
