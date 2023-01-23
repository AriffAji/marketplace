<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DashboardProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['galleries', 'category'])->where('users_id', Auth::user()->id)->get();
        $carts = Cart::where('users_id', Auth::user()->id)->count();
        return view('pages.dashboard-products', [
            'products' => $products,
            'carts' => $carts,
        ]);
    }
    public function details(Request $request, $id)
    {
        $product = Product::with(['galleries', 'user', 'category']);
        $categories = Category::all();

        return view('pages.dashboard-products-details', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function uploadGallery(Request $request)
    {
        $data = $request->all();

        $data['photos'] = $request->file('photos')->store('asset/product', 'public');

        ProductGallery::create($data);

        return redirect()->route('dashboard-product-details', $request->products_id);
    }

    public function deleteGallery(Request $request, $id)
    {
        //
        $item = ProductGallery::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard-product-details', $item->products_id);
    }
    public function create()
    {
        $categories = Category::all();
        return view('pages.dashboard-products-create', ['categories' => $categories,]);
    }
    public function store(ProductRequest $request)
    {
        //
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $product = Product::create($data);
        $gallery = [
            'products_id' => $product->id,
            'photos' => $request->file('photo')->store('asset/product', 'public'),
        ];
        ProductGallery::create($gallery);
        return redirect()->route('product.index');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $item = Product::findOrFail($id);
        $data['slug'] = Str::slug($request->name);


        $item->update($data);

        return redirect()->route('dashboard-product');
    }
}