<?php

namespace App\Traits;

use App\Models\Image;
use App\Models\LeaveType;
use App\Models\Product;
use App\Models\Variant;

trait ProductSaveTrait
{
    public function SaveData($productData)
    {
        $productNode = $productData['node'];
        $user_id =1;
        $id =  $productNode['id'];
        $numericId = substr($id, strrpos($id, '/') + 1);
        $product_check = Product::with('images')->where('shopify_id', $numericId)->first();
        if ($product_check) {
            $product = $product_check;
        } else {
            $product = new Product();
        }
        $product->shopify_id = $numericId;
        $product->user_id = $user_id;
        $product->title = $productNode['title'];
        $product->status = $productNode['status'];
        $product->tags = json_encode($productNode['tags']);
        $product->vendor = $productNode['vendor'];
        $product->shopify_created_at = $productNode['createdAt'];
        $product->has_only_default_variant = $productNode['hasOnlyDefaultVariant'];
        $product->description = $productNode['description'];
        $product->featured_image = $productNode['featuredImage'] != null ?  $productNode['featuredImage']['url'] : null ;
        $product->product_type = $productNode['productType'];
        $product->has_out_of_stock_variants = $productNode['hasOutOfStockVariants'];
        $product->tracks_inventory =  $productNode['tracksInventory'];
        $product->total_inventory =  $productNode['totalInventory'];
        $product->handle = $productNode['handle'];
        $product->save();
        // Image Save
        $images =  $productNode['images']['edges'];
        foreach ($images as $key => $image) {
            $id_img = $image['node']['id'];
            $numericIdimg = substr($id_img, strrpos($id_img, '/') + 1);
            $image_save = Image::where('shopify_id', $numericIdimg)->first();
            if (!$image_save) {
                $image_save = new Image();
                // dd("numericIdimg");
            }else{
                $image_save = $image_save;
            }
            $image_save->user_id = $user_id;
            $image_save->shopify_id = $numericIdimg;
            $image_save->product_id = $product->id;
            $image_save->shopify_product_id = $numericId;
            $image_save->alt = $image['node']['altText'];
            $image_save->height = $image['node']['height'];
            $image_save->width = $image['node']['width'];
            $image_save->src = $image['node']['url'];
            $image_save->originalSrc = $image['node']['originalSrc'];
            $image_save->save();
        }
        // Variants Save
        $variants =  $productNode['variants']['edges'];
        foreach ($variants as $key => $variant) {
            $variantId = $variant['node']['id'];
            $numericVariantId = substr($variantId, strrpos($variantId, '/') + 1);
            $variant_save = Variant::where('shopify_id', $numericVariantId)->first();
            if (!$variant_save) {
                $variant_save = new Variant();
            }else{
                $variant_save = $variant_save;
            }
            $variant_save->user_id = $user_id;
            $variant_save->shopify_id = $numericVariantId;
            $variant_save->product_id = $product->id;
            $variant_save->shopify_product_id = $numericId;
            $variant_save->title = $variant['node']['title'];
            $variant_save->sku = $variant['node']['sku'];
            $variant_save->price = $variant['node']['price'];
            $variant_save->inventoryQuantity = $variant['node']['inventoryQuantity'];
            $variant_save->save();
        }
    }
}
