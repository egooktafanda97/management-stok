<?php

namespace App\Constant;

enum PayType: int
{
    const CREDIT_CARD = 1;
    const PAYPAL = 2;
    const BANK_TRANSFER = 3;
    const CASH = 4;
    const CRYPTOCURRENCY = 5;
    const DEBS = 6;
    const ONCARD = 7;
}
