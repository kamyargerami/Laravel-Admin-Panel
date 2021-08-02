<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddLicenceRequest;
use App\Http\Requests\Admin\MultiUpdateRequest;
use App\Http\Requests\Admin\UpdateLicenceRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\UsedLicence;
use App\Models\User;
use App\Services\Helper;
use App\Services\LicenseService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LicenseController extends Controller
{
    public function getResultQuery(Request $request)
    {
        return License::with('user', 'product')->where(function ($query) use ($request) {
            foreach (['id', 'user_id', 'product_id', 'key'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }

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
        for ($i = 0; $i < $request->quantity; $i++) {
            $license = LicenseService::create($request->type, $request->max_use, $request->user_id, $request->status, $request->product_id, $request->character_length);
            LogService::log('new_license', $license, auth()->id());
        }

        return back()->with('success', 'لایسنس های جدید با موفقیت اضافه شد');
    }

    public function edit(License $license)
    {
        $products = Product::all();
        $users = User::all();

        return view('pages.admin.license.edit', compact('license', 'products', 'users'));
    }

    public function update(License $license, UpdateLicenceRequest $request)
    {
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

    public function used($license_id)
    {
        $used_licenses = UsedLicence::where(['license_id' => $license_id])->orderByDesc('id')->paginate();

        return view('pages.admin.license.used', compact('used_licenses', 'license_id'));
    }
}
