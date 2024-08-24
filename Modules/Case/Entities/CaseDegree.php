<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseDegreeFactory;

class CaseDegree extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   protected $guarded = [];
}
