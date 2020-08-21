<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        //
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function transactions(){
        return $this->hasMany(Transactions::class);
    }
}
