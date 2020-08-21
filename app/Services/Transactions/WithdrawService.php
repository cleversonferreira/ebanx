<?php

namespace App\Services\Transactions;

use App\Enums\Transactions as Types;
use App\Transactions;
use App\Account;
use Response;

class WithdrawService
{
    public function withdraw($origin, $amount){
        try{
            if(!isset($origin) || empty($origin))
                throw new \Exception("Origin cannot be null");
                
            if(!isset($amount) || empty($amount))
                throw new \Exception("Amount cannot be null");

            //seach account
            $account = Account::where('account_id', $origin)->first();            
            
            //if account don't exists
            if(!$account){
                return response(0, 404);
            }

            //if account exists, sum amount to current balance
            $total = ($account->balance - $amount);
            $account = Account::where('account_id', $origin)->update(array('balance' => $total));

            //create transaction
            Transactions::create([
                'account_id' => $origin,
                'type' => Types::WITHDRAW,
                'destination' => $origin,
                'amount' => $amount,
            ]);
            
            return Response::json([
                'origin' => [
                    "id" => $origin,
                    "balance" => $total
                ]
            ], 201);


        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }
}
