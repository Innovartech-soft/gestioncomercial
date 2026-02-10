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
        Schema::table('comisiones', function (Blueprint $table) {
            $table->double('monto')->change();
            $table->double('ganancia_venta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            $table->double('monto', 8, 2)->change();
            $table->double('ganancia_venta', 8, 2)->nullable()->change();
        });
    }
};
