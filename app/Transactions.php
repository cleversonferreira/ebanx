<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [
        //
    ];

    public function account(){
        return $this->hasOne(Account::class)
    }
}
