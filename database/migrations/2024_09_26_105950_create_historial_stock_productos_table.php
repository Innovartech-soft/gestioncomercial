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
        Schema::create('historial_stock_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto'); 
            $table->float('cantidad');
            $table->enum('tipo', ['ingreso', 'egreso']); // Tipo de movimiento
            $table->foreignId('id_venta')->nullable(); 
            $table->string('motivo')->nullable(); 
            $table->foreignId('id_usuario')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_stock_productos');
    }
};
