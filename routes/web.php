<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SearchController;
use App\Models\HomeSection;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;

// ========== HOME PAGE ==========
Route::get('/', function () {
    $sliders = Slider::where('type', 'slider')
        ->where('is_active', true)
        ->orderBy('order')
        ->get();

    $banners = Slider::where('type', 'banner')
        ->where('is_active', true)
        ->orderBy('order')
        ->limit(2)
        ->get();

    $sections = HomeSection::where('is_active', true)
        ->with(['items'])
        ->orderBy('priority')
        ->get();

    return view('home', compact('sliders', 'banners', 'sections'));
});

// ========== PRODUCT IMAGE (private storage) ==========
Route::get('/product-image/{filename}', function ($filename) {
    $path = storage_path('app/private/products/'.$filename);
    if (! file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('product.image');

// ========== PRODUCT INFO (for cart popup) ==========
Route::get('/product-info/{id}', function ($id) {
    $product = Product::findOrFail($id);
    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
    $image = isset($images[0]) ? route('product.image', basename($images[0])) : asset('images/no-image.png');

    return response()->json([
        'name' => $product->name,
        'price' => number_format($product->sale_price ?? $product->price),
        'image' => $image,
    ]);
});

// Single Product Page
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');

// Parent Category Page
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category.show');

// Child Category Page
Route::get('/category/{parentSlug}/{childSlug}', [ProductController::class, 'childCategory'])->name('category.child.show');

// ========== CART ==========
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// ========== CUSTOM PAGES ==========
Route::get('/pages/{slug}', [CustomPageController::class, 'show'])->name('pages.show');

// ========== Order Success ==========
Route::get('/order-success/{id}', function ($id) {
    $order = Order::with('orderItems.product')->findOrFail($id);

    return view('order-success', compact('order'));
})->name('order.success');

// ========== INVOICE ==========
Route::get('/admin/orders/{order}/invoice', [InvoiceController::class, 'show'])
    ->name('orders.invoice')
    ->middleware(['auth']);

// ========== ADMIN PANEL REDIRECT ==========
Route::get('/new-login', function () {
    return redirect('/new-login/dashboard');
});

// ========== MENU MANAGE ==========
Route::middleware(['auth'])->prefix('new-login')->group(function () {
    Route::get('/menu-manage', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::post('/menu-manage', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::post('/menu-manage/reorder', [MenuController::class, 'reorder'])->name('admin.menu.reorder');
    Route::put('/menu-manage/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu-manage/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
});

// ========== SEARCH ==========
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/api/search', [SearchController::class, 'search'])->name('search');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// ============checkout page  ===========
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// ========= Wishlists =============
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// =================Customer Protected Routes=========================
Route::middleware('auth:customer')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{id}', [AccountController::class, 'orderDetail'])->name('account.orders.detail');
    Route::get('/account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::get('/account/wishlist', [WishlistController::class, 'index'])->name('account.wishlist');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::post('/products/{slug}/review', [ReviewController::class, 'store'])->name('product.review.store');

});
