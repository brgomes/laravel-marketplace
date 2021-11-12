@extends('layouts.front')

@section('content')
    <h2 class="alert alert-success">
        Muito obrigado por sua compra!
    </h2>
    <h3>
        Seu pedido foi processado.
        Código do pedido: {{ request()->get('order') }}.

        @if (request()->has('b'))
            <br><br>
            <a href="{{ request()->get('b') }}" class="btn btn-success" target="_blank">Imprimir boleto</a>
        @endif
    </h3>
@endsection
