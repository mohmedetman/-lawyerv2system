<?php

namespace Modules\Case\Entities;

use App\Helpers\TokenType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Case\Database\factories\JudicialReportFactory;

class JudicialReport extends Model
{
    use HasFactory,TokenType;
protected $table = 'judicial_reports';
protected $guarded = [];
protected static function booted()
{


    static::addGlobalScope('author', function ($builder) {
        $auth = static::generateToken();
        $builder->where('lawyer_id',$auth=='lawyer'?Auth::user()->id : Auth::user()->lawer_id);
    });
}
}
