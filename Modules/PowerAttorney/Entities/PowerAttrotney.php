<?php

namespace Modules\PowerAttorney\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PowerAttorney\Database\factories\PowerAttrotneyFactory;

class PowerAttrotney extends Model
{
    use HasFactory;
    protected $table = 'power_attrotneys';
    public $guarded = [] ;

}
