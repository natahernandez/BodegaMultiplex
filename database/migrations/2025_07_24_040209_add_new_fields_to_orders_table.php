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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('dpi', 20)->nullable()->after('telefono_cliente');
            $table->string('nit', 20)->nullable()->after('dpi');
            $table->enum('tipo_pago', ['linea', 'contra_entrega'])->nullable()->after('metodo_pago');
            $table->json('info_pago')->nullable()->after('tipo_pago');
            $table->string('guia_envio')->nullable()->after('codigo_seguimiento');
            $table->string('empresa_envio')->nullable()->after('guia_envio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['dpi', 'nit', 'tipo_pago', 'info_pago', 'guia_envio', 'empresa_envio']);
        });
    }
};
