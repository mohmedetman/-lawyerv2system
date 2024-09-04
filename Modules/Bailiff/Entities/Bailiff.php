<?php

namespace Modules\Bailiff\Entities;

use App\Helpers\TokenType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Bailiff\Database\factories\BailiffFactory;

class Bailiff extends Model
{
    use HasFactory;
    protected $guarded = [] ;
    public static function booted()
    {
        static::addGlobalScope('auth', function ($builder) {
            $user = Auth::user();
            $token = request()->bearerToken();
            $personal_token = PersonalAccessToken::find($token)->tokenable_type;
            $builder->where('lawyer_id',str_contains(strtolower($personal_token),'lawyer') ? $user->id : $user->lawyer_id);
        });
    }
   protected $table = 'bailiffs';
}
