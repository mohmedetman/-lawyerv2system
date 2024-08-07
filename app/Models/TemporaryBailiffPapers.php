<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryBailiffPapers extends Model
{
    use HasFactory;
    protected $fillable = [
        'bailiffs_pen_en',
         'bailiffs_pen_ar',
         'bailiff_id',
         'user_code',
         'user_id',
         'user_name',
         'Delivery time',
         'session_time',
         'status',
         'permission',
         'bailiffs_num'
     ];
}
