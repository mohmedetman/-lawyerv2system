<?php

namespace Modules\Office\Entities;

use App\Helpers\TokenType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class CaseHistory extends Model
{
    use HasFactory,TokenType;
    public $guarded = [] ;
    protected static function booted()
    {
        static::addGlobalScope('auth',function ($builder){
            $auth = static::generateToken();
            $builder->where('lawyer_id',$auth=='lawyer' ? Auth::user()->id : Auth::user()->lawyer_id);
        });
    }
protected $hidden = ['created_at','updated_at','lawyer_id'];

}
