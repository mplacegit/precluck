<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PartnerPad extends Authenticatable
{

    protected $fillable = [
        'user_id', 'domain'
    ];


}
