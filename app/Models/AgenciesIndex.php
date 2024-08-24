<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenciesIndex extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'lawyer_id',
        'agencies_num_en',
        'office_doc_en',
        'agencies_type_en',
        'agencies_num_ar',
        'office_doc_ar',
        'agencies_type_ar',
        'date',
        'status',
        'permission',
        'image',
        'employee_id'
    ];
}
