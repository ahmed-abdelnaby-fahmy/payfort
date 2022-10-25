<?php

namespace Payfort\Exceptions;

use Exception;

class PayfortException extends Exception
{
    public function render($request)
    {
        $msg = $this->getMessage();
        return view(config('payfort.path')??'payfort::error')->with(compact('msg'));
    }
}


