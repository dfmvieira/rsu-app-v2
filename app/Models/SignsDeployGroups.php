<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignsDeployGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'IDIviSign',
        'IDDeployGroup',
    ];
}
