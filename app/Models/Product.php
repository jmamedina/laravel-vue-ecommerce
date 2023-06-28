<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['title', 'descriptions', 'price', 'image', 'published', 'image_mime', 'image_size', 'created_by', 'updated_by'];

    public function getSlufOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsForm('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
