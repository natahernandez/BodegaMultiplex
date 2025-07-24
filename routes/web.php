<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
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
});

// Rutas del shop que requieren autenticación
Route::middleware('auth')->name('shop.')->group(function () {
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/procesar-orden', [ShopController::class, 'processOrder'])->name('process.order');
    Route::get('/orden/exitosa/{numero_orden}', [ShopController::class, 'orderSuccess'])->name('order.success');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Rutas de Productos (Admin) - sin prefijo admin
    Route::resource('productos', ProductoController::class);
    Route::put('productos/{producto}/stock', [ProductoController::class, 'updateStock'])->name('productos.updateStock');
    Route::get('productos-datatable', [ProductoController::class, 'datatable'])->name('productos.datatable');
    
    // Rutas de Órdenes (Admin) - sin prefijo admin
    Route::resource('orders', OrderController::class);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('orders/{order}/generate-pdf', [OrderController::class, 'generatePdf'])->name('orders.generate-pdf');
    Route::put('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::get('orders-export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders-dashboard-data', [OrderController::class, 'dashboardData'])->name('orders.dashboardData');
});
