<?php

namespace App\Rules;

use App\ChannelForbiddenName;
use Illuminate\Contracts\Validation\Rule;

class NotForbiddenChannelName implements Rule
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
        return !(ChannelForbiddenName::where('name', $value)->exists());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This channel name is forbidden. Please pick another';
    }
}
