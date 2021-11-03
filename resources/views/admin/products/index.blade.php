@extends('layouts.app')

@section('content')
    <a href="{{ route('admin.products.create') }}" class="btn btn-success">Criar produto</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Loja</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>R$ {{ number_format($p->price, 2, ',', '.') }}</td>
                    <td>{{ $p->store->name }}</td>
                    <td>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-primary">Editar</a>
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
@endsection
