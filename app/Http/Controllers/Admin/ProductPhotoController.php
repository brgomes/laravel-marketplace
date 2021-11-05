<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductPhotoController extends Controller
{
    public function removePhoto(ProductPhoto $photo)
    {
        $disk = Storage::disk('public');

        if ($disk->exists($photo->image)) {
            $disk->delete($photo->image);
        }

        $photo->delete();

        flash('Imagem removida com sucesso.')->success();
        return redirect()->route('admin.products.edit', $photo->product);
    }
}
