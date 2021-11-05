<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        $store = auth()->user()->store;
        $products = $store->products()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $store = auth()->user()->store;
        $product = $store->products()->create($data);

        $product->categories()->sync($data['categories']);

        if ($request->hasFile('photos')) {
            $images = $this->imageUpload($request, 'image');

            $product->photos()->createMany($images);
        }

        flash('Produto criado com sucesso.')->success();

        return redirect()->route('admin.products.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = Category::all(['id', 'name']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->all();
        $product->update($data);

        $product->categories()->sync($data['categories']);

        if ($request->hasFile('photos')) {
            $images = $this->imageUpload($request, 'image');

            $product->photos()->createMany($images);
        }

        flash('Produto atualizado com sucesso.')->success();

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        flash('Produto removido com sucesso.')->success();

        return redirect()->route('admin.products.index');
    }

    private function imageUpload(Request $request, $imageColumn)
    {
        $images = $request->file('photos');

        $uploadedImages = [];

        foreach ($images as $image) {
            $uploadedImages[] = [$imageColumn => $image->store('products', 'public')];
        }

        return $uploadedImages;
    }
}
