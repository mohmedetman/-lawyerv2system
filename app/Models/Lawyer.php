<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Lawyer extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'phone_number',
        'password',
        'code',
        'department_id',
        'bio_ar',
        'bio_en',
        'specialization',
        'admin_id'
    ];
    protected $hidden = [
        'password',
    ];
    public function department() {
        return $this->hasOne(LawyerDepartment::class, 'id', 'department_id');
    }
}
