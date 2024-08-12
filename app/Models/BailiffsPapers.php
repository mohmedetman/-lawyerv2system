<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BailiffsPapers extends Model
{
    use HasFactory;
    protected $fillable = [
       'bailiffs_pen_en',
        'bailiffs_pen_ar',
        'user_id',
        'employee_id',
        "model_type",
        'lawyer_id',
        "user_type",
        'announcment_time',
        'bailiff_reply',
        'user_name',
        'delivery_time',
        'session_time',
        'status',
        'permission',
        'bailiffs_num'
    ];
    public function lawyer()
    {
     return $this->hasOne(Lawyer::class, 'id', 'lawyer_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function employee()
    {
      return $this->hasOne(Employee::class, 'id', 'employee_id');
    }
}
