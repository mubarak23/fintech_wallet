<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    protected $fillable = [
        'txn_type', 'purpose', 'amount', 'account_id', 'reference', 'balance_before', 'balance_after', 'metadata'
    ];
}
