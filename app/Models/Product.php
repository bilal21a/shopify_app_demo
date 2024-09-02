<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'shopify_id',
        'title',
        'status',
        'tags',
        'vendor',
        'created_at',
        'has_only_default_variant',
        'description',
        'featured_image',
        'product_type',
        'has_out_of_stock_variants',
        'tracks_inventory',
        'total_inventory',
        'handle'
    ];
    public function images()
    {
        return $this->hasMany(Image::class, 'shopify_product_id', 'shopify_id');
    }
    public function variants()
    {
        return $this->hasMany(Variant::class, 'shopify_product_id', 'shopify_id');
    }
}
