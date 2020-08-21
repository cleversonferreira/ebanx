<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account_id',
        'user_id',
        'balance'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function transactions(){
        return $this->hasMany(Transactions::class);
    }
}
