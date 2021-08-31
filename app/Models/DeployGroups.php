<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notes',
        'entityID',
        'deployed',
    ];
}
