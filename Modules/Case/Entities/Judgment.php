<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Case\Database\factories\JudgmentsFactory;

class Judgment extends Model
{
    use HasFactory;
 protected $table = 'judgments';
 protected $guarded = [];
 public static function booted() {
 static::addGlobalScope('auth',function($builder){
     $token = request()->bearerToken();
     $token_type = PersonalAccessToken::findToken($token)->tokenable_type;
     $builder->whereHas('case',function($builder) use ($token_type){
        $builder->where('lawyer_id',str_contains($token_type,'Lawyer')?Auth::user()->id:Auth::user()->lawyer_id);
     });
 });
 }
 public function case() {
     return $this->belongsTo(CaseFile::class);
 }



    public $hidden = ['updated_at','created_at'];


}
