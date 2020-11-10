<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $table='new_trips';
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
