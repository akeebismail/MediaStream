<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actibity extends Model
{
    protected $fillable = [

        'subject_id', 'subject_type', 'name', 'user_id', 'ip_address', 'user_agent', 'country',
        'browser_name', 'browser_version', 'os', 'device',
    ];

    /**
     * An Activity record is owned by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
