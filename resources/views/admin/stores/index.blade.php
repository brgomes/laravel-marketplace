@extends('layouts.app')

@section('content')
    @if (!$store)
        <a href="{{ route('admin.stores.create') }}" class="btn btn-success">Criar loja</a>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Loja</th>
                    <th>Total de produtos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $store->id }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->products()->count() }}</td>
                    <td>
                        <form action="{{ route('admin.stores.destroy', $store) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-primary">Editar</a>
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
@endsection
