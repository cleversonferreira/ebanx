<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;

class AccountController extends Controller
{
    public function balance(Request $request){
        try{

            $amount = Account::where('account_id', $request['account_id'])->pluck('amount')->first();

            if($amount)
                return response($amount, 200);
            
            return response(0, 404);

        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }
}
