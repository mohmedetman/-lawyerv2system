<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\WorkDistributionFactory;

class WorkDistribution extends Model
{
    use HasFactory;

    protected $table = 'work_distribution';

    protected $fillable = [
        'case_id',
        'action',
        'court_name',
        'notes',
        'assigned_to',
        'due_date',
    ];

    public function case()
    {
        return $this->belongsTo(CaseFile::class, 'case_id', 'case_id');
    }
}
