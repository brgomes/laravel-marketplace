<?php

namespace App;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use Slug;

    protected $fillable = ['name', 'description', 'body', 'price', 'slug'];

    public function getThumbAttribute()
    {
        return $this->photos->first()->image;
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function getShippingOptsAttribute()
    {
        return [16, 16, 16, .2, 1]; // largura, algura, comprimento, peso e quantidade
    }

    /*public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }*/
}
