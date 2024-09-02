<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('shopify_id')->nullable();
            $table->string('title');
            $table->string('status');
            $table->longText('tags')->nullable();
            $table->string('vendor');
            $table->string('shopify_created_at');
            $table->boolean('has_only_default_variant');
            $table->text('description')->nullable();
            $table->text('featured_image')->nullable();
            $table->longText('options')->nullable();
            $table->string('product_type')->nullable();
            $table->boolean('has_out_of_stock_variants');
            $table->boolean('tracks_inventory');
            $table->integer('total_inventory');
            $table->string('handle')->nullable();
            $table->timestamps();
        });
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('shopify_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->longText('alt')->nullable();
            $table->bigInteger('shopify_product_id')->nullable();
            $table->bigInteger('shopify_collection_id')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->longText('src')->nullable();
            $table->longText('originalSrc')->nullable();
            $table->timestamps();
        });
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('shopify_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('shopify_product_id')->nullable();
            $table->string('title')->nullable();
            $table->string('sku')->nullable();
            $table->Integer('price')->nullable();
            $table->string('width')->nullable();
            $table->string('weightUnit')->nullable();
            $table->bigInteger('inventoryQuantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('images');
    }
}
