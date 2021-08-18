<?php


namespace App\Services\Payment;


use App\Services\Payment\Gateways\ZarinpalGateway;
use App\Services\Service;
use Illuminate\Http\Request;

class PaymentService extends Service
{
    /*
      All responses must follow this style

       return [
         'status' => true / false,
         'code' => [integer],
         'message' => [string]
      ];
     */

    protected $gateway;

    public function __construct()
    {
        if (config('payment.default') == 'zarinpal')
            $this->gateway = new ZarinpalGateway();
    }

    public function redirect($amount, $details = null, $parameters = []): array
    {
        return $this->gateway->redirect($amount, $details, $parameters);
    }

    public function verify(Request $request): array
    {
        return $this->gateway->verify($request);
    }
}
