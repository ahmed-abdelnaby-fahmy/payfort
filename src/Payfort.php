<?php

namespace Payfort;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Payfort
{

    public static function payment()
    {
        return new Merchant();
    }
}
