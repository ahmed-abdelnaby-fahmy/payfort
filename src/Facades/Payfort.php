<?php

namespace Payfort\Facades;

use Illuminate\Support\Facades\Facade;
use Payfort\Merchant;

class Payfort extends Facade
{
    public static function getFacadeAccessor()
    {
        return Merchant::class;
    }
}
