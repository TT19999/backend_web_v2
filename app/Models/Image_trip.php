<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id',
        'path',
    ];
}
