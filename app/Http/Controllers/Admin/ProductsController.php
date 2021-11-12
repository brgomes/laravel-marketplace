<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Product;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use UploadTrait;

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        $user = auth()->user();

        if (!$user->store()->exists()) {
            flash('É preciso criar uma loja para cadastrar produtos.')->warning();
            return redirect()->route('admin.stores.index');
        }

        $products = $user->store->products()->paginate(10);

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
        $categories = $request->get('categories', null);
        $store = auth()->user()->store;
        $data['price'] = formatPriceToDatabase($data['price']);

        $product = $store->products()->create($data);

        $product->categories()->sync($categories);

        if ($request->hasFile('photos')) {
            $images = $this->imageUpload($request->file('photos'), 'image');

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
        $categories = $request->get('categories', null);
        $product->update($data);

        if (isset($categories)) {
            $product->categories()->sync($categories);
        }

        if ($request->hasFile('photos')) {
            $images = $this->imageUpload($request->file('photos'), 'image');

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
}
