<?php

namespace App;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Slug;

    protected $fillable = ['name', 'description', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
