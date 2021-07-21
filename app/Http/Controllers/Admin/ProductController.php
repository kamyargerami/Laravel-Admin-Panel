<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\LogService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate();

        return view('pages.admin.product.index', compact('products'));
    }

    public function add()
    {
        return view('pages.admin.product.add');
    }

    public function store(AddProductRequest $request)
    {
        Product::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'محصول جدید با موفقیت اضافه شد');
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
