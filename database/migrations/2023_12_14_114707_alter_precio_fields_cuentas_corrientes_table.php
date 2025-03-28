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
       Schema::table('cuentas_corrientes', function (Blueprint $table) {
            $table->double('monto')->change();
            $table->double('saldo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuentas_corrientes', function (Blueprint $table) {
            // Rollback a FLOAT
            $table->float('monto')->change();
            $table->float('saldo')->change();
        });
    }
};
