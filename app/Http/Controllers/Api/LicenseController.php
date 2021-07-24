<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\LogService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function check(Request $request)
    {
        $this->validate($request, [
            'license' => 'required|string|max:250',
            'product_name' => 'required|string|max:250',
            '‫‪machin_fingerprint‬‬' => 'required|string|max:250'
        ]);

        $license = License::with('used', 'product')->where('key', $request->license)->first();

        // invalid licence
        if (!$license) {
            return $this->response(['license_status' => 905]);
        }

        if ($license->product->name != $request->product_name) {
            LogService::log('wrong_product_name', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 906]);
        }

        // re use license
        if ($license->used->where('fingerprint', $request->‫‪machin_fingerprint‬‬)->count()) {
            LogService::log('re_use_license', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 902]);
        }

        // over use
        if ($license->used->count() > $license->max_use) {
            LogService::log('over_use_license', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 903]);
        }

        // first use
        return $this->response(['license_status' => 901]);
    }

    public function activate(Request $request)
    {
        $this->validate($request, [
            'license' => 'required|string|max:250',
            'first_name' => 'required|string|max:250',
            'last_name' => 'required|string|max:250',
            'country' => 'required|string|max:250',
            'company_name' => 'nullable|string|max:250',
            'email' => 'required|email|max:250',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'city' => 'required|string|max:250',
            'av_version' => 'required|string|max:250',
            '‫‪machin‬‬_fingerprint' => 'required|string|max:250',
            'device_name' => 'required|string|max:250',
            'product_name' => 'required|string|max:250',
        ]);

        $license = License::with('used', 'product')->where('key', $request->license)->first();

        // invalid licence
        if (!$license) {
            return $this->response(['license_status' => 905]);
        }

        if ($license->product->name != $request->product_name) {
            LogService::log('wrong_product_name', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 906]);
        }

        // re use license
        if ($license->used->where('fingerprint', $request->‫‪machin_fingerprint‬‬)->count()) {
            LogService::log('re_use_license', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 902]);
        }

        // over use
        if ($license->used->count() > $license->max_use) {
            LogService::log('over_use_license', $license, null, ['fingerprint' => $request->‫‪machin_fingerprint‬‬]);
            return $this->response(['license_status' => 903]);
        }


    }

    public function response(array $data): array
    {
        return [
            'status' => true,
            'message' => 'success',
            'code' => 0,
            'data' => $data
        ];
    }
}
