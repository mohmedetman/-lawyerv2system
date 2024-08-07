<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collab extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_path',
        'image_url',
        'company_url'
    ];
}
