<?php

use App\Http\Controllers\Product\CollectionController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\WebhookTestController;
use App\Jobs\ProductsCreateJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
// Route::middleware(['auth.shopify'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::get('/product-featch', [ProductController::class, 'featchData'])->name('product_featch');
    Route::get('/product-add', [ProductController::class, 'create'])->name('product_add');
    Route::post('/product-store', [ProductController::class, 'store'])->name('product_store');
    Route::get('/product-delete/{id}', [ProductController::class, 'delete'])->name('product_delete');
    Route::post('/product-update/{id}', [ProductController::class, 'update'])->name('product_update');


    Route::get('/collection-featch', [CollectionController::class, 'featchData'])->name('collection_featch');
    Route::get('/collection-add', [CollectionController::class, 'create'])->name('collection_add');
    Route::post('/collection-store', [CollectionController::class, 'store'])->name('collection_store');
    Route::post('/collection-update/{id}', [CollectionController::class, 'update'])->name('collection_update');
    Route::post('/collection-products', [CollectionController::class, 'addProductCollection'])->name('collection_products');
// });


