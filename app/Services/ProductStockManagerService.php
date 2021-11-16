<?php

namespace App\Services;

use App\Product;
use App\UserOrder;

class ProductStockManagerService
{
    private $userOrder;

    public function __construct(UserOrder $userOrder)
    {
        $this->userOrder = $userOrder;
    }

    public function removeProductFromStock()
    {
        foreach ($this->userOrder->items as $item) {
            Product::find($item['id'])->decrement('in_stock', $item['number']);
        }
    }

    public function addingProductInStock()
    {
        foreach ($this->userOrder->items as $item) {
            Product::find($item['id'])->increment('in_stock', $item['number']);
        }
    }
}
