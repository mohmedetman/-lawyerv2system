<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'lawyer_id',
        'title_en',
        'image',
        'description_en',
        'title_ar',
        'description_ar'
    ];
}
