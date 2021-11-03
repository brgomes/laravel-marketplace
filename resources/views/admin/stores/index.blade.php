@extends('layouts.app')

@section('content')
    <a href="{{ route('admin.stores.create') }}" class="btn btn-success">Criar loja</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Loja</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stores as $store)
                <tr>
                    <td>{{ $store->id }}</td>
                    <td>{{ $store->name }}</td>
                    <td>
                        <form action="{{ route('admin.stores.destroy', $store) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-primary">Editar</a>
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $stores->links() }}
@endsection
