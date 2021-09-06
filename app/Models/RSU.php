<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RSU extends Model
{
    use HasFactory;

    public $table = 'rsu';

    protected $fillable = [
        'id',
        'name',
        'serialNumber',
        'range',
        'hardwareDetails'
    ];
}
