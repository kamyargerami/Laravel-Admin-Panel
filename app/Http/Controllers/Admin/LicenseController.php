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
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $order_by = Helper::orderBy($request->order_by);

        $licenses = License::with('user', 'product')->where(function ($query) use ($request) {
            foreach (['id', 'user_id', 'product_id', 'key'] as $column) {
                if ($request->get($column)) {
                    $query->where($column, $request->get($column));
                }
            }
        })->withCount('used')->orderBy($order_by[0], $order_by[1])->paginate();

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
        $license->update($request->validated());

        LogService::log('license_updated', $license, auth()->id(), $request->validated());

        return back()->with('success', 'محصول با موفقیت ویرایش شد');
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
}
