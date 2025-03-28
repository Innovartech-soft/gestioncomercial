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
        Schema::table('detalles_ventas', function (Blueprint $table) {
            $table->float('iva_valor')->nullable();
            $table->float('lista_valor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalles_ventas', function (Blueprint $table) {
            $table->dropcolumn('iva_valor');
            $table->dropcolumn('lista_valor');
        });
    }
};
