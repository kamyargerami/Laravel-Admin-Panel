<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActivateLicenceRequest;
use App\Models\License;
use App\Models\UsedLicence;
use App\Services\HashService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function activate(ActivateLicenceRequest $request)
    {
        $license = License::with('used', 'product')->where('key', $request->key)->first();

        if ($license->product->name != $request->product_name) {
            // wrong product name
            LogService::log('wrong_product_name', $license, null, $request->validated());
            return response(['message' => 'The given data was invalid.', 'errors' => ['product_name' => 'این لایسنس با این محصول مطابقت ندارد']], 422);
        }

        if ($license->used->where('fingerprint', $request->fingerprint)->count()) {
            // re use license
            LogService::log('reuse_license', $license, null, ['fingerprint' => $request->fingerprint]);
            $reuse = true;
        } elseif (count(array_unique($license->used->pluck('fingerprint')->toArray())) >= $license->max_use) {
            // over use
            LogService::log('over_use_license', $license, null, ['fingerprint' => $request->fingerprint]);
            return response(['message' => 'Overused license', 'errors' => ['key' => 'حداکثر استفاده از این لاینس به پایان رسیده و استفاده از این لایسنس برای این دستگاه مقدور نیست']], 490);
        }

        if (!$license->status) {
            // inactive license
            LogService::log('inactive_license', $license, null, ['fingerprint' => $request->fingerprint]);
            return response(['message' => 'Inactive license', 'errors' => ['key' => 'لایسنس مورد نظر غیر فعال است و امکان استفاده از آن مقدور نیست.']], 491);
        }

        DB::beginTransaction();

        try {
            if (!isset($reuse) and !$license->expires_at) {
                // first use
                $license->update([
                    'expires_at' => $license->type == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth()
                ]);
                LogService::log('first_use', $license, null, ['fingerprint' => $request->fingerprint]);
            }

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
            return response(['message' => 'Store licence data failed', 'errors' => ['key' => 'هنگام ذخیره اطلاعات مشتری مشکلی به وجود آمده، لطفا دوباره امتحان کنید.']], 492);
        }

        $first_use = $license->used->first();

        return response([
            'key' => $license->key,
            'type' => $license->type,
            'product_name' => $license->product->name,
            'use_type' => isset($reuse) ? 'reuse' : 'first_use',
            'first_use_date' => $first_use ? Carbon::createFromFormat('Y-m-d H:i:s', $first_use->created_at)->timestamp : Carbon::now()->timestamp,
            'expires_at' => Carbon::createFromFormat('Y-m-d', $license->expires_at)->setHour(24)->setMinute(0)->setSecond(0)->timestamp,
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
        ]);
    }
}
