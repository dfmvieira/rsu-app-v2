<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDeployGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'IDUser',
        'IDDeployGroup',
    ];
}
