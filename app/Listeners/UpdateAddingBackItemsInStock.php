<?php

namespace App\Listeners;

use App\Events\UserCancelledOrder;
use App\Services\ProductStockManagerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateAddingBackItemsInStock
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCancelledOrder  $event
     * @return void
     */
    public function handle(UserCancelledOrder $event)
    {
        (new ProductStockManagerService($event->userOrder))->addingProductInStock();
    }
}
