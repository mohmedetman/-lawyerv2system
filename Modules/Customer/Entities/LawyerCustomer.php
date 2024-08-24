<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\LawyerCustomerFactory;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LawyerCustomer extends Pivot
{
    protected $table = 'lawyer_customer';

    protected $fillable = [
        'lawyer_id',
        'customer_id',
        // Add any other fields you may want to track
    ];
}
