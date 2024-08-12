<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'lawyer_id',
        'user_type',
        "user_id",
        'court_en',
        'employee_id',
        'created_by',
        'model_type',
        'user_status_en',
        'enemy_status_en',
        'last_session_en',
        'decision_en',
        'court_ar',
        'user_status_ar',
        'enemy_status_ar',
        'last_session_ar',
        'permission',
        'decision_ar',
        'status',
    ];
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class, 'id', 'lawyer_id');
    }
    public function employee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
