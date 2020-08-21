<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\Transactions as Types;
use Illuminate\Support\Facades\Hash;
use App\Transactions;
use App\Account;
use Response;
use App\User;

class TransactionsController extends Controller
{
    public function event(Request $request){
        try{

            if(!isset($request['type']) || empty($request['type']))
                throw new \Exception("Field type cannot be null");

            switch ($request['type']) {
                case Types::DEPOSIT :
                    return $this->deposit($request['destination'], $request['amount']);
                    break;
                case Types::WITHDRAW :
                    return $this->withdraw($request['origin'], $request['amount']);
                    break;
                case Types::TRANSFER :
                    return $this->transfer($request['origin'], $request['amount'], $request['destination']);
                    break;
                default:
                    throw new \Exception("Inserted type is not permitted");
            }

        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }

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
