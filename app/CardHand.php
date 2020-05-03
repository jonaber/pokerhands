<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardHand extends Model
{
    public function cardhands()
    {
        return $this->hasOne('App\User');
    }
}

