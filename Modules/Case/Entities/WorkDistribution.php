<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\WorkDistributionFactory;
use Modules\Lawyer\Entities\Employee;

class WorkDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'employee_id',
        'action',
        'notes',
        'due_date',
    ];
    public function case()
    {
        return $this->belongsTo(CaseFile::class, 'case_id')
            ->select(['id',  'court_en', 'court_ar']);
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id')
            ->select(['id', 'name_en', 'name_ar']);
    }
    protected $hidden = ['case_id', 'employee_id','created_at'];
}
