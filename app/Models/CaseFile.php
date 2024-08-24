<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Case\Entities\CaseType;

class CaseFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'lawyer_id',
        'user_type',
        "customer_id",
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
        'case_type_id',
        'case_degree_id'
    ];
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class, 'id', 'lawyer_id');
    }
    public function employee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function caseType()
    {
        return $this->hasOne(CaseType::class, 'id', 'case_type_id');
    }
    public function caseDegree()
    {
        return $this->hasOne(CaseType::class, 'id', 'case_type_id');
    }
}
