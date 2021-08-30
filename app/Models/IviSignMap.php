<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IviSignMap extends Model
{
    use HasFactory;

    public $table = 'ivi_signs_map';

    protected $fillable = [
        'id',
        'name',
        'entityId',
        'guid',
        'viennaSignId',
        'latitude',
        'longitude',
        'comment',
        'locked',
        'published',
        'IDDetection',
        'IDAwareness',
        'IDRelevance',
    ];
}
