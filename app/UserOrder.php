<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    protected $fillable = ['reference', 'pagseguro_code', 'pagseguro_status', 'items', 'store_id', 'type', 'link_boleto'];

    public function getItemsAttribute()
    {
        return unserialize($this->attributes['items']);
    }

    public function setItemsAttribute($prop)
    {
        $this->attributes['items'] = serialize($prop);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'order_store', 'order_id');
    }
}
