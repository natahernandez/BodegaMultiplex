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
            // Actualizar el enum de estado para incluir 'proceso' y 'completado'
            $table->enum('estado', ['pendiente', 'confirmado', 'en_preparacion', 'proceso', 'enviado', 'entregado', 'completado', 'cancelado'])->default('pendiente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revertir al enum original
            $table->enum('estado', ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado', 'cancelado'])->default('pendiente')->change();
        });
    }
};
