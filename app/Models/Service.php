<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'image_path',
        'image_url',
        'description_en',
        'title_ar',
        'description_ar'
    ];
}
