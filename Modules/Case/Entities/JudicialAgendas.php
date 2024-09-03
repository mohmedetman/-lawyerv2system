<?php

namespace Modules\Case\Entities;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Case\Database\factories\JudicialAgendasFactory;

class JudicialAgendas extends Model
{
    use HasFactory;

   protected  $guarded = [] ;
   protected $hidden = ['created_at' , 'updated_at','model_type' , 'model_id'] ;
    protected static function booted()
    {
        static::addGlobalScope('lawyer', function (Builder $builder) {
           $builder->where('judicial_agendas.lawyer_id' , Auth::user()->id);
        });
    }
    public function lawyer() {
        return $this->belongsTo(Lawyer::class);
    }
    public function cases() {
        return $this->belongsTo(CaseFile::class,'case_id');
    }
}
