<?php

namespace Payfort\Exceptions;

use Exception;

class PayfortException extends Exception
{
    public function render($request)
    {
        $msg = $this->getMessage();
        return view('payfort::error')->with(compact('msg'));
    }
}


