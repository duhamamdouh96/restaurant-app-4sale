<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AfterDate implements ValidationRule
{
    protected $otherDate;

    public function __construct($otherDate)
    {
        $this->otherDate = $otherDate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $requestFrom = Carbon::parse($this->otherDate.' '.$value)->format('Y-m-d H:i:s');

        if ($requestFrom <=  now()) {
            $fail('The :attribute must be a time after '.date('h:i a'));
        }
    }
}
