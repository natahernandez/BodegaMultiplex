<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            
            // Información del producto al momento de la compra (para preservar datos históricos)
            $table->string('nombre_producto');
            $table->string('codigo_producto');
            $table->text('descripcion_producto')->nullable();
            $table->string('categoria_producto');
            
            // Precios y cantidades
            $table->decimal('precio_unitario', 10, 2);
            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);
            
            // Descuentos específicos del ítem
            $table->decimal('descuento_unitario', 10, 2)->default(0);
            $table->decimal('descuento_total', 10, 2)->default(0);
            
            // Información adicional
            $table->json('atributos_producto')->nullable(); // Para variantes, tallas, colores, etc.
            $table->text('notas_item')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['order_id', 'producto_id']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
