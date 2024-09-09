<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Database\factories\SessionFactory;

class Session extends Model
{
    use HasFactory;

    protected $guarded = [] ;
    public static function booted() {
        static::addGlobalScope('auth', function ($builder) {
            $token = request()->bearerToken();
            $token_type = PersonalAccessToken::findToken($token)->tokenable_type;
            if (str_contains($token_type, 'Lawyer')) {
                $builder->where('lawyer_id',Auth::user()->id);
            }
            else {
                $builder->where('lawyer_id',Auth::user()->lawyer_id);
            }
        });
    }
    public function case()
    {
        return $this->belongsTo(CaseFile::class)
            ->select(['id','court_en','court_ar','customer_id','case_number','status']);
    }
    public function sessionResult()
    {
        return $this->hasMany(SessionResult::class);
    }
    public $hidden = ['lawyer_id','created_at','updated_at'];

}
