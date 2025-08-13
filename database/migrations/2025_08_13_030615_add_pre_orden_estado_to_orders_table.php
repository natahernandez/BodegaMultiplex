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
            // Modificar el enum para incluir 'pre_orden'
            $table->dropColumn('estado');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('estado', [
                'pre_orden',      // Nueva: para pagos en línea antes de confirmar
                'pendiente', 
                'confirmado', 
                'en_preparacion', 
                'proceso',        // Mantener para compatibilidad
                'enviado', 
                'entregado', 
                'completado',
                'cancelado',
                'expirado'        // Nueva: para pre-órdenes que expiraron
            ])->default('pendiente')->after('codigo_postal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('estado', [
                'pendiente', 
                'confirmado', 
                'en_preparacion', 
                'proceso',
                'enviado', 
                'entregado', 
                'completado',
                'cancelado'
            ])->default('pendiente')->after('codigo_postal');
        });
    }
};