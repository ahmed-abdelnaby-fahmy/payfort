<?php


namespace Payfort;

use Illuminate\Support\Facades\Http;
use Payfort\Exceptions\PayfortException;
use Payfort\Validation\Services;

class Merchant extends Config
{

    public function __construct()
    {
        $this->configure(config('payfort'));
    }

    public function purchase(array $params)
    {
        $params = array_merge([
            'command' => 'PURCHASE',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'language' => $this->language,
            'currency' => $this->currency,
            'return_url' => $this->return_url,
        ], $params);

        $params['amount'] = $this->convertAmountFormat($params['amount']);
        $signature = $this->signature($params);
        $params['signature'] = $signature;

        $validator = new Services();
        $validator = $validator->validator($params, 'authorization');

        return $this->redirect($validator->data);
    }

    public function authorize(array $params)
    {
        $params = array_merge([
            'command' => 'AUTHORIZATION',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'language' => $this->language,
            'currency' => $this->currency,
            'return_url' => $this->return_url,
        ], $params);

        $params['amount'] = $this->convertAmountFormat($params['amount']);
        $signature = $this->signature($params);
        $params['signature'] = $signature;

        $validator = new Services();
        $validator = $validator->validator($params, 'authorization');

        return $this->redirect($validator->data);
    }

    public function payment(array $params)
    {
        $params = array_merge([
            'command' => 'PURCHASE',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'currency' => $this->currency,
            'language' => $this->language,
            'return_url' => $this->return_url,
        ], $params);


        $params['amount'] = $this->convertAmountFormat($params['amount']);
        $signature = $this->signature($params);
        $params['signature'] = $signature;

        $validator = new Services();
        $validator = $validator->validator($params, 'payment');

        return $this->request($validator->data);
    }

    public function applePay(array $params)
    {
        $params = array_merge([
            'digital_wallet' => 'APPLE_PAY',
            'command' => 'PURCHASE',
            'access_code' => $this->apple_access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'currency' => $this->currency,
            'language' => $this->language,
        ], $params);

        $params['amount'] = $this->convertAmountFormat($params['amount']);

        $signature = $this->signature($params, 'request', true);
        $params['signature'] = $signature;
        $validator = new Services();

        $validator = $validator->validator($params, 'apple_pay');
        return $this->request($validator->data);
    }

    public function tokenization(array $params)
    {
        $params = array_merge([
            'service_command' => 'TOKENIZATION',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'language' => $this->language,
            'currency' => $this->currency,
            'return_url' => $this->return_url_tokenization,
        ], $params);

        $signature = $this->signature(
            $this->except($params, [
                'card_security_code',
                'card_number',
                'expiry_date',
                'card_holder_name',
                'remember_me',
            ])
        );

        $params['signature'] = $signature;
        $validator = new Services();

        $validator = $validator->validator($params, 'tokenization');
        return $this->redirect($validator->data);
    }

    public function checkStatus(array $params): array
    {
        $params = array_merge([
            'query_command' => 'CHECK_STATUS',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'language' => $this->language,
        ], $params);
        $signature = $this->signature($params);

        $params['signature'] = $signature;
        $validator = new Services();

        $validator = $validator->validator($params, 'check_status');
        $response = $this->request($validator->data);

        $this->checkResponse($response);
        return $response;
    }

    public function refund(array $params)
    {
        $params = array_merge([
            'command' => 'REFUND',
            'access_code' => $this->access_code,
            'merchant_identifier' => $this->merchant_identifier,
            'currency' => $this->currency,
            'language' => $this->language,
        ], $params);
        if (!empty($params['amount']))
            $params['amount'] = $this->convertAmountFormat($params['amount']);
        $signature = $this->signature($params);
        $params['signature'] = $signature;
        $validator = new Services();
        $validator = $validator->validator($params, 'refund');
        $response = $this->request($validator->data);
        $this->checkResponse($response);
        return $response;
    }

    /**
     * check the transaction status
     *
     * @param array $params
     *
     * @return array
     */
    public function checkTransactionStatus(array $params): array
    {
        $response = $this->checkStatus($params);

        $status = $response['transaction_status'];

        if ($status && !in_array($status, ['04', '14'])) {
            $message = $response['transaction_message'] ?? 'Unknown transaction status';
            throw new PayfortException($message);
        }
        return $response;
    }


    public
    function redirect($data)
    {
        $html = '';
        $html .= '<form action = "' . $this->getRoute()['redirectUrl'] . '" method = "post" name="frm"   >';
        foreach ($data as $a => $b) {
            $html .= '<input type = "hidden" name = "' . htmlentities($a) . '" value = "' . htmlentities($b) . '" >';
        }

        $html .= "\t<script type='text/javascript'>\n";
        $html .= "\t\tdocument.frm.submit();\n";
        $html .= "\t</script>\n";
        $html .= '</form >';

        return view('client.app.payment')->with(compact('html'));
    }

    public function request($data)
    {
        return Http::connectTimeout(10)->post($this->getRoute()['url'], $data)->json();
    }


    public function checkTransaction($data, $apple = false)
    {
        $this->checkResponse($data, $apple);
    }
}
