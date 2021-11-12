@extends('layouts.app')

@section('content')
    <h1>Editar loja</h1>

    <form method="post" action="{{ route('admin.stores.update', $store) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nome loja</label>
            <input type="text" name="name" class="form-control" value="{{ $store->name }}">
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="description" class="form-control" value="{{ $store->description }}">
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $store->phone }}">
        </div>

        <div class="form-group">
            <label>Celular / WhatsApp</label>
            <input type="text" name="mobile_phone" id="mobile_phone" class="form-control" value="{{ $store->mobile_phone }}">
        </div>

        <div class="form-group">
            <p>
                <img src="{{ asset('storage/' . $store->logo) }}" alt="" class="img-fluid" />
            </p>
        </div>

        <div class="form-group">
            <label>Logomarca</label>
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">

            @error('logo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        let imPhone = new Inputmask('(99) 9999-9999');
        imPhone.mask(document.getElementById('phone'));

        let imMobilePhone = new Inputmask('(99) 99999-9999');
        imMobilePhone.mask(document.getElementById('mobile_phone'));
    </script>
@endsection
