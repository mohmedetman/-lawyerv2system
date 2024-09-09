<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SameLength implements ValidationRule
{
    protected $otherArray ;
    public function __construct($otherArray) {
    $this->otherArray = $otherArray;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($this->otherArray) && is_array($value)) {
            if (count($value)  != count($this->otherArray) ) {
                $fail("The same attributes do not match.");
            }
        }

        //
    }
}
