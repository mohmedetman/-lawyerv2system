<?php

namespace Modules\Customer\Entities;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'customers';
 protected $guarded = [] ;
 public  $hidden = ['created_at' , 'updated_at','password'];
    /**
     * The attributes that are mass assignable.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class,'customer_id','id');
    }

    public function phones()
    {
        return $this->hasMany(CustomerPhone::class,'customer_id','id');
    }
    protected static function booted()
    {
        if (Auth::check()) {
            static::addGlobalScope('lawyer', function (Builder $builder) {
                $builder->where('customers.lawyer_id', Auth::user()->id);
            });
        }
    }
    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class, 'lawyer_customer')
            ->withTimestamps();
    }
}
