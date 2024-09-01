<?php

namespace Modules\Lawyer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lawyer\Database\factories\EmployeePhoneFactory;

class EmployeePhone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
 protected $guarded = [] ;

}
