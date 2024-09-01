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
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
//    public function validate(string $attribute, mixed $value, Closure $fail): void
//    {
//        $lawyer_email = DB::table('lawyers')->where('email', $value)->exists();
//        $admin = DB::table('admins')->where('email', $value)->exists();
//        $employees_email = DB::table('employees')->where('email', $value)->exists();
//        $customer_email = DB::table('customers')->where('email', $value)->exists();
//        if ($lawyer_email  || $admin || $employees_email || $customer_email) {
//            $fail($customer_email==1?'Email already exists. in customer':($lawyer_email==1?'Email already exists.  lawyer':($employees_email==1?'Email already exists. employees':'Email already exists. administrator')));
//        }
//
//    }
//}
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ignoreId = request()->input('id'); // Assume 'id' is passed in the request

        $lawyer_email = DB::table('lawyers')
            ->where('email', $value)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        $admin_email = DB::table('admins')
            ->where('email', $value)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        $employee_email = DB::table('employees')
            ->where('email', $value)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        $customer_email = DB::table('customers')
            ->where('email', $value)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        if ($lawyer_email || $admin_email || $employee_email || $customer_email) {
            $message = match (true) {
                $customer_email => 'Email already exists in customers.',
                $lawyer_email => 'Email already exists in lawyers.',
                $employee_email => 'Email already exists in employees.',
                $admin_email => 'Email already exists in admins.',
                default => 'Email already exists.'
            };
            $fail($message);
        }
    }

}
