<?php

namespace App\Rules;

use App\Permissions;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class NotBannedFromChannel implements Rule
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
        return $this->isUserBannedFromChannel(Auth::id(), $value) === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ' You Have been banned from submitting to this channel.';
    }
}
