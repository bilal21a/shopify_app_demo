<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    public function rules()
    {
        return $this->hasMany(Rule::class, 'shopify_collection_id', 'shopify_id');
    }
    public function images()
    {
        return $this->hasOne(Image::class, 'shopify_collection_id', 'shopify_id');
    }
}
