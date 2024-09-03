<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\PowerAttorneyFactory;

class PowerAttorney extends Model
{
    use HasFactory;
    public $table = 'power_attorneys';
    protected $guarded = [];
}
