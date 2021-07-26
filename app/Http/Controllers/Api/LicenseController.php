<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateLicenceRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\UsedLicence;
use App\Services\HashService;
use App\Services\LicenseService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function activate(ActivateLicenceRequest $request)
    {
        $product = Product::where('name', $request->product_name)->first();

        if ($request->key == 'trial') {
            $last_used_licence = UsedLicence::where('fingerprint', $request->fingerprint)->whereHas('license', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->first();

            if ($last_used_licence) {
                // Can not active trial for this product
                LogService::log('cannot_use_trial_licence', $last_used_licence, null, ['fingerprint' => $request->fingerprint]);
                return response(['message' => 'Can not use trial license for this product.', 'errors' => ['key' => 'شما قبلا از لایسنس دیگری برای این محصول استفاده کرده اید و مجاز به دریافت لایسنس آزمایشی جدید نیستید.']], 480);
            }

            $license = LicenseService::create('trial', 1, 1, 1, $product->id, 40);
        } else {
            $license = License::with('used', 'product')->where('key', $request->key)->first();

            if (!$license) {
                // License not exist
                return response(['message' => 'Invalid license', 'errors' => ['key' => 'این لایسنس در سیستم موجود نیست']], 481);
            }
        }

        if ($license->product->name != $request->product_name) {
            // wrong product name
            LogService::log('wrong_product_name', $license, null, $request->validated());
            return response(['message' => 'The given data was invalid.', 'errors' => ['product_name' => 'این لایسنس با این محصول مطابقت ندارد']], 482);
        }

        if (!$license->status) {
            // inactive license
            LogService::log('inactive_license', $license, null, ['fingerprint' => $request->fingerprint]);
            return response(['message' => 'Inactive license', 'errors' => ['key' => 'لایسنس مورد نظر غیر فعال است و امکان استفاده از آن مقدور نیست.']], 483);
        }

        if (Carbon::parse($license->expires_at)->setTime(23, 59, 59)->isPast()) {
            // expired license
            LogService::log('expired_license', $license, null, ['fingerprint' => $request->fingerprint]);
            return response(['message' => 'Expired license', 'errors' => ['key' => 'مهلت استفاده از این لاینس به پایان رسیده است']], 484);
        }

        if ($license->expires_at) {
            $last_used_fingerprints = array_unique($license->used->pluck('fingerprint')->toArray());
            if (!in_array($request->fingerprint, $last_used_fingerprints) and count($last_used_fingerprints) >= $license->max_use) {
                // over use
                LogService::log('over_use_license', $license, null, ['fingerprint' => $request->fingerprint]);
                return response(['message' => 'Overused license', 'errors' => ['key' => 'حداکثر استفاده از این لاینس به پایان رسیده و استفاده از این لایسنس برای این دستگاه مقدور نیست']], 485);
            }
            LogService::log('reuse_license', $license, null, ['fingerprint' => $request->fingerprint]);
        } else {
            // first use
            $license->update([
                'expires_at' => $license->type == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth()
            ]);
            LogService::log('first_use', $license, null, ['fingerprint' => $request->fingerprint]);
        }

        DB::beginTransaction();
        try {
            do {
                $username = HashService::rand(15);
            } while (UsedLicence::where('username', $username)->count());

            $used_licence = UsedLicence::firstOrCreate([
                'license_id' => $license->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'country' => $request->country,
                'city' => $request->city,
                'version' => $request->version,
                'device_name' => $request->device_name,
                'fingerprint' => $request->fingerprint,
                'company' => $request->company,
            ], [
                'username' => $username,
                'password' => HashService::rand(15)
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            LogService::log('create_used_license_failed', $license, null, ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
            return response(['message' => 'Store licence data failed', 'errors' => ['key' => 'هنگام ذخیره اطلاعات مشتری مشکلی به وجود آمده، لطفا دوباره امتحان کنید.']], 486);
        }

        return response([
            'message' => 'You can use this license',
            'data' => [
                'key' => $license->key,
                'type' => $license->type,
                'product_name' => $license->product->name,
                'first_use_date' => Carbon::parse($license->used()->get()->first()->created_at)->timestamp,
                'expires_at' => Carbon::parse($license->expires_at)->setTime(23, 59, 59)->timestamp,
                'first_name' => $used_licence->first_name,
                'last_name' => $used_licence->last_name,
                'email' => $used_licence->email,
                'phone' => $used_licence->phone,
                'country' => $used_licence->country,
                'city' => $used_licence->city,
                'company' => $used_licence->company,
                'version' => $used_licence->version,
                'fingerprint' => $used_licence->fingerprint,
                'device_name' => $used_licence->device_name,
                'username' => $used_licence->username,
                'password' => $used_licence->password,
            ]
        ]);
    }
}
