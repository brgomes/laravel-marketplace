<?php

namespace App;

use App\Notifications\StoreReceiveNewOrder;
use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use Slug;

    protected $fillable = ['name', 'description', 'phone', 'mobile_phone', 'slug', 'logo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->belongsToMany(UserOrder::class, 'order_store', 'store_id', 'order_id');
    }

    public function notifyStoreOwners($storesId)
    {
        $stores = $this->whereIn('id', $storesId)->get();

        // O map percorre as lojas e retorna o dono (user) de cada loja em um novo array
        return $stores->map(function ($store) {
            return $store->user;
        })->each->notify(new StoreReceiveNewOrder());
    }

    public function getZipcodeAttribute()
    {
        return '01001-000';
    }
}
