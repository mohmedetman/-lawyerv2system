<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseTypeFactory;

class CaseType extends Model
{
    use HasFactory;

   protected $guarded =[];
}
