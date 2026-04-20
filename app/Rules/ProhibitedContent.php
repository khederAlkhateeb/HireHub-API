<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ProhibitedContent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $badWords = ['badword1', 'badword2', 'offensiveTerm'];

        foreach ($badWords as $word) {
            if (str_contains(strtolower($value), $word)) {
                $fail('your content is bad...!');
            }
        }
    }
}
