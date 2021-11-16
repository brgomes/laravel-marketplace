<?php

namespace App\Services;

use App\Product;
use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;

class ShippingService
{
    private $shippingService;
    private $zipCodeOrigin;

    public function __construct(Client $client)
    {
        $this->shippingService = $client->freight();
    }

    public function setItem(Product $product)
    {
        $this->shippingService->item(...$product->shippingOpts);
        $this->zipCodeOrigin = $product->store->zipcode;

        return $this;
    }

    public function calculateShipping(string $zipcode)
    {
        return $this->shippingService
            ->origin($this->zipCodeOrigin)
            ->destination($zipcode)
            ->services(Service::SEDEX, Service::PAC)
            ->calculate();
    }
}
