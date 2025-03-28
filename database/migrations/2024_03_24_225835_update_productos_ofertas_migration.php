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
        //
        Schema::table('productos', function (Blueprint $table) {
            $table->float('precio_costo_oferta')->nullable();
            $table->dateTime('oferta_fecha_desde')->nullable();
            $table->dateTime('oferta_fecha_hasta')->nullable();
            $table->boolean('en_oferta')->default(0);
            $table->boolean('no_comisionable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('precio_costo_oferta');
            $table->dropColumn('oferta_fecha_desde');
            $table->dropColumn('oferta_fecha_hasta');
            $table->dropColumn('en_oferta');
            $table->dropColumn('no_comisionable');
        });
    }
};
