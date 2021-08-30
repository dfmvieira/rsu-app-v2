<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployGroup extends Model
{
    protected $fillable = [
        'name',
        'notes',
        'status',
        'ID_sign_group',
        'ID_user_group',

    ];
}
