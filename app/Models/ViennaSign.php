<?php

namespace App\Models;

/* use Illuminate\Database\Eloquent\Factories\HasFactory; */
use Illuminate\Database\Eloquent\Model;

class ViennaSign extends Model
{
    /* use HasFactory; */
    /* protected $table = 'vienna_signs'; */
    protected $fillable = [
        'id', 
        'name',
        'image',
        'IDCategory',
    ];
}
