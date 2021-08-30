<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetectionZone extends Model
{
    use HasFactory;

    public $table = 'detection_zones';

    protected $fillable = [
        'id',
        'latitude1',
        'longitude1',
        'latitude2',
        'longitude2',
    ];
}
