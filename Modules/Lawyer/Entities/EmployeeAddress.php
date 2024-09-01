<?php

namespace Modules\Lawyer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lawyer\Database\factories\EmployeeAddressFactory;

class EmployeeAddress extends Model
{
    use HasFactory;
    protected $table = 'employee_addresses';

    /**
     * The attributes that are mass assignable.
     */
   protected $guarded = [] ;
}
