<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('title')->nullable();
            $table->string('handle')->nullable();
            $table->string('shopify_updated_at')->nullable();
            $table->string('sort_order')->nullable();
            $table->text('description')->nullable();
            $table->text('description_html')->nullable();
            $table->string('template_suffix')->nullable();
            $table->integer('products_count')->default(0);
            $table->boolean('applied_disjunctively');
            $table->string('rules_column')->nullable();
            $table->string('rules_relation')->nullable();
            $table->string('rules_condition')->nullable();
            $table->timestamps();
        });
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_collection_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('rules_column')->nullable();
            $table->string('rules_relation')->nullable();
            $table->string('rules_condition')->nullable();
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
        Schema::dropIfExists('collections');
    }
}
