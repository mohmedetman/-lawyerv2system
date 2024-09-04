<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\SessionFactory;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): SessionFactory
    {
        //return SessionFactory::new();
    }
}
