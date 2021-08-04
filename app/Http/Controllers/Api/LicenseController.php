<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateLicenceRequest;
use App\Http\Requests\Api\CheckLicenceRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\UsedLicence;
use App\Services\HashService;
use App\Services\LicenseService;
use App\Services\LogService;
use App\Services\MobileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    //////////////////////////////////////////////////////
    ///
    /// to new developers:
    /// I forced to use different parameters of our database in api
    /// because our client's application is hardcoded and developer of that
    /// Doesn't change these keys to something that is in
    /// our database, so I change my code to that keys !!
    /// if you don't understand what is going on here, i must tell you me ether!
    /// it's all just because client want's this and nobody listens to my advices.
    ///
    //////////////////////////////////////////////////////


    public function activate(ActivateLicenceRequest $request)
    {
        $product = Product::where('name', $request->product_name)->first();

        if ($request->license == 'TRIAL') {
            $last_used_licence = UsedLicence::where('fingerprint', $request->machine_fingerprint)->whereHas('license', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->first();

            if ($last_used_licence) {
                // Can not active trial for this product
                LogService::log('cannot_use_trial_licence', $last_used_licence, null, ['fingerprint' => $request->machine_fingerprint]);
                return response(['message' => 'Can not use trial license for this product.', 'errors' => ['key' => 'شما قبلا از لایسنس دیگری برای این محصول استفاده کرده اید و مجاز به دریافت لایسنس آزمایشی جدید نیستید.']], 480);
            }

            $license = LicenseService::create('trial', 1, 1, 1, $product->id, 40);
        } else {
            $license = License::with('used', 'product')->where('key', $request->license)->first();

            if (!$license) {
                // License not exist
                return response(['message' => 'Invalid license', 'errors' => ['key' => 'این لایسنس در سیستم موجود نیست']], 481);
            }
        }

        if ($license->product->name != $request->product_name) {
            // Wrong product name
            LogService::log('wrong_product_name', $license, null, $request->validated());
            return response(['message' => 'Product name is incorrect', 'errors' => ['product_name' => 'این لایسنس با این محصول مطابقت ندارد']], 482);
        }

        if (!$license->status) {
            // Inactive license
            LogService::log('inactive_license', $license, null, ['fingerprint' => $request->machine_fingerprint]);
            return response(['message' => 'Inactive license', 'errors' => ['key' => 'لایسنس مورد نظر غیر فعال است و امکان استفاده از آن مقدور نیست.']], 483);
        }

        if (Carbon::parse($license->expires_at)->setTime(23, 59, 59)->isPast()) {
            // Expired license
            LogService::log('expired_license', $license, null, ['fingerprint' => $request->machine_fingerprint]);
            return response(['message' => 'Expired license', 'errors' => ['key' => 'مهلت استفاده از این لاینس به پایان رسیده است']], 484);
        }

        if (now()->diffInDays($license->created_at) > config('settings.license_deadline_for_use')) {
            // Can not use this license after deadline
            LogService::log('can_not_use_license_after_deadline', $license, null, $request->validated());
            return response(['message' => 'Can not use this license after deadline', 'errors' => ['key' => 'امکان استفاده از این لایسنس تنها تا ' . config('settings.license_deadline_for_use') . ' روز بعد از ایجاد آن وجود دارد.']], 487);
        }

        if ($license->expires_at) {
            $last_used_fingerprints = array_unique($license->used->pluck('fingerprint')->toArray());
            if (!in_array($request->machine_fingerprint, $last_used_fingerprints) and count($last_used_fingerprints) >= $license->max_use) {
                // Over use
                LogService::log('over_use_license', $license, null, ['fingerprint' => $request->machine_fingerprint]);
                return response(['message' => 'Overused license', 'errors' => ['key' => 'حداکثر استفاده از این لاینس به پایان رسیده و استفاده از این لایسنس برای این دستگاه مقدور نیست']], 485);
            }
            LogService::log('reuse_license', $license, null, ['fingerprint' => $request->machine_fingerprint]);
        } else {
            // First use
            $this->validate($request, [
                'first_name' => 'required|string|max:250',
                'last_name' => 'required|string|max:250',
                'country' => 'required|string|max:250',
                'company_name' => 'nullable|string|max:250',
                'email' => 'required|email|max:250',
                'phone_number' => ['required', function ($attribute, $value, $fail) {
                    if (!MobileService::validate($value, false)['status']) {
                        foreach (MobileService::validate($value, false)['errors'] as $error) {
                            $fail($error);
                        }
                    }

                    return true;
                }],
                'city' => 'required|string|max:250',
            ]);

            $license->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => MobileService::generate($request->phone_number),
                'email' => $request->email,
                'country' => $request->country,
                'city' => $request->city,
                'company' => $request->company_name,
                'expires_at' => LicenseService::getExpireDate($license->type)
            ]);

            LogService::log('first_use', $license, null, ['fingerprint' => $request->machine_fingerprint]);
        }

        DB::beginTransaction();
        try {
            do {
                $username = HashService::rand(15);
            } while (UsedLicence::where('username', $username)->count());

            $used_licence = UsedLicence::firstOrCreate([
                'license_id' => $license->id,
                'version' => $request->version,
                'device_name' => $request->device_name,
                'fingerprint' => $request->machine_fingerprint,
            ], [
                'username' => $username,
                'password' => HashService::rand(15)
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            LogService::log('create_used_license_failed', $license, null, ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
            return response(['message' => 'Store licence data failed', 'errors' => ['key' => 'هنگام ذخیره اطلاعات مشتری مشکلی به وجود آمده، لطفا دوباره امتحان کنید.', $exception->getMessage()]], 486);
        }

        return response([
            'message' => 'You can use this license',
            'data' => [
                'license' => $license->key,
                'license_type' => $this->getLicenseTypeMonthNumber($license->type),
                'product_name' => $license->product->name,
                'active_date' => Carbon::parse($license->used()->get()->first()->created_at)->timestamp,
                'expire_date' => Carbon::parse($license->expires_at)->setTime(23, 59, 59)->timestamp,
                'first_name' => $license->first_name,
                'last_name' => $license->last_name,
                'email' => $license->email,
                'phone_number' => $license->phone,
                'country' => $license->country,
                'city' => $license->city,
                'company_name' => $license->company,
                'version' => $used_licence->version,
                'machine_fingerprint' => $used_licence->fingerprint,
                'device_name' => $used_licence->device_name,
                'username' => $used_licence->username,
                'password' => $used_licence->password,
            ]
        ]);
    }

    public function check(CheckLicenceRequest $request)
    {
        $license = License::with('used', 'product')->where('key', $request->license)->first();

        if (!$license) {
            // License not exist
            return response(['message' => 'Invalid license', 'errors' => ['key' => 'این لایسنس در سیستم موجود نیست']], 481);
        }

        if ($license->product->name != $request->product_name) {
            // Wrong product name
            return response(['message' => 'Product name is incorrect', 'errors' => ['product_name' => 'این لایسنس با این محصول مطابقت ندارد']], 482);
        }

        if (!$license->status) {
            // Inactive license
            return response(['message' => 'Inactive license', 'errors' => ['key' => 'لایسنس مورد نظر غیر فعال است و امکان استفاده از آن مقدور نیست.']], 483);
        }

        if (Carbon::parse($license->expires_at)->setTime(23, 59, 59)->isPast()) {
            // Expired license
            return response(['message' => 'Expired license', 'errors' => ['key' => 'مهلت استفاده از این لاینس به پایان رسیده است']], 484);
        }

        if (now()->diffInDays($license->created_at) > config('settings.license_deadline_for_use')) {
            // Can not use this license after deadline
            return response(['message' => 'Can not use this license after deadline', 'errors' => ['key' => 'امکان استفاده از این لایسنس تنها تا ' . config('settings.license_deadline_for_use') . ' روز بعد از ایجاد آن وجود دارد.']], 487);
        }

        if ($license->expires_at) {
            $last_used_fingerprints = array_unique($license->used->pluck('fingerprint')->toArray());
            if (!in_array($request->machine_fingerprint, $last_used_fingerprints) and count($last_used_fingerprints) >= $license->max_use) {
                // Over use
                return response(['message' => 'Overused license', 'errors' => ['key' => 'حداکثر استفاده از این لاینس به پایان رسیده و استفاده از این لایسنس برای این دستگاه مقدور نیست']], 485);
            }
        }

        return response([
            'message' => 'You can use this license',
            'data' => [
                'license' => $license->key,
                'license_type' => $this->getLicenseTypeMonthNumber($license->type),
                'machine_fingerprint' => $request->machine_fingerprint
            ]
        ]);
    }

    public function getLicenseTypeMonthNumber($license_type)
    {
        if ($license_type == 'trial') return 'TRIAL';

        return 'GENUINE-' . explode('_', $license_type)[0];
    }
}
