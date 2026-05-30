<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShoePrototypeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

use App\Models\Shoe;
/*
GET
*/

Route::get('/', function () {
    $trendingShoes = \App\Models\Shoe::with(['brand', 'images', 'variations'])
        ->latest()
        ->take(4)
        ->get();
    return view('main', compact('trendingShoes'));
});
Route::get('register', [UserController::class, 'registerPage'])->name('register');
Route::get('login', [UserController::class, 'loginPage'])->name('login');
Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
Route::get('/user/product', [ShoeController::class, 'index'])->name('product');
Route::get('/user/wishlist', [ShoeController::class, 'wishlist'])->name('wishlist');
Route::get('/wishlist/items', [ShoeController::class, 'getWishlistItems'])->name('wishlist.items');
Route::get('/user/cart', [CartController::class, 'show'])->name('cart.index');
Route::get('/brands', [BrandController::class, 'getAllBrands']);
Route::get('/shoes', [ShoeController::class, 'getAllShoes']);
Route::get('/user/products/{shoeId}', [ShoeController::class, 'show'])->name('products.show');
Route::get('/shoes/admin/{shoeId}', [ShoeController::class, 'getAdminShoeDetails']);
Route::get('/shoes/search', [ShoeController::class, 'searchShoes']);
Route::get('/shoes/brand/{brandId}', [ShoeController::class, 'getShoesByBrand']);
Route::get('/shoes/{shoeId}/options', [ShoeController::class, 'getShoeOptions']);
Route::get('/shoes/{id}', [ShoeController::class, 'getShoeById']);
Route::get('/test-product/{shoeId}',[ShoeController::class, 'showAdminTestPage'])->name('test-product');
Route::get('/prototype-test/{shoe}', function (\App\Models\Shoe $shoe) {
    return view('prototype-test', compact('shoe'));
});
Route::get('/test-manage-shoes', function () {
    $shoes = Shoe::with([
        'brand',
        'options',
        'variations'
    ])->get();

    return view(
        'test-manage-shoes',
        compact('shoes')
    );
});
Route::get('/payment/toyyibpay/return', function (Request $request) {
    $statusId = (string) $request->query('status_id');

    $message = match ($statusId) {
        '1' => 'ToyyibPay payment completed successfully.',
        '2' => 'ToyyibPay payment is still pending.',
        '3' => 'ToyyibPay payment failed.',
        default => 'Returned from ToyyibPay without a payment status.',
    };

    $flashKey = $statusId === '3' ? 'failed' : 'success';

    return redirect()
        ->route('test-payment')
        ->with($flashKey, $message)
        ->with('checkout_result', [
            'status_id' => $statusId,
            'billcode' => $request->query('billcode'),
            'order_id' => $request->query('order_id'),
        ]);
})->name('toyyibpay.return');

/*
POST
*/
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::post('/shoes', [ShoeController::class, 'createShoe'])->name('shoes.store');
Route::post('/shoes/options', [ShoeController::class, 'createShoeOptions'])->name('shoes.options.store');
Route::post('/shoes/skus', [ShoeController::class, 'createSkus'])->name('shoes.skus.store');
Route::post('/shoes/{shoeId}/images', [ShoeController::class, 'uploadShoeImages']);
Route::post('/shoe-variations/{variationId}/images', [ShoeController::class, 'uploadVariationImages']);
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{item}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('shoes/{shoeId}/clone', [ShoePrototypeController::class, 'clone'])->name('shoes.clone');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('/payment/toyyibpay/callback', function () {
    return response()->json(['status' => 'ok']);
})->name('toyyibpay.callback');
Route::post('/wishlist/add/{shoeId}', [ShoeController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove/{shoeId}', [ShoeController::class, 'removeFromWishlist'])->name('wishlist.remove');

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
Route::delete('/cart/{item}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
/*
VIEW
*/
Route::view('/about', 'user.about')->name('about');
Route::view('/test-create-shoes', 'test-create-shoes')->name('test-create-shoes');
Route::view('/test-payment', 'test-payment')->name('test-payment');
