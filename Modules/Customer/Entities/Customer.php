<?php

namespace Modules\Customer\Entities;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Database\factories\CustomerFactory;

class Customer extends Model
{
    use HasFactory;
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
        static::addGlobalScope('lawyer', function (Builder $builder) {
            $builder->whereHas('lawyers', function ($query) {
                $query->where('lawyer_id', Auth::user()->id ?? 1);
            });
        });
    }
    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class, 'lawyer_customer')
            ->withTimestamps();
    }
}
