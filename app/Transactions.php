<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [
        'account_id',
        'type',
        'destination',
        'amount'
    ];

    public function account(){
        return $this->hasOne(Account::class);
    }
}
