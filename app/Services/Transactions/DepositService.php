<?php

namespace App\Services\Transactions;

use Illuminate\Support\Facades\Hash;
use App\Enums\Transactions as Types;
use App\Transactions;
use App\Account;
use Response;
use App\User;

class DepositService
{
    public function deposit($destination, $amount){

        try{

            if(!isset($destination) || empty($destination))
                throw new \Exception("Destination cannot be null");
                
            if(!isset($amount) || empty($amount))
                throw new \Exception("Amount cannot be null");

            //seach account
            $account = Account::where('account_id', $destination)->first();            
            
            //if account don't exists
            if(!$account){

                //create user
                $user = User::create([
                    'name' => 'Client ' . date('dmyHis'),
                    'email' => date('dmyHis') . '@ebanx.com',
                    'password' => Hash::make('secret'),
                ]);

                //create account
                $account = Account::create([
                    'account_id' => $destination,
                    'user_id' => $user->id,
                    'balance' => $amount,
                ]);

                //create transaction
                Transactions::create([
                    'account_id' => $destination,
                    'type' => Types::DEPOSIT,
                    'destination' => $destination,
                    'amount' => $amount,
                ]);

                return Response::json([
                    'destination' => [
                        "id" => $account->account_id,
                        "balance" => $account->balance
                    ]
                ], 201);

            }

            //if account exists, sum amount to current balance
            $total = ($account->balance + $amount);
            $account = Account::where('account_id', $destination)->update(array('balance' => $total));

            //create transaction
            Transactions::create([
                'account_id' => $destination,
                'type' => Types::DEPOSIT,
                'destination' => $destination,
                'amount' => $amount,
            ]);
            
            return Response::json([
                'destination' => [
                    "id" => $destination,
                    "balance" => $total
                ]
            ], 201);
        

        }catch(\Exception $e){
            return $e->getMessage();         
        }

    }
}
