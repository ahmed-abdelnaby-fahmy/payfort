<?php


namespace Payfort\Validation;

use Payfort\Exceptions\PayfortException;
use Payfort\Config;

class Services extends Config
{
    /**
     * @var array
     */
    protected $services;
    protected $rules;
    protected $data;

    public function __construct()
    {
        $this->rules['payment_options'] = ['MASTERCARD', 'VISA', 'AMEX',
            'MADA', //  (for Purchase operations and eci Ecommerce only)
            'MEEZA', // (for Purchase operations and ECOMMERCE eci only)
        ];
        $this->rules['eci'] = ['ECOMMERCE', 'MOTO', 'RECURRING',];
        $this->rules['digital_wallets'] = ['APPLE_PAY'];
        $this->rules['commands'] = ['AUTHORIZATION','PURCHASE','REFUND',];
        $this->rules['service_commands'] = [
            'TOKENIZATION', 'SDK_TOKEN', 'GET_BATCH_RESULTS', 'VERIFY_CARD',
            'PROCESS_BATCH', 'UPLOAD_BATCH_FILE', 'CURRENCY_CONVERSION',
            'CREATE_TOKEN', 'BILL_PRESENTMENT', 'PAYMENT_LINK',
        ];
        $this->rules['query_commands'] = [
            'CHECK_VERIFY_CARD_STATUS', 'GENERATE_REPORT', 'CHECK_STATUS', 'GET_TOKENS',
            'DOWNLOAD_REPORT', 'GET_REPORT', 'GET_INSTALLMENTS_PLANS',
        ];
        $this->services['tokenization'] = [
            "service_command" => ['required' => 1, 'in_array' => $this->rules['service_commands']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "merchant_reference" => ['required' => 1, 'maxlength' => 40],
            "amount" => ['required' => 0],
            "currency" => ['required' => 1, 'maxlength' => 3],
            "language" => ['required' => 1, 'maxlength' => 2],
            "signature" => ['required' => 1, 'maxlength' => 600],
            "expiry_date" => ['required' => 0, 'maxlength' => 4],
            "card_number" => ['required' => 0, 'min' => 16, 'maxlength' => 19],
            "card_security_code" => ['required' => 0, 'maxlength' => 4],
            "card_holder_name" => ['required' => 0, 'maxlength' => 50],
            "token_name" => ['required' => 0, 'maxlength' => 160],
            "remember_me" => ['required' => 0, 'in_array' => ['YES', 'NO']],
            'recurring_mode' => ['required' => 0],
            'recurring_transactions_count' => ['required' => 0],
            "return_url"
        ];

        $this->services['apple_pay'] = [
            "command" => ['required' => 1, 'in_array' => $this->rules['commands']],
            "digital_wallet" => ['required' => 1, 'in_array' => $this->rules['digital_wallets']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "merchant_reference" => ['required' => 1, 'maxlength' => 40],
            "amount" => ['required' => 1],
            "customer_ip" => ['required' => 0],
            "customer_email",
            "apple_data" => ['required' => 1, 'maxlength' => 500],
            "apple_signature" => ['required' => 1, 'maxlength' => 3000],
            "apple_header",
            "apple_paymentMethod",
            'eci' => ['required' => 0, 'in_array' => $this->rules['eci']],
            "order_description" => ['required' => 0, 'maxlength' => 150],
            "customer_name" => ['required' => 0, 'maxlength' => 40],
            "phone_number" => ['required' => 0, 'maxlength' => 19],
            "currency" => ['required' => 1, 'maxlength' => 3],
            "language" => ['required' => 1, 'maxlength' => 2],
            "signature" => ['required' => 1, 'maxlength' => 600]
        ];
        $this->services['authorization'] = [
            "command" => ['required' => 1, 'in_array' => $this->rules['commands']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "merchant_reference" => ['required' => 1, 'maxlength' => 40],
            "amount" => ['required' => 1],
            "currency" => ['required' => 1, 'maxlength' => 3],
            "language" => ['required' => 1, 'maxlength' => 2],
            "customer_email" => ['required' => 1],
            "customer_ip" => ['required' => 0],
            "signature" => ['required' => 1, 'maxlength' => 600],
            "order_description" => ['required' => 0, 'maxlength' => 160],
            "card_security_code" => ['required' => 0, 'maxlength' => 4],
            "token_name" => ['required' => 1, 'maxlength' => 160],
            "remember_me" => ['required' => 0, 'in_array' => ['YES', 'NO']],
            'payment_option' => ['required' => 0, 'in_array' => $this->rules['payment_options']],
            'eci' => ['required' => 0, 'in_array' => $this->rules['eci']],
            'phone_number' => ['required' => 0, 'maxlength' => 19],
            'settlement_reference' => ['required' => 0, 'maxlength' => 34],
            'installments' => ['required' => 0],
            "return_url",
            'merchant_extra' => ['required' => 0],
            'merchant_extra1' => ['required' => 0],
            'merchant_extra2' => ['required' => 0],
            'merchant_extra3' => ['required' => 0],
            'merchant_extra4' => ['required' => 0],
            'merchant_extra5' => ['required' => 0],
            'recurring_mode' => ['required' => 0],
            'recurring_transactions_count' => ['required' => 0],
            'agreement_id' => ['required' => 0],
            'recurring_days_between_payments' => ['required' => 0],
            'recurring_expiry_date' => ['required' => 0],
        ];
        $this->services['check_status'] = [
            "query_command" => ['required' => 1, 'in_array' => $this->rules['query_commands']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "merchant_reference" => ['required' => 0, 'maxlength' => 40],
            "language" => ['required' => 1, 'maxlength' => 2],
            "signature" => ['required' => 1, 'maxlength' => 600],
            'fort_id' => ['required' => 1, 'maxlength' => 60],
            'return_third_party_response_codes' => ['required' => 0, 'in_array' => ['YES', 'NO']],
        ];
        $this->services['refund'] = [
            "command" => ['required' => 1, 'in_array' => $this->rules['commands']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "merchant_reference" => ['required' => 0, 'maxlength' => 40],
            "language" => ['required' => 1, 'maxlength' => 2],
            "currency" => ['required' => 1, 'maxlength' => 3],
            "amount" => ['required' => 1, 'maxlength' => 20],
            "signature" => ['required' => 1, 'maxlength' => 600],
            'fort_id' => ['required' => 1, 'maxlength' => 60],
        ];
        $this->services['payment'] = [
            "command" => ['required' => 1, 'in_array' => $this->rules['commands']],
            "access_code" => ['required' => 1, 'maxlength' => 60],
            "merchant_identifier" => ['required' => 1, 'maxlength' => 20],
            "signature" => ['required' => 1, 'maxlength' => 600],
            "order_description" => ['required' => 0, 'maxlength' => 150],
            'amount',
            "currency" => ['required' => 1, 'maxlength' => 3],
            "language",
            'customer_email' => ['required' => 1],
            'agreement_id' => ['required' => 0],
            'return_url',
            "merchant_reference" => ['required' => 1, 'maxlength' => 60],
            "token_name" => ['required' => 1, 'maxlength' => 160],
            'eci' => ['required' => 0, 'in_array' => $this->rules['eci']],
        ];
    }

    public function validator($data, $service)
    {
        if (!empty($data) && !empty($service) && in_array($service, array_keys($this->services))) {
            $parms = $this->services[$service];
            $new = new Validator($parms, $data);
            $this->data = $new->data;
            if (!$new->status)
                throw new PayfortException($new->error['msg']);

            return $new;
        } else
            throw new PayfortException('invalid data service not find');

    }

}
