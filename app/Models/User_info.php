<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_info extends Model
{
    use HasFactory;
    protected $table='user_info';

    protected $fillable = [
        'phone',
        'address',
        'avatar',
        'cover',
        'describe',
        'pay_email',
        'avatar',
        'cover',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
