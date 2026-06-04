<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShoePrototypeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Shoe;
use App\Models\User;
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
Route::get('/user/cart', [CartController::class, 'show'])->name('cart.index');
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function (Request $request) {
            $adminUser = $request->user();

            $stats = [
                ['label' => 'Total Users', 'value' => User::count(), 'note' => 'Registered accounts'],
                ['label' => 'Admin Accounts', 'value' => User::where('role', 'admin')->count(), 'note' => 'Privileged access'],
                ['label' => 'Brands', 'value' => Brand::count(), 'note' => 'Active catalog brands'],
                ['label' => 'Shoes', 'value' => Shoe::count(), 'note' => 'Products in catalog'],
                ['label' => 'Orders', 'value' => Order::count(), 'note' => 'Placed purchases'],
            ];

            $recentShoes = Shoe::with('brand')
                ->latest()
                ->take(5)
                ->get();

            $recentUsers = User::latest()
                ->take(5)
                ->get();

            $recentOrders = Order::with('user')
                ->latest()
                ->take(8)
                ->get();

            return view('admin.dashboard', compact('adminUser', 'stats', 'recentShoes', 'recentUsers', 'recentOrders'));
        })->name('dashboard');

        Route::get('/shoes', function () {
            $shoes = Shoe::with([
                'brand',
                'options',
                'variations',
                'images',
            ])->latest()->paginate(15);

            $brands = Brand::orderBy('brand_name')->get();

            return view('admin.manage-shoes', compact('shoes', 'brands'));
        })->name('shoes.index');

        Route::get('/shoes/{shoeId}', function (int $shoeId) {
            $shoe = Shoe::with([
                'brand',
                'options',
                'images',
                'variations',
                'variations.images',
            ])->findOrFail($shoeId);
            $brands = Brand::orderBy('brand_name')->get();

            return view('admin.product', compact('shoe', 'brands'));
        })->name('shoes.show');

        Route::get('/brands', function () {
            $brands = Brand::withCount('shoes')
                ->orderBy('brand_name')
                ->paginate(20);

            return view('admin.brands', compact('brands'));
        })->name('brands.index');

        Route::post('/brands', [BrandController::class, 'createBrand'])->name('brands.store');
        Route::put('/brands/{brandId}', [BrandController::class, 'updateBrand'])->name('brands.update');
        Route::delete('/brands/{brandId}', [BrandController::class, 'deleteBrand'])->name('brands.destroy');

        Route::get('/orders', function () {
            $orders = Order::with(['user', 'items.variation.shoe.brand'])
                ->latest()
                ->paginate(15);

            // Compute status summary with a DB query so it's correct even when paginated
            $statusSummary = Order::select('status', DB::raw('count(*) as cnt'))
                ->groupBy('status')
                ->pluck('cnt', 'status')
                ->toArray();

            // Ensure keys exist for expected statuses
            $expected = ['pending', 'paid', 'shipping', 'delivered', 'cancelled'];
            $statusSummary = array_merge(array_fill_keys($expected, 0), $statusSummary);

            return view('admin.orders', compact('orders', 'statusSummary'));
        })->name('orders.index');

        Route::put('/orders/{orderId}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::delete('/orders/{orderId}', [AdminController::class, 'deleteOrder'])->name('orders.destroy');

        Route::get('/users', function () {
            $users = User::latest()->paginate(20);

            $roleSummary = User::select('role', DB::raw('count(*) as cnt'))
                ->groupBy('role')
                ->pluck('cnt', 'role')
                ->toArray();

            $roleSummary = array_merge(['admin' => 0, 'customer' => 0], $roleSummary);

            return view('admin.users', compact('users', 'roleSummary'));
        })->name('users.index');

        Route::put('/users/{userId}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{userId}', [AdminController::class, 'deleteUser'])->name('users.destroy');

        Route::post('/shoes', [ShoeController::class, 'createShoe'])->name('shoes.store');
        Route::put('/shoes/{shoeId}', [ShoeController::class, 'updateShoe'])->name('shoes.update');
        Route::delete('/shoes/{shoeId}', [ShoeController::class, 'deleteShoe'])->name('shoes.destroy');
        Route::post('/shoes/options', [ShoeController::class, 'createShoeOptions'])->name('shoes.options.store');
        Route::put('/shoes/options/{optionId}', [ShoeController::class, 'updateOption'])->name('shoes.options.update');
        Route::delete('/shoes/options/{optionId}', [ShoeController::class, 'deleteShoeOption'])->name('shoes.options.destroy');
        Route::put('/shoes/variations/{variationId}', [ShoeController::class, 'updateSku'])->name('shoes.variations.update');
        Route::delete('/shoes/variations/{variationId}', [ShoeController::class, 'deleteSku'])->name('shoes.variations.destroy');
    });
Route::get('/brands', [BrandController::class, 'getAllBrands']);
Route::get('/shoes', [ShoeController::class, 'getAllShoes']);
Route::get('/user/products/{shoeId}', [ShoeController::class, 'show'])->name('products.show');
Route::get('/shoes/admin/{shoeId}', [ShoeController::class, 'getAdminShoeDetails']);
Route::get('/shoes/search', [ShoeController::class, 'searchShoes']);
Route::get('/shoes/brand/{brandId}', [ShoeController::class, 'getShoesByBrand']);
Route::get('/shoes/{shoeId}/options', [ShoeController::class, 'getShoeOptions']);
Route::get('/shoes/{id}', [ShoeController::class, 'getShoeById']);
Route::get('/payment/toyyibpay/return', [PaymentController::class, 'toyyibpayReturn'])->name('toyyibpay.return');
Route::get('/payment/toyyibpay/callback', [PaymentController::class, 'toyyibpayCallback'])->name('toyyibpay.callback');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('user.payment-success');

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
Route::middleware('auth')->get('/user/payment', function (Request $request) {
    $user = $request->user();

    return view('user.payment', [
        'amount' => $request->query('amount', 500),
        'subtotal' => $request->query('subtotal', 500),
        'discountAmount' => $request->query('discount_amount', 0),
        'shipping' => $request->query('shipping', 0),
        'shippingMethod' => $request->query('shipping_method', 'standard'),
        'paymentType' => $request->query('payment_type', 'FPX'),
        'customerName' => $request->query('customer_name', $user?->name),
        'customerEmail' => $request->query('customer_email', $user?->email),
        'customerPhone' => $request->query('customer_phone', $user?->phone ?? ''),
    ]);
})->name('user.payment');

Route::middleware('auth')->get('/test-payment', function () {
    return redirect()->route('user.payment');
});
