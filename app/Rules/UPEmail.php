<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UPEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $attribute == 'email' && preg_match('/@up.pt$/', $value) == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The e-mail should belong to UP domain.';
    }
}
