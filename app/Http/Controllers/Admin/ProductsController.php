<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Store;
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
        $products = $this->product->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $stores = Store::all(['id', 'name']);

        return view('admin.products.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $store = Store::find($data['store']);
        $store->products()->create($data);

        flash('Produto criado com sucesso.')->success();

        return redirect()->route('admin.products.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $product->update($data);

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
