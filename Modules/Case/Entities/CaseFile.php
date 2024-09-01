<?php

namespace Modules\Case\Entities;

use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseFileFactory;
use Modules\Lawyer\Entities\Employee;

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
        return $this->belongsToMany(Employee::class, 'case_employees', 'case_id', 'employee_id');
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

