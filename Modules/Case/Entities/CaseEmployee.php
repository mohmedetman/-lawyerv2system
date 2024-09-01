<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseEmployeeFactory;
use Modules\Lawyer\Entities\Employee;

class CaseEmployee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

   protected $guarded = [];
   public function case() {
       return $this->belongsTo(CaseFile::class);
   }
   public function employee() {
       return $this->belongsTo(Employee::class)
           ->select(['id', 'name_ar', 'name_en']);
   }
}
