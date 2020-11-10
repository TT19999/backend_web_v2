<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory;
    protected $table='trips';
    protected $fillable = [
        'name',
        'description',
        'location',
        'duration',
        'departure',
        'price',
        'languages',
        'group-size',
        'categories',
        'transportation',
        'includes',
        'excludes',
    ];
}
