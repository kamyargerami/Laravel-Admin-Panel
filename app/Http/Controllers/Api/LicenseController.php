<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\LogService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function activate(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|string|max:250|exists:licenses,key',
            'first_name' => 'required|string|max:250',
            'last_name' => 'required|string|max:250',
            'country' => 'required|string|max:250',
            'company' => 'nullable|string|max:250',
            'email' => 'required|email|max:250',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'city' => 'required|string|max:250',
            'version' => 'required|string|max:250',
            'fingerprint' => 'required|string|max:250',
            'device_name' => 'required|string|max:250',
            'product_name' => 'required|string|max:250|exists:products,name',
        ]);

        $license = License::with('used', 'product')->where('key', $request->key)->first();

        if ($license->product->name != $request->product_name) {
            LogService::log('wrong_product_name', $license, null, ['fingerprint' => $request->fingerprint]);
            return response([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'product_name' => 'این لایسنس با این محصول مطابقت ندارد'
                ]
            ], 422);
        }


        if ($license->used->count() >= $license->max_use) {
            if ($license->used->where('fingerprint', $request->fingerprint)->count()) {
                // re use license
                LogService::log('re_use_license', $license, null, ['fingerprint' => $request->fingerprint]);
            } else {
                // over use
                LogService::log('over_use_license', $license, null, ['fingerprint' => $request->fingerprint]);
                return response([
                    'message' => 'Overused license',
                    'errors' => [
                        'key' => 'حداکثر استفاده از این لاینس به پایان رسیده و استفاده از این لایسنس برای این دستگاه مقدور نیست'
                    ]
                ], 490);
            }
        }

        dd($request->all());
    }
}
