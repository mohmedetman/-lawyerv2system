<?php

namespace Modules\Bailiff\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Bailiff\Database\factories\MovementFactory;

class Movement extends Model
{
    use HasFactory;

    protected $guarded = [] ;
    public function document() {
        return $this->belongsTo(Document::class);
    }
    public function bailiff() {
        return $this->belongsTo(Bailiff::class, 'bailiff_id');
    }
}
