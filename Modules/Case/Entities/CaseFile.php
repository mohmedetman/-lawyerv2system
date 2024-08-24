<?php

namespace Modules\Case\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Case\Database\factories\CaseFileFactory;

class CaseFile extends Model
{
    use HasFactory;
   protected $table = 'case_files';
   protected  $guarded = [] ;
}
