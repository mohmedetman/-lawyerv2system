<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenciesIndex extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_code',
        'user_id',
        'user_name',
        'agencies_num_en',
        'office_doc_en',
        'agencies_type_en',
        'agencies_num_ar',
        'office_doc_ar',
        'agencies_type_ar',
        'date',
        'status',
        'permission',
        'agencies_imagePath',
        'agencies_imageUrl'
    ];
}
