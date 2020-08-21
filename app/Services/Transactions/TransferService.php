<?php

namespace App\Services\Transactions;

use Illuminate\Support\Facades\Hash;
use App\Enums\Transactions as Types;
use App\Transactions;
use App\Account;
use Response;
use App\User;

class TransferService
{
    public function transfer($origin, $amount, $destination){
        try{
            if(!isset($origin) || empty($origin))
                throw new \Exception("Origin cannot be null");
                
            if(!isset($amount) || empty($amount))
                throw new \Exception("Amount cannot be null");

            if(!isset($destination) || empty($destination))
                throw new \Exception("Destination cannot be null");

            //seach origin account
            $account_origin = Account::where('account_id', $origin)->first();
            
            //if origin account don't exists
            if(!$account_origin){
                return response(0, 404);
            }

            //search destination account
            $account_destination = Account::where('account_id', $destination)->first();

            //if destination account don't exists
            if(!$account_destination){
                //create destination user
                $destination_user = User::create([
                    'name' => 'Client ' . date('dmyHis'),
                    'email' => date('dmyHis') . '@ebanx.com',
                    'password' => Hash::make('secret'),
                ]);

                //create destination account
                $account_destination = Account::create([
                    'account_id' => $destination,
                    'user_id' => $destination_user->id,
                    'balance' => 0,
                ]);
            }

            //subtract amount de origin
            $origin_total = ($account_origin->balance - $amount);
            //update origin balance
            Account::where('account_id', $origin)->update(array('balance' => $origin_total));

            //sum destination amount
            $destination_total = ($account_destination->balance + $amount);
            //update destination amount 
            Account::where('account_id', $destination)->update(array('balance' => $destination_total));

            //create transaction
            Transactions::create([
                'account_id' => $origin,
                'type' => Types::TRANSFER,
                'destination' => $destination,
                'amount' => $amount,
            ]);

            
            return Response::json([
                'origin' => [
                    "id" => $origin,
                    "balance" => $origin_total
                ],
                'destination' => [
                    "id" => $destination,
                    "balance" => $destination_total
                ]
            ], 201);


        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }
}
