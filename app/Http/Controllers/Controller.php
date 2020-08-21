<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Artisan;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function reset(){
        try{
            Artisan::call('migrate:reset', ['--force' => true]);
            return response('OK', 200);
        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }
}
