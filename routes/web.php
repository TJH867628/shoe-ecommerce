<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
/*
GET
*/
Route::get('/', function () {
    return view('main');
});
Route::get('register',[UserController::class, 'registerPage']) -> name('register');
Route::get('login',[UserController::class, 'loginPage']) -> name('login');
Route::get('/brands', [BrandController::class, 'getAllBrands']);
Route::get('/shoes', [ShoeController::class, 'getAllShoes']);
Route::get('/products/{shoeId}', [ShoeController::class, 'show'])->name('products.show');
Route::get('/shoes/admin/{shoeId}', [ShoeController::class, 'getAdminShoeDetails']);
Route::get('/shoes/search', [ShoeController::class, 'searchShoes']);
Route::get('/shoes/brand/{brandId}', [ShoeController::class, 'getShoesByBrand']);
Route::get('/shoes/{shoeId}/options', [ShoeController::class, 'getShoeOptions']);
Route::get('/shoes/{id}', [ShoeController::class, 'getShoeById']);    
Route::get('/test-builder', [ShoeController::class, 'testBuilder']);
Route::get('/test-sku-builder', [ShoeController::class, 'testSkuBuilder']);
Route::get(
    '/test-product/{shoeId}',
    [ShoeController::class, 'showAdminTestPage']
)->name('test-product');

/*
POST
*/
Route::post('/shoes', [ShoeController::class, 'createShoe'])->name('shoes.store');
Route::post('/shoes/options', [ShoeController::class, 'createShoeOptions'])->name('shoes.options.store');
Route::post('/shoes/skus', [ShoeController::class, 'createSkus'])->name('shoes.skus.store');
Route::post('/shoes/{shoeId}/images',[ShoeController::class, 'uploadShoeImages']);  
Route::post('/shoe-variations/{variationId}/images',[ShoeController::class, 'uploadVariationImages']);
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

/*
PUT
*/
Route::put('/shoes/variations/{variationId}', [ShoeController::class, 'updateSku'])->name('shoes.variations.update');
Route::put('/shoes/{shoeId}', [ShoeController::class, 'updateShoe'])->name('shoes.update');
Route::put('/shoes/options/{optionId}', [ShoeController::class, 'updateOption'])->name('shoes.options.update');

/*
DELETE
*/
Route::delete('/shoes/{shoeId}', [ShoeController::class, 'deleteShoe'])->name('shoes.destroy');
Route::delete('/shoes/options/{optionId}', [ShoeController::class, 'deleteShoeOption'])->name('shoes.options.destroy');
Route::delete('/shoes/variations/{variationId}', [ShoeController::class, 'deleteSku'])->name('shoes.variations.destroy');
Route::delete('/shoes/images/{imageId}', [ShoeController::class, 'removeShoeImage'])->name('shoes.images.destroy');
Route::delete('/shoe/variations/image/{imageId}', [ShoeController::class, 'removeVariationImage'])->name('shoe-variations.images.destroy');
/*
VIEW
*/
Route::view('/test-create-shoes', 'test-create-shoes')->name('test-create-shoes');
