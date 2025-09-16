<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberVerificationRule implements ValidationRule
{
    protected $regexRules = [
        // Bangladesh (+8801XXXXXXXXX) → total 13 digits after +880, must start with 1
        'bdt' => '/^\+8801[0-9]{9}$/',

        // India (+91XXXXXXXXXX) → 10 digits after +91, must start with 6-9
        'inr' => '/^\+91[6-9][0-9]{9}$/',

        // Pakistan (+92XXXXXXXXXX) → 10 digits after +92, usually starts with 3
        'pkr' => '/^\+92[0-9]{10}$/',

        // Nepal (+977XXXXXXXXX) → 9 digits after +977, usually starts with 9
        'npr' => '/^\+977[0-9]{9}$/',

        // Sri Lanka (+94XXXXXXXXX) → 9 digits after +94
        'lkr' => '/^\+94[0-9]{9}$/',

        // Bhutan (+975XXXXXXXX) → 8 digits after +975
        'btn' => '/^\+975[0-9]{8}$/',

        // UAE (+971XXXXXXXX) → 8 or 9 digits after +971
        'aed' => '/^\+971[0-9]{8,9}$/',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValid = false;

        foreach ($this->regexRules as $country => $pattern) {
            if (preg_match($pattern, $value)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail("The $attribute format is invalid.");
        }
    }
}

