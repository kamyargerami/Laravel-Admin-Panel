<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddLicenceRequest;
use App\Http\Requests\Admin\MultiUpdateRequest;
use App\Http\Requests\Admin\SendNotificationRequest;
use App\Http\Requests\Admin\UpdateLicenceRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\UsedLicence;
use App\Models\User;
use App\Services\Email;
use App\Services\Helper;
use App\Services\LicenseService;
use App\Services\LogService;
use App\Services\MobileService;
use App\Services\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LicenseController extends Controller
{
    public function getResultQuery(Request $request)
    {
        return License::with('user', 'product')->where(function ($query) use ($request) {
            foreach (['id', 'user_id', 'product_id', 'key', 'email', 'type'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }

            if ($request->phone)
                $query->where('phone', MobileService::generate($request->phone));

            if ($request->from_id)
                $query->where('id', '>=', $request->from_id);
            if ($request->to_id)
                $query->where('id', '<=', $request->to_id);

            if ($request->from_created)
                $query->where('created_at', '>=', $request->from_created);
            if ($request->to_created)
                $query->where('created_at', '<=', $request->to_created);
            if ($request->from_expires)
                $query->where('created_at', '>=', $request->from_expires);
            if ($request->to_expires)
                $query->where('created_at', '<=', $request->to_expires);

            if ($request->from_first_use)
                $query->whereHas('first_use', function ($q) use ($request) {
                    $q->where('created_at', '>=', $request->from_first_use);
                });
            if ($request->to_first_use)
                $query->whereHas('first_use', function ($q) use ($request) {
                    $q->where('created_at', '<=', $request->to_first_use);
                });

            if (isset($request->used)) {
                if ($request->used) {
                    $query->whereHas('used');
                } else {
                    $query->doesntHave('used');
                }
            }

            if (auth()->user()->cannot('read_others_data')) {
                $query->where('user_id', auth()->id());
            }
        });
    }

    public function index(Request $request)
    {
        $order_by = Helper::orderBy($request->order_by);

        $licenses = $this->getResultQuery($request)->with('used')->orderBy($order_by[0], $order_by[1])->paginate();
        $products = Product::all();
        $users = User::all();

        return view('pages.admin.license.index', compact('licenses', 'products', 'users'));
    }

    public function add()
    {
        $products = Product::all();
        $users = User::all();

        return view('pages.admin.license.add', compact('products', 'users'));
    }

    public function store(AddLicenceRequest $request)
    {
        if (auth()->user()->cannot('store_data_for_others') and $request->user_id != auth()->id()) {
            return back()->withErrors(['شما دسترسی ایجاد لایسنس برای افراد دیگر را ندارید']);
        }

        $first_id = null;
        $last_id = null;

        for ($i = 0; $i < $request->quantity; $i++) {
            $license = LicenseService::create($request->type, $request->max_use, $request->user_id, $request->status, $request->product_id, $request->character_length);
            LogService::log('new_license', $license, auth()->id());

            if ($i == 0)
                $first_id = $license->id;

            if ($i == $request->quantity - 1)
                $last_id = $license->id;
        }

        return redirect()->route('admin.license.list', [
            'from_id' => $first_id,
            'to_id' => $last_id,
        ])->with(['success' => 'لایسنس های جدید با موفقیت اضافه شد']);
    }

    public function edit(License $license)
    {
        $products = Product::all();
        $users = User::all();

        return view('pages.admin.license.edit', compact('license', 'products', 'users'));
    }

    public function update(License $license, UpdateLicenceRequest $request)
    {
        if (auth()->user()->cannot('change_others_data') and $request->user_id != auth()->id()) {
            return back()->withErrors(['شما دسترسی ویرایش لایسنس برای افراد دیگر را ندارید']);
        }

        $data = [];
        foreach ($request->validated() as $key => $value) {
            if ($license->$key != $value) {
                $data[$key] = $value;
            }
        }

        if (count($data)) {
            $license->update($data);
            LogService::log('license_updated', $license, auth()->id(), $data);
        }

        return back()->with('success', 'لایسنس با موفقیت ویرایش شد');
    }

    public function delete(License $license)
    {
        $count_used_licences = $license->used()->count();

        if ($count_used_licences) {
            return back()->withErrors(['این لایسنس مشتری دارد و شما مجاز به حذف آن نیستید، ابتدا رکورد مشتریان این لایسنس را پاک کنید']);
        }

        if (auth()->user()->cannot('delete_others_data') and $license->user_id != auth()->id()) {
            return back()->withErrors(['شما دسترسی حذف لایسنس برای افراد دیگر را ندارید']);
        }

        LogService::log('license_deleted', $license, auth()->id());

        $license->delete();

        return back()->with('success', 'لایسنس با موفقیت حذف شد');
    }

    public function export(Request $request)
    {
        $result = 'key,type,status,max_use,product,user,expires_at,created_at' . PHP_EOL;

        $this->getResultQuery($request)->chunk(200, function ($licenses) use (&$result) {
            foreach ($licenses as $license) {
                $result .= $license->key . ',' . $license->type . ',' . $license->status . ',' . $license->max_use . ',' . $license->product->name . ',' . $license->user->name . ',' . $license->expires_at . ',' . $license->created_at . PHP_EOL;
            }
        });

        return Response::make($result, 200, [
            'Content-type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
            'Content-Disposition' => 'attachment; filename="export_' . Carbon::now()->timestamp . '.csv"',
        ]);
    }

    public function multiUpdate(MultiUpdateRequest $request)
    {
        if (auth()->user()->cannot('change_others_data') and $request->user_id != auth()->id()) {
            return back()->withErrors(['شما دسترسی ویرایش لایسنس برای افراد دیگر را ندارید']);
        }

        $this->getResultQuery($request)->chunk(200, function ($licenses) use ($request) {
            foreach ($licenses as $license) {
                $data = [];

                foreach (['product_id', 'type', 'user_id', 'max_use', 'status', 'expires_at'] as $column) {
                    if ($request->get('new_' . $column) != '' and $license->$column != $request->get('new_' . $column)) {
                        $data[$column] = $request->get('new_' . $column);
                    }
                }

                if (!count($data)) {
                    return back()->withErrors(['شما هیچ فیلدی را تغییر ندادید در نتیجه تغییری اعمال نشد']);
                }

                $license->update($data);

                LogService::log('license_updated', $license, auth()->id(), $data);
            }
        });

        return back()->with('success', 'لایسنس های مورد نظر ویرایش شدند.');
    }

    public function multiDelete(Request $request)
    {
        $this->getResultQuery($request)->chunk(200, function ($licenses) {
            foreach ($licenses as $license) {
                $count_used_licences = $license->used()->count();

                if ($count_used_licences) {
                    return back()->withErrors(['این لایسنس مشتری دارد و شما مجاز به حذف آن نیستید، ابتدا رکورد مشتریان این لایسنس را پاک کنید']);
                }

                if (auth()->user()->cannot('delete_others_data') and $license->user_id != auth()->id()) {
                    return back()->withErrors(['شما دسترسی حذف لایسنس برای افراد دیگر را ندارید']);
                }

                LogService::log('license_deleted', $license, auth()->id());

                $license->delete();
            }
        });

        return back()->with('success', 'لایسنس  ها با موفقیت حذف شدند');
    }

    public function used($license_id)
    {
        $license = License::findOrFail($license_id);

        if (auth()->user()->cannot('change_others_data') and $license->user_id != auth()->id()) {
            return back()->withErrors(['شما دسترسی مشاهده مشتریان این لایسنس را ندارید']);
        }

        $used_licenses = UsedLicence::where(['license_id' => $license->id])->orderByDesc('id')->get();

        return view('pages.admin.license.used', compact('used_licenses', 'license'));
    }

    public function sendNotification(SendNotificationRequest $request)
    {
        $on = Carbon::parse($request->date)->setTime($request->hour, $request->minute, 0)->toDate();

        $this->getResultQuery($request)->chunk(100, function ($licenses) use ($request, $on) {
            foreach ($licenses as $license) {
                if (in_array('sms', $request->methods ?: [])) {
                    SMS::send($license->phone, $request->text, null, $on);
                }
                if (in_array('email', $request->methods ?: [])) {
                    Email::send($license->email, $request->text, $request->subject, $request->button_text, $request->button_link, $on);
                }
            }
        });

        return back()->with('success', 'نوتیفیکیشن ها ارسال شد');
    }
}
