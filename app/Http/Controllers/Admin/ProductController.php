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
        $product = Product::create([
            'name' => $request->name
        ]);

        LogService::log('new_product', $product, auth()->id(), ['name' => $request->name]);

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

    public function delete(Product $product)
    {
        $count_used_licences = $product->licenses()->count();

        if ($count_used_licences) {
            return back()->withErrors(['این محسول لایسنس دارد و شما مجاز به حذف آن نیستید، ابتدا رکورد مشتریان این لایسنس را پاک کنید']);
        }

        LogService::log('product_deleted', $product, auth()->id());

        $product->delete();

        return back()->with('success', 'محصول با موفقیت حذف شد');
    }
}
