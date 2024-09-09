<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Database\factories\SessionResultFactory;

class SessionResult extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function booted() {
        static::addGlobalScope('auth', function ($builder) {
            $token = request()->bearerToken();
            $token_type = PersonalAccessToken::findToken($token)->tokenable_type;
            $builder->whereHas('session', function ($builder) use ($token_type) {
                $builder->where('lawyer_id',str_contains(strtolower($token_type), 'lawyer') ? Auth::user()->id : Auth::user()->lawyer_id);
            });
        });
    }
    public function session() {
        return $this->belongsTo(Session::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
