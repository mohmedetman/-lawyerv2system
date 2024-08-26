<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Lawyer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
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
