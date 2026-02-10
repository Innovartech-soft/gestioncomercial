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
        Schema::table('ventas', function (Blueprint $table) {
            $table->index(['deleted_at', 'id'], 'ventas_deleted_id_index');
            $table->index(['deleted_at', 'fecha'], 'ventas_deleted_fecha_index');
            $table->index(['deleted_at', 'fecha_pago'], 'ventas_deleted_fecha_pago_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('ventas_deleted_id_index');
            $table->dropIndex('ventas_deleted_fecha_index');
            $table->dropIndex('ventas_deleted_fecha_pago_index');
        });
    }
};
