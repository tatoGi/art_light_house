<?php

use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Website\FrontendController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\SearchController;
use App\Http\Controllers\Website\wishlistController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

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

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->group(function () {
        require __DIR__.'/admin/admin.php';
        require __DIR__.'/admin/products.php';
        require __DIR__.'/admin/page.php';
        require __DIR__.'/admin/settings.php';

    });
});

require __DIR__.'/website/auth.php';


Route::get('/', [DashboardController::class, 'index'])->middleware('auth');


Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/contact-submit', [FrontendController::class, 'submitContactForm'])->name('contact.submit');
Route::get('/sitemap', [SitemapController::class, 'generate']);
Route::get('/pro/{url}', [FrontendController::class, 'show'])->name('single_product');
Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');
Route::get('/home', [FrontendController::class, 'homePage']);
Route::get('/pages', [FrontendController::class, 'pages']);
// Keep catch-all route at the end


Route::get('/{slug}', [FrontendController::class, 'index'])->where('slug', '.*');


require __DIR__.'/website/basket.php';

Route::get('/clear-optimization', function () {

    Artisan::call('optimize:clear');

    // Display a message or redirect back
    return 'Optimization cache cleared!';
});
