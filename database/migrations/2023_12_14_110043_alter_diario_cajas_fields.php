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
        Schema::table('diario_cajas', function (Blueprint $table) {
            $table->double('caja_apertura')->change();
            $table->double('caja_actual')->nullable()->change();
            $table->double('caja_cierre')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diario_cajas', function (Blueprint $table) {
            $table->float('caja_apertura')->change();
            $table->float('caja_actual')->nullable()->change();
            $table->float('caja_cierre')->nullable()->change();
        });
    }
};
