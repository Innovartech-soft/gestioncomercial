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
        Schema::table('recibos', function (Blueprint $table) {
            $table->index(['deleted_at', 'created_at'], 'recibos_deleted_created_index');
            $table->index(['deleted_at', 'afectar_caja', 'created_at'], 'recibos_deleted_afectar_created_index');
            $table->index('id_cliente', 'recibos_id_cliente_index');
            $table->index('id_proveedor', 'recibos_id_proveedor_index');
            $table->index('id_usuario', 'recibos_id_usuario_index');
            $table->index('id_tipo_recibo', 'recibos_id_tipo_recibo_index');
            $table->index('id_venta', 'recibos_id_venta_index');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->index(['id_cliente', 'id_tipo_venta', 'pagada', 'deleted_at', 'id'], 'ventas_cliente_tipo_pagada_deleted_id_index');
        });

        Schema::table('metodos_pagos', function (Blueprint $table) {
            $table->index('nombre', 'metodos_pagos_nombre_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropIndex('recibos_deleted_created_index');
            $table->dropIndex('recibos_deleted_afectar_created_index');
            $table->dropIndex('recibos_id_cliente_index');
            $table->dropIndex('recibos_id_proveedor_index');
            $table->dropIndex('recibos_id_usuario_index');
            $table->dropIndex('recibos_id_tipo_recibo_index');
            $table->dropIndex('recibos_id_venta_index');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('ventas_cliente_tipo_pagada_deleted_id_index');
        });

        Schema::table('metodos_pagos', function (Blueprint $table) {
            $table->dropIndex('metodos_pagos_nombre_index');
        });
    }
};
