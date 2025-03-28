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
            $table->float('ganancia_venta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropColumn('ganancia_venta');
        });
    }
};
