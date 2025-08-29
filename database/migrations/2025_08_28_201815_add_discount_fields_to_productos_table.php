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
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('en_oferta')->default(false)->after('activo');
            $table->decimal('descuento_porcentaje', 5, 2)->nullable()->after('en_oferta');
            $table->decimal('precio_oferta', 10, 2)->nullable()->after('descuento_porcentaje');
            $table->datetime('fecha_inicio_oferta')->nullable()->after('precio_oferta');
            $table->datetime('fecha_fin_oferta')->nullable()->after('fecha_inicio_oferta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['en_oferta', 'descuento_porcentaje', 'precio_oferta', 'fecha_inicio_oferta', 'fecha_fin_oferta']);
        });
    }
};
