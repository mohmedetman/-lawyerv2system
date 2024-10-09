<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Modules\Case\Entities\PowerAttorney;

class CheckUniqeClassification implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = (int)request()->route()->id  ?? 0 ;
        $numeric_classification = request()->numeric_classification;
        $alphabetic_classification = request()->alphabetic_classification;
        $power = DB::table('power_attorneys')
            ->where('alphabetic_classification', $alphabetic_classification)
            ->where('numeric_classification', $numeric_classification)
            ->where('id', '!=', $id)
            ->exists();
        if ($power) {
            $fail('classification attribute already exists.');

        }

    }
}
