<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddLicenceRequest;
use App\Http\Requests\Admin\UpdateLicenceRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\User;
use App\Services\Helper;
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
        });
    }

    public function index(Request $request)
    {
        $order_by = Helper::orderBy($request->order_by);

        $licenses = $this->getResultQuery($request)->withCount('used')->orderBy($order_by[0], $order_by[1])->paginate();
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
            $license = License::firstOrCreate([
                'product_id' => $request->product_id,
                'key' => $this->randomString($request->character_length)
            ], [
                'type' => 'yearly',
                'max_use' => $request->max_use,
                'user_id' => $request->user_id,
                'status' => $request->status
            ]);

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

    function randomString($length = 25)
    {
        $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
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
}
