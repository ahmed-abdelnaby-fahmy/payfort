<?php


namespace Payfort;

use Payfort\Exceptions\PayfortException;
use Payfort\Validation\Validator;


abstract class Config
{

    public $merchant_identifier;

    public $access_code;

    public $apple_access_code;

    public $sha_request_phrase;

    public $sha_response_phrase;

    public $apple_sha_request_phrase;

    public $apple_sha_response_phrase;

    public $language;

    public $currency;

    public $sha_type;

    public $return_url;

    public $return_url_tokenization;

    public $is_sandbox;

    protected $config = [];

    protected $log;

    protected $routes;

    protected $merchant;

    protected $data;

    /**
     * get configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }


    /**
     * get payfort route
     *
     * @return array
     */
    public function getRoute(): array
    {
        return $this->routes[$this->is_sandbox ? 'test' : 'live'];
    }

    /**
     * configure the Payfort
     *
     * @param array $config
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function configure(array $config = [])
    {
        if (empty($config))
            throw new PayfortException('something go wrong.');
        $this->config = array_merge($this->config, $config);
        $this->validateConfig($this->config);
        $this->access_code = $this->config['access_code'];
        $this->apple_access_code = $this->config['apple_access_code'];
        $this->merchant_identifier = $this->config['merchant_identifier'];
        $this->sha_request_phrase = $this->config['sha_request_phrase'];
        $this->sha_response_phrase = $this->config['sha_response_phrase'];
        $this->apple_sha_request_phrase = $this->config['apple_sha_request_phrase'];
        $this->apple_sha_response_phrase = $this->config['apple_sha_response_phrase'];
        $this->sha_type = $this->config['sha_type'];
        $this->language = $this->config['language'];
        $this->currency = strtoupper($this->config['currency']);
        $this->is_sandbox = $this->config['is_sandbox'];
        $this->return_url = $this->config['return_url'];
        $this->return_url_tokenization = $this->config['return_url_tokenization'];
        $this->routes = [
            'live' => [
                'redirectUrl' => 'https://checkout.payfort.com/FortAPI/paymentPage',
                'url' => 'https://paymentservices.payfort.com/FortAPI/paymentApi'
            ],
            'test' => [
                'redirectUrl' => 'https://sbcheckout.PayFort.com/FortAPI/paymentPage',
                'url' => 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi'
            ]
        ];
        $this->log = ['command', 'merchant_reference', 'amount', 'signature', 'currency', 'language', 'customer_email', 'order_description', 'return_url'];

    }

    //Currency Decimal Points
    public function getCDP($currency)
    {
        $decimalPoint = 2;
        $arrCurrencies = array(
            'JOD' => 3,
            'KWD' => 3,
            'OMR' => 3,
            'TND' => 3,
            'BHD' => 3,
            'LYD' => 3,
            'IQD' => 3,
        );
        if (isset($arrCurrencies[$currency])) {
            $decimalPoint = $arrCurrencies[$currency];
        }
        return $decimalPoint;
    }

    /**
     * convert amount to payfort format
     *
     * @param string $amount
     * @param $currencyCode
     * @return string
     */
    public function convertAmountFormat(string $amount): string
    {
        $decimalPoints = $this->getCDP('SAR');
        return round($amount, $decimalPoints) * (pow(10, $decimalPoints));
    }

    /**
     * revert amount to original format
     *
     * @param string $amount
     *
     * @return string
     */
    public function revertAmountFormat(string $amount): string
    {
        $decimalPoints = $this->getCDP('SAR');
        return round($amount, $decimalPoints) / (pow(10, $decimalPoints));
    }

    /**
     * @param array $params
     *
     * @throws PayfortException
     */
    public function checkResponse(array $params, $apple = false)
    {
        if (empty($params))
            throw new PayfortException('The response data can not be empty');

        $response_code = $params['response_code'];

        if (substr($response_code, 2) !== '000' && substr($response_code, 2) !== '064')
            throw new PayfortException($params['response_message'] ?? 'Invalid payment status');

        $response_signature = $params['signature'];
        unset($params['signature']);

        $signature = $this->signature($params, 'response', $apple);

        if ($signature !== $response_signature)
            throw new PayfortException('Signature mismatch');
    }

    /**
     * create the data signature
     *
     * @param string $type
     *
     * @return string
     */
    public function signature(array $input, string $type = 'request', $is_apple = false): string
    {
        $string = '';
        ksort($input);
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                $sub_string = '{';
                foreach ($v as $sub_k => $sub_v) {
                    if (!next($v))
                        $sub_string .= "$sub_k=$sub_v";
                    else
                        $sub_string .= "$sub_k=$sub_v, ";
                }
                $sub_string .= '}';
                $string .= "$k=$sub_string";
            } else
                $string .= "$k=$v";
        }

        if ($type == 'request') {
            if ($is_apple)
                $string = $this->apple_sha_request_phrase . $string . $this->apple_sha_request_phrase;
            else
                $string = $this->sha_request_phrase . $string . $this->sha_request_phrase;
        } else {
            if ($is_apple)
                $string = $this->apple_sha_response_phrase . $string . $this->apple_sha_response_phrase;
            else
                $string = $this->sha_response_phrase . $string . $this->sha_response_phrase;
        }

        return hash($this->sha_type, $string);
    }

    protected function except($array, $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array))
                unset($array[$key]);
        }
        return $array;
    }

    protected function validateConfig(array $config)
    {
        $new = new Validator([
            'access_code', 'merchant_identifier',
            'sha_type', 'sha_request_phrase',
            'sha_response_phrase', 'language',
            'currency', 'is_sandbox',
            'return_url', 'return_url_tokenization'], $config);
        $this->data = $new->data;
        if (!$new->status)
            throw new PayfortException($new->error['msg']);
    }


}
