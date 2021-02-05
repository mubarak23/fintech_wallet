<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //

    protected $fillable = [
        'accounrt_id', 'balance', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
