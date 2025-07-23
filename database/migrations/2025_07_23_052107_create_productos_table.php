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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras')->unique()->nullable();
            $table->string('codigo_interno')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('marca')->nullable();
            $table->string('categoria');
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('precio_mayoreo', 10, 2)->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_maximo')->nullable();
            $table->string('unidad_medida')->default('Unidad'); // Unidad, Kg, Litro, etc.
            $table->string('ubicacion')->nullable(); // Pasillo, estante, etc.
            $table->string('proveedor')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('requiere_receta')->default(false);
            $table->decimal('iva', 5, 2)->default(0.00); // Porcentaje de IVA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
