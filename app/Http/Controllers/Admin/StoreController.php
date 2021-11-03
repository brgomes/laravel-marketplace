<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Store;
use App\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::paginate(10);

        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        $users = User::all(['id', 'name']);

        return view('admin.stores.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = User::find($data['user']);
        $user->store()->create($data);

        flash('Loja criada com sucesso.')->success();

        return redirect()->route('admin.stores.index');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $data = $request->all();
        $store->update($data);

        flash('Loja atualizada com sucesso.')->success();

        return redirect()->route('admin.stores.index');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        flash('Loja removida com sucesso.')->success();

        return redirect()->route('admin.stores.index');
    }
}
