<?php

namespace App\Models;

/* use Illuminate\Database\Eloquent\Factories\HasFactory; */
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class ViennaSign extends Model implements HasMedia
{

    use InteractsWithMedia;
    /* use HasFactory; */
    /* protected $table = 'vienna_signs'; */


    protected $fillable = [
        'id', 
        'name',
        'image',
        'IDCategory',
    ];
}
