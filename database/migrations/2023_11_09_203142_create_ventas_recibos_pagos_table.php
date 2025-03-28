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
        Schema::create('ventas_recibos_pagos', function (Blueprint $table) {
            $table->id();
            $table->index(["id_recibo"], 'fk_pagos_recibo1_idx');
            $table->index(["id_venta"], 'fk_pagos_venta1_idx');
            $table->double('monto');
            $table->timestamps();

            $table->integer('id_recibo')->foreign('id_recibo', 'fk_pagos_recibo1_idx')
                ->references('id')->on('recibos')->nullable();
            $table->integer('id_venta')->foreign('id_venta', 'fk_pagos_venta1_idx')
                ->references('id')->on('ventas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_recibos_pagos');
    }
};
