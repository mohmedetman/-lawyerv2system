<?php

namespace Modules\Bailiff\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Bailiff\Database\factories\DocumentFactory;
use Modules\Case\Entities\CaseFile;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public function case()
    {
        return $this->belongsTo(CaseFile::class);
    }
    public static function booted() {
        static::addGlobalScope('auth', function ($builder) {
            $builder->whereHas('case', function ($builder) {
                $builder->where('lawyer_id', auth()->id());
            });
        });
    }


}
