<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ChechEmailUniqe implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lawyer_email = DB::table('lawyers')->where('email', $value)->exists();
        $employees_email = DB::table('employees')->where('email', $value)->exists();
        $customer_email = DB::table('customers')->where('email', $value)->exists();
//        $customer_email = DB::table('customers')->where('email', $value)->exists();
        if ($lawyer_email || $employees_email || $customer_email) {
            $fail($customer_email==1?'Email already exists. in customer':($lawyer_email==1?'Email already exists.  lawyer':'Email already exists. employees'));
        }

    }
}
