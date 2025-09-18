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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Información del cliente
            $table->string('nombre_cliente');
            $table->string('email_cliente');
            $table->string('telefono_cliente');
            $table->string('dpi', 20)->nullable();
            $table->string('nit', 20)->nullable();
            $table->text('direccion_entrega');
            $table->string('ciudad')->default('Guatemala');
            $table->string('departamento')->default('Guatemala');
            $table->string('codigo_postal')->nullable();
            
            // Información del pedido
            $table->enum('estado', ['pre_orden', 'pendiente', 'confirmado', 'en_preparacion', 'proceso', 'enviado', 'entregado', 'completado', 'cancelado', 'expirado'])->default('pendiente');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'contra_entrega', 'cancelado'])->default('pendiente');
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'contra_entrega'])->default('contra_entrega');
            $table->enum('tipo_pago', ['linea', 'contra_entrega'])->nullable();
            $table->json('info_pago')->nullable();
            
            // Montos
            $table->decimal('subtotal', 10, 2);
            $table->decimal('envio', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Fechas
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->timestamp('fecha_entrega_estimada')->nullable();
            $table->timestamp('fecha_entrega_real')->nullable();
            
            // Notas y observaciones
            $table->text('notas_cliente')->nullable();
            $table->text('notas_admin')->nullable();
            
            // Tracking
            $table->string('codigo_seguimiento')->nullable();
            $table->string('guia_envio')->nullable();
            $table->string('empresa_envio')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['estado', 'fecha_pedido']);
            $table->index(['user_id', 'fecha_pedido']);
            $table->index('numero_orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
