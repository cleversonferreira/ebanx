<?php

namespace App\Enums;

abstract class Transactions
{
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const TRANSFER = 'transfer';

    const TYPES = [
        self::DEPOSIT,
        self::WITHDRAW,
        self::TRANSFER
    ];
}
