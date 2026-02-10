<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForVentasTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->index(['deleted_at', 'pagada', 'id'], 'ventas_deleted_pagada_id_index');
            $table->index(['deleted_at', 'pagada', 'fecha_pago'], 'ventas_deleted_pagada_fecha_pago_index');
            $table->index(['deleted_at', 'pagada', 'fecha'], 'ventas_deleted_pagada_fecha_index');
            $table->index('id_tipo_venta', 'ventas_id_tipo_venta_index');
            $table->index('id_vendedor', 'ventas_id_vendedor_index');
            $table->index('id_usuario', 'ventas_id_usuario_index');
            $table->index('id_cliente', 'ventas_id_cliente_index');
            $table->index('nombre_cliente', 'ventas_nombre_cliente_index');
        });

        Schema::table('ventas_recibos_pagos', function (Blueprint $table) {
            $table->index('id_venta', 'ventas_recibos_pagos_id_venta_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('ventas_deleted_pagada_id_index');
            $table->dropIndex('ventas_deleted_pagada_fecha_pago_index');
            $table->dropIndex('ventas_deleted_pagada_fecha_index');
            $table->dropIndex('ventas_id_tipo_venta_index');
            $table->dropIndex('ventas_id_vendedor_index');
            $table->dropIndex('ventas_id_usuario_index');
            $table->dropIndex('ventas_id_cliente_index');
            $table->dropIndex('ventas_nombre_cliente_index');
        });

        Schema::table('ventas_recibos_pagos', function (Blueprint $table) {
            $table->dropIndex('ventas_recibos_pagos_id_venta_index');
        });
    }
}
