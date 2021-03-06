@extends('layouts.app')

@section('content')
    <h1>Atualizar produto</h1>

    <form method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nome produto</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}">
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="description" class="form-control" value="{{ $product->description }}">
        </div>

        <div class="form-group">
            <label>Conteúdo</label>
            <textarea name="body" cols="30" rows="10" class="form-control">{{ $product->body }}</textarea>
        </div>

        <div class="form-group">
            <label>Preço</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ $product->price }}">
        </div>

        <div class="form-group">
            <label>Categoria</label>
            <select name="categories[]" id="" class="form-control" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if ($product->categories->contains($category)) selected @endif>{{ $category->id . ' - ' . $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Fotos do produto</label>
            <input type="file" name="photos[]" class="form-control @error('photos') is-invalid @enderror" multiple>

            @error('photos')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>

    <hr>

    <div class="row">
        @foreach ($product->photos as $photo)
            <div class="col-4 text-center">
                <img src="{{ asset('storage/' . $photo->image) }}" alt="" class="img-fluid">
                <form action="{{ route('admin.photo.remove', $photo) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">Remover</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/plentz/jquery-maskmoney@master/dist/jquery.maskMoney.min.js"></script>
    <script>
        $('#price').maskMoney({
            prefix: '',
            allowNegative: false,
            thousands: '.',
            decimal: ','
        });
    </script>
@endsection
