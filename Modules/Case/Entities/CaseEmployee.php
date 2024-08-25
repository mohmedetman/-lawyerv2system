<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseEmployeeFactory;

class CaseEmployee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

   protected $guarded = [];
}
