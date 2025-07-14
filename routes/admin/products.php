<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductOptionController;
use Illuminate\Support\Facades\Route;

Route::resource('/products', ProductController::class);
Route::get('/products/option/{product_id}', [ProductOptionController::class, 'index'])->name('product.option.index');
// Route for displaying the form to create a new product option
Route::get('/products/{product_id}/options/create', [ProductOptionController::class, 'create'])->name('product.option.create');

// Route for storing a new product option
Route::post('/products/{product_id}/options', [ProductOptionController::class, 'store'])->name('product.option.store');

// Route for displaying the form to edit an existing product option
Route::get('/products/{product_id}/options/{option_id}/edit', [ProductOptionController::class, 'edit'])->name('product.option.edit');

// Route for updating an existing product option (alternative using patch method)
Route::patch('/products/{product_id}/options/{option_id}', [ProductOptionController::class, 'update'])->name('product.option.update');

// Route for deleting a product option
Route::delete('/products/{product_id}/options/{option_id}', [ProductOptionController::class, 'destroy'])->name('product.option.destroy');

Route::delete('/products/delete/image/{image_id}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
Route::get('/products/cleanup-images', [ProductController::class, 'cleanupMissingImages'])->name('products.cleanup-images');
