<?php

namespace Payfort\Exceptions;

use Exception;

class PayfortException extends Exception
{
    public function render()
    {
        $msg = $this->getMessage();
        return match (config('payfort.exception_return_type')) {
            default => response()->json([
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ], 500),
            'view' => view(config('payfort.path'))->with(compact('msg')),
        };
    }
}


