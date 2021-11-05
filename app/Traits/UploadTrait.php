<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait UploadTrait
{
    private function imageUpload($images, $imageColumn = null)
    {
        if (is_array($images)) {
            $uploadedImages = [];

            foreach ($images as $image) {
                $uploadedImages[] = [$imageColumn => $image->store('products', 'public')];
            }
        } else {
            $uploadedImages = $images->store('logo', 'public');
        }

        return $uploadedImages;
    }
}
