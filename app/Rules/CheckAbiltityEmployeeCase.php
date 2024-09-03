<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Case\Entities\CaseEmployee;

class CheckAbiltityEmployeeCase implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employee_id = request()->post('employee_id');
        $case_id = request()->post('case_id');
        $case_employee = CaseEmployee::where('case_id', $case_id)->where('employee_id', $employee_id)
            ->where('status', 'confirmed');
        if ($case_employee->count() == 0) {
            $fail('employee is already not  confirmed');
        }

    }
}
