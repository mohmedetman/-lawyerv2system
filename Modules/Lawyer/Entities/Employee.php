<?php

namespace Modules\Lawyer\Entities;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'password',
        'code',
        'phone_number',
        'personal_id',
        'address',
        'gender',
        'user_type',
        'litigationDegree_en',
        'litigationDegree_ar',
        'lawyer_id',
        'email'

    ];
//    protected $hidden = ['password','lawyer_id'] ;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class, 'id', 'lawyer_id');
    }





    public function addresses()
    {
        return $this->hasMany(EmployeeAddress::class,'employee_id','id');
    }

    public function phones()
    {
        return $this->hasMany(EmployeePhone::class,'employee_id','id');
    }
}
