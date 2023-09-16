<?php

namespace Payfort\Validation;


class Validator
{
    public $status = 1;
    public $error;
    public $data;


    public function __construct($parms, $data, $test = 0)
    {
        $data = array_filter((array)$data, function ($val) {
            return isset($val);
        });
        $this->data = $data;
        foreach ($parms as $key => $parm) {
            if (is_string($key)) {
                $this->check_val($key, $parm);

                unset($parms[$key]);
                array_push($parms, $key);
            } else {
                if (!isset($data[$parm]))
                    $this->setError('Missing  parameter ' . $parm);
            }
        }
        $this->data = !empty($test) ? [$parms, $data] : array_intersect_key($data, array_flip($parms));

    }

    function check_val($key, $parms)
    {
        foreach ($parms as $fun => $parm) {
            if (!$this->$fun($key, $parm)) {
                $this->status = 0;
                return $this->error = ['status' => false, 'msg' => 'invalid  ' . $key . ' wrong ' . $fun . ' ' . json_encode($parm)];
            }
        }
        return ['status' => true];
    }

    function required($key, $val)
    {
        if ($val && empty($this->data[$key]))
            return false;
        else
            return true;
    }

    function max($key, $val)
    {
        if (!empty($this->data[$key]) && $this->data[$key] > $val)
            return false;
        else
            return true;
    }

    function min($key, $val)
    {
        if (!empty($this->data[$key]) && $this->data[$key] < $val)
            return false;
        else
            return true;
    }

    function maxlength($key, $val)
    {
        if (!empty($this->data[$key]) && strlen($this->data[$key]) > $val)
            return false;
        else
            return true;
    }

    function in_array($key, $val)
    {
        if (!empty($this->data[$key]) && !in_array($this->data[$key], $val))
            return false;
        else
            return true;
    }

    function numeric($key, $val)
    {
        if (!empty($this->data[$key]) && $val && !is_numeric($this->data[$key]))
            return false;
        else
            return true;
    }

    public function setError($msg = 'error')
    {
        $this->status = 0;
        $this->error = ['status' => false, 'msg' => $msg];
    }
}
