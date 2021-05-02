<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //

    protected $fillable = [
        'account_id', 'balance', 'user_id',
    ];

    protected $casts = [
    'balance' => 'number',
    'account_id' => 'integer'
];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
