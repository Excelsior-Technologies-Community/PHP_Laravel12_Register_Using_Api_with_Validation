<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9]{10,15}$/', $value)) {
            $fail('The :attribute must be a valid mobile number (10-15 digits).');
        }
    }
}