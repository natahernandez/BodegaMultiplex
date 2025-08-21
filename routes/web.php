<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PagaditoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

// Ruta específica para servir imágenes de productos
Route::get('/storage/productos/{filename}', function ($filename) {
    $path = storage_path('app/public/productos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000')
        ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
})->where('filename', '.*');

// Ruta general para archivos de storage
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $file = file_get_contents($fullPath);
    $type = mime_content_type($fullPath);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000');
})->where('path', '.*');

// Ruta principal con productos
Route::get('/', [ShopController::class, 'index'])->name('welcome');

// Rutas públicas del shop (sin middleware auth)
Route::name('shop.')->group(function () {
    Route::get('/tienda/producto/{producto}', [ShopController::class, 'show'])->name('product.show');
    Route::post('/carrito/agregar', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::get('/carrito', [ShopController::class, 'cart'])->name('cart');
    Route::put('/carrito/actualizar', [ShopController::class, 'updateCart'])->name('cart.update');
    Route::delete('/carrito/eliminar', [ShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/api/search', [ShopController::class, 'searchApi'])->name('search.api');
    Route::post('/carrito/vaciar', [ShopController::class, 'clearCart'])->name('cart.clear');
});

// Rutas del shop que requieren autenticación
Route::middleware('auth')->name('shop.')->group(function () {
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/procesar-orden', [ShopController::class, 'processOrder'])->name('process.order');
    Route::get('/orden/exitosa/{numero_orden}', [ShopController::class, 'orderSuccess'])->name('order.success');
});

// Rutas de Pagadito (pago en línea)
Route::prefix('pagos/pagadito')->middleware('auth')->group(function () {
    // Inicia el pago: redirige al checkout seguro de Pagadito
    Route::get('/iniciar/{order}', [PagaditoController::class, 'iniciar'])->name('pagadito.iniciar');

    // URL de retorno configurada en Pagadito
    Route::get('/retorno', [PagaditoController::class, 'retorno'])->name('pagadito.retorno');
});

Auth::routes();

// Rutas para usuarios autenticados (clientes)
Route::middleware('auth')->group(function () {
    // Vista de pedidos para usuarios (clientes)
    Route::prefix('mis-pedidos')->name('user.orders.')->group(function () {
        Route::get('/', [UserOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [UserOrderController::class, 'show'])->name('show');
    });
});

// Rutas administrativas (solo para administradores)
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Rutas de Productos (Admin)
    Route::resource('productos', ProductoController::class);
    Route::put('productos/{producto}/stock', [ProductoController::class, 'updateStock'])->name('productos.updateStock');
    Route::get('productos-datatable', [ProductoController::class, 'datatable'])->name('productos.datatable');
    
    // Rutas de Marcas (Admin)
    Route::resource('brands', BrandController::class);
    Route::put('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggleStatus');
    
    // Rutas de Categorías (Admin)
    Route::resource('categories', CategoryController::class);
    Route::put('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    
    // Rutas de Órdenes (Admin)
    Route::resource('orders', OrderController::class);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('orders/{order}/generate-pdf', [OrderController::class, 'generatePdf'])->name('orders.generate-pdf');
    Route::put('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::get('orders-export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders-dashboard-data', [OrderController::class, 'dashboardData'])->name('orders.dashboardData');
    
    // Rutas de Administradores
    Route::resource('admin-users', AdminUserController::class);
});
