<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\ShoePrototypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [ShoeController::class, 'home']);
Route::view('/about', 'user.about')->name('about');

Route::get('/user/product', [ShoeController::class, 'index'])->name('product');
Route::get('/user/products/{shoeId}', [ShoeController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/register', [UserController::class, 'registerPage'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/login', [UserController::class, 'loginPage'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Account, Cart, and Payment
|--------------------------------------------------------------------------
*/

Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/user/cart', [CartController::class, 'show'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{item}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::delete('/cart/{item}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');

Route::get('/user/payment', [PaymentController::class, 'paymentPage'])->middleware('auth')->name('user.payment');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment/toyyibpay/return', [PaymentController::class, 'toyyibpayReturn'])->name('toyyibpay.return');
Route::get('/payment/toyyibpay/callback', [PaymentController::class, 'toyyibpayCallback'])->name('toyyibpay.callback');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('user.payment-success');

/*
|--------------------------------------------------------------------------
| Catalog API Routes
|--------------------------------------------------------------------------
*/

Route::get('/brands', [BrandController::class, 'getAllBrands']);
Route::get('/shoes', [ShoeController::class, 'getAllShoes']);
Route::get('/shoes/search', [ShoeController::class, 'searchShoes']);
Route::get('/shoes/admin/{shoeId}', [ShoeController::class, 'getAdminShoeDetails']);
Route::get('/shoes/brand/{brandId}', [ShoeController::class, 'getShoesByBrand']);
Route::get('/shoes/{shoeId}/options', [ShoeController::class, 'getShoeOptions']);
Route::get('/shoes/{id}', [ShoeController::class, 'getShoeById']);

/*
|--------------------------------------------------------------------------
| Shoe Management
|--------------------------------------------------------------------------
*/

Route::post('/shoes', [ShoeController::class, 'createShoe'])->name('shoes.store');
Route::put('/shoes/{shoeId}', [ShoeController::class, 'updateShoe'])->name('shoes.update');
Route::delete('/shoes/{shoeId}', [ShoeController::class, 'deleteShoe'])->name('shoes.destroy');

Route::post('/shoes/options', [ShoeController::class, 'createShoeOptions'])->name('shoes.options.store');
Route::put('/shoes/options/{optionId}', [ShoeController::class, 'updateOption'])->name('shoes.options.update');
Route::delete('/shoes/options/{optionId}', [ShoeController::class, 'deleteShoeOption'])->name('shoes.options.destroy');

Route::post('/shoes/skus', [ShoeController::class, 'createSkus'])->name('shoes.skus.store');
Route::put('/shoes/variations/{variationId}', [ShoeController::class, 'updateSku'])->name('shoes.variations.update');
Route::delete('/shoes/variations/{variationId}', [ShoeController::class, 'deleteSku'])->name('shoes.variations.destroy');

Route::post('/shoes/{shoeId}/images', [ShoeController::class, 'uploadShoeImages']);
Route::delete('/shoes/images/{imageId}', [ShoeController::class, 'removeShoeImage'])->name('shoes.images.destroy');
Route::post('/shoe-variations/{variationId}/images', [ShoeController::class, 'uploadVariationImages']);
Route::delete('/shoe/variations/image/{imageId}', [ShoeController::class, 'removeVariationImage'])->name('shoe-variations.images.destroy');

Route::post('/shoes/{shoeId}/clone', [ShoePrototypeController::class, 'clone'])->name('shoes.clone');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');

Route::get('/admin/shoes', [ShoeController::class, 'adminIndex'])->middleware(['auth', 'admin'])->name('admin.shoes.index');
Route::get('/admin/shoes/{shoeId}', [ShoeController::class, 'adminShow'])->middleware(['auth', 'admin'])->name('admin.shoes.show');
Route::post('/admin/shoes', [ShoeController::class, 'createShoe'])->middleware(['auth', 'admin'])->name('admin.shoes.store');
Route::put('/admin/shoes/{shoeId}', [ShoeController::class, 'updateShoe'])->middleware(['auth', 'admin'])->name('admin.shoes.update');
Route::delete('/admin/shoes/{shoeId}', [ShoeController::class, 'deleteShoe'])->middleware(['auth', 'admin'])->name('admin.shoes.destroy');

Route::post('/admin/shoes/options', [ShoeController::class, 'createShoeOptions'])->middleware(['auth', 'admin'])->name('admin.shoes.options.store');
Route::put('/admin/shoes/options/{optionId}', [ShoeController::class, 'updateOption'])->middleware(['auth', 'admin'])->name('admin.shoes.options.update');
Route::delete('/admin/shoes/options/{optionId}', [ShoeController::class, 'deleteShoeOption'])->middleware(['auth', 'admin'])->name('admin.shoes.options.destroy');
Route::put('/admin/shoes/variations/{variationId}', [ShoeController::class, 'updateSku'])->middleware(['auth', 'admin'])->name('admin.shoes.variations.update');
Route::delete('/admin/shoes/variations/{variationId}', [ShoeController::class, 'deleteSku'])->middleware(['auth', 'admin'])->name('admin.shoes.variations.destroy');

Route::get('/admin/brands', [BrandController::class, 'adminIndex'])->middleware(['auth', 'admin'])->name('admin.brands.index');
Route::post('/admin/brands', [BrandController::class, 'createBrand'])->middleware(['auth', 'admin'])->name('admin.brands.store');
Route::put('/admin/brands/{brandId}', [BrandController::class, 'updateBrand'])->middleware(['auth', 'admin'])->name('admin.brands.update');
Route::delete('/admin/brands/{brandId}', [BrandController::class, 'deleteBrand'])->middleware(['auth', 'admin'])->name('admin.brands.destroy');

Route::get('/admin/orders', [AdminController::class, 'orders'])->middleware(['auth', 'admin'])->name('admin.orders.index');
Route::put('/admin/orders/{orderId}', [AdminController::class, 'updateOrder'])->middleware(['auth', 'admin'])->name('admin.orders.update');
Route::delete('/admin/orders/{orderId}', [AdminController::class, 'deleteOrder'])->middleware(['auth', 'admin'])->name('admin.orders.destroy');

Route::get('/admin/users', [AdminController::class, 'users'])->middleware(['auth', 'admin'])->name('admin.users.index');
Route::put('/admin/users/{userId}', [AdminController::class, 'updateUser'])->middleware(['auth', 'admin'])->name('admin.users.update');
Route::delete('/admin/users/{userId}', [AdminController::class, 'deleteUser'])->middleware(['auth', 'admin'])->name('admin.users.destroy');
