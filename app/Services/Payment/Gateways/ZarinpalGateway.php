<?php


namespace App\Services\Payment\Gateways;


use App\Models\Transaction;

class ZarinpalGateway extends Gateway
{
    /*
    All responses must follow this style

    return [
       'status' => true / false,
       'code' => [integer],
       'message' => [string]
    ];
   */
    protected $client;

    public function __construct()
    {
        $this->client = new \SoapClient(config('payment.zarinpal.url') . '/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
    }

    public function redirect($amount, $callback, $description = null, $mobile = null, $email = null): array
    {
        try {
            Transaction::create([
                'status' => 'pending',
                'gateway' => config('payment.default'),
                'amount' => $amount,
                'description' => $description,
                'ip' => request()->ip()
            ]);

            $result = $this->client->PaymentRequest([
                'MerchantID' => config('payment.zarinpal.merchant'),
                'Amount' => $amount,
                'Description' => $description,
                'Email' => $email,
                'Mobile' => $mobile,
                'CallbackURL' => $callback,
            ]);

            $code = intval($result->Status);

            if ($result->Status != 100) {
                return [
                    'status' => false,
                    'code' => $code,
                    'message' => $this->translate_responses($code),
                ];
            }

            return [
                'status' => true,
                'code' => $code,
                'message' => $this->translate_responses($code),
                'redirect_url' => config('payment.zarinpal.url') . '/pg/StartPay/' . $result->Authority
            ];
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function verify($request): array
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        if ($transaction->status == 'cancel') {
            return [
                'status' => false,
                'code' => 2,
                'message' => 'تراکنش کنسل شده و امکان پرداخت آن وجود ندارد'
            ];
        } elseif ($transaction->status == 'success') {
            return [
                'status' => false,
                'code' => 2,
                'message' => 'تراکنش از قبل پرداخت شده و امکان پرداخت آن وجود ندارد'
            ];
        }

        $code = intval($request->Status);

        if ($request->Status != 'OK') {
            $transaction->update([
                'status' => 'cancel'
            ]);

            return [
                'status' => false,
                'code' => $code,
                'message' => $this->translate_responses($code)
            ];
        }

        try {
            $result = $this->client->PaymentVerification([
                'MerchantID' => config('payment.zarinpal.merchant'),
                'Authority' => $request->Authority,
                'Amount' => $request->amount
            ]);

            $verify_status_code = intval($result->Status);

            if ($verify_status_code == 100) {
                $transaction->update([
                    'status' => 'success',
                    'status_code' => $verify_status_code,
                    'ref_id' => $result->RefID,
                    'payed_at' => now()->toDateTimeString()
                ]);
            }

            return [
                'status' => $verify_status_code == 100,
                'code' => $verify_status_code,
                'message' => $this->translate_responses($verify_status_code)
            ];
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function translate_responses($code)
    {
        switch ($code) {
            case '-1':
                return 'اطلاعات ارسال شده ناقص است.';
                break;
            case '-2':
                return 'آی پی یا مرچنت کد پذیرنده صحیح نیست';
                break;
            case '-3':
                return 'با توجه به محدودیت های شاپرک امکان پرداخت با رقم درخواست شده میسر نمی باشد.';
                break;
            case '-4':
                return 'سطح تایید پذیرنده پایین تر از صطح نقره ای است.';
                break;
            case '-11':
                return 'درخواست مورد نظر یافت نشد.';
                break;
            case '-12':
                return 'امکان ویرایش درخواست میسر نمی باشد.';
                break;
            case '-21':
                return 'هیچ نوع عملیات مالی برای این تراکنش یافت نشد.';
                break;
            case '-22':
                return 'تراکنش نا موفق می باشد.';
                break;
            case '-33':
                return 'رقم تراکنش با رقم پرداخت شده مطابقت ندارد.';
                break;
            case '-34':
                return 'سقف تقسیم تراکنش از لحاظ تعداد با رقم عبور نموده است.';
                break;
            case '-40':
                return 'اجازه دسترسی به متد مربوطه وجود ندارد.';
                break;
            case '-41':
                return 'اطلاعات ارسال شده مربوط به AdditionalData غیر معتر می باشد.';
                break;
            case '-42':
                return 'مدت زمان معتبر طول عمر شناسه پرداخت بین 30 دقیقه تا 40 روز می باشد.';
                break;
            case '-54':
                return 'درخواست مورد نظر آرشیو شده است.';
                break;
            case '100':
                return 'عملیات با موفقیت انجام گردیده است.';
                break;
            case '101':
                return 'عملیات پرداخت موفق بوده و قبلا Payment Verification تراکنش انجام شده است';
                break;
            default:
                return null;
                break;
        }
    }
}
