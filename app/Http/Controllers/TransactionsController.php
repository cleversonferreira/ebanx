<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\Transactions as Types;
use App\Services\Transactions\DepositService;
use App\Services\Transactions\WithdrawService;
use App\Services\Transactions\TransferService;

class TransactionsController extends Controller
{

    private $depositService;
    private $withdrawService;
    private $transferService;

    public function __construct (DepositService $depositService, WithdrawService $withdrawService, TransferService $transferService)
    {
        $this->depositService = $depositService;
        $this->withdrawService = $withdrawService;
        $this->transferService = $transferService;
    }

    public function event(Request $request)
    {
        try
        {
            if(!isset($request['type']) || empty($request['type']))
                throw new \Exception("Field type cannot be null");

            switch ($request['type']) {
                case Types::DEPOSIT :
                    return $this->depositService->deposit($request['destination'], $request['amount']);
                    break;
                case Types::WITHDRAW :
                    return $this->withdrawService->withdraw($request['origin'], $request['amount']);
                    break;
                case Types::TRANSFER :
                    return $this->transferService->transfer($request['origin'], $request['amount'], $request['destination']);
                    break;
                default:
                    throw new \Exception("This inserted type is not permitted");
            }
        }catch(\Exception $e){
            return $e->getMessage();         
        }
    }
}
