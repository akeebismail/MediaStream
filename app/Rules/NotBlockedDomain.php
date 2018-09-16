<?php

namespace App\Rules;

use App\Permissions;
use Illuminate\Contracts\Validation\Rule;

class NotBlockedDomain implements Rule
{
    use Permissions;
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
        return $this->isDomainBlocked($value, request('channel_name')) === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The Submitted website is in the channel's blacklist. Please find another source";
    }
}
