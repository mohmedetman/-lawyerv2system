<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
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
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class, 'id', 'lawyer_id');
    }
}
