<?php

namespace App\Http\Controllers;

use App\Product;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function calcShipping(ShippingService $shippingService, Request $request)
    {
        $shipping = $shippingService->setItem(Product::whereSlug($request->productId)->first())
            ->calculateShipping($request->zipcode);

        return response()->json([
            'data' => [
                'shipping' => $shipping,
            ],
        ]);
    }
}
