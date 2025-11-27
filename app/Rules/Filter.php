<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Filter implements Rule
{
    protected $forbidden;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($forbidden)
    {
        $this->forbidden = $forbidden;
        // dd($this->forbidden);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // here inside the passes method we put the logic of the validation
        // if the value is in the forbidden list, return false (validation fails)
        // otherwise, return true (validation passes)
        /**
         * <input name="name" value="">
         * - $attribute: Represents the name of the field, which will always be "name" in this case.
         * - $value: The value entered in this field, value present in the request.
         */


        // if (strtolower($value) == 'laravel') {
        //     return false;
        // }
        // return true;

        // for Clean Code we can write like this:
        // return !(strtolower($value) === 'laravel');




        // is_string($this->forbidden) ? $this->forbidden = [$this->forbidden] : null;
        return !in_array(strtolower($value), $this->forbidden);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The validation error message.';
        return 'this Value is Not Allowed.';
    }
}
