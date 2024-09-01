<?php

namespace Modules\Lawyer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lawyer\Database\factories\LawyerDepartmentFactory;

class LawyerDepartment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): LawyerDepartmentFactory
    {
        //return LawyerDepartmentFactory::new();
    }
}
