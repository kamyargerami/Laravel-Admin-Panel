<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddLicenceRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\License;
use App\Models\Product;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $licenses = License::with('user', 'product')->where(function ($query) use ($request) {

        })->withCount('used')->paginate();

        return view('pages.admin.license.index', compact('licenses'));
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
            $license = License::create([
                'type' => 'yearly',
                'max_use' => $request->max_use,
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'key' => str_random($request->character_length),
            ]);

            LogService::log('new_license', $license, auth()->id());
        }

        return back()->with('success', 'لایسنس های جدید با موفقیت اضافه شد');
    }

    public function edit(Product $product)
    {
        return view('pages.admin.product.edit', compact('product'));
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $product->update([
            'name' => $request->name
        ]);

        LogService::log('product_updated', $product, auth()->id(), ['new_name' => $request->name]);

        return back()->with('success', 'محصول با موفقیت ویرایش شد');
    }
}
