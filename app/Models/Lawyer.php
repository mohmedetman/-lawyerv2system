<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Lawyer extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'phone_number',
        'password',
        'code',
        'specialization',
        'admin_id'
    ];
    protected $hidden = [
        'password',
    ];
}
