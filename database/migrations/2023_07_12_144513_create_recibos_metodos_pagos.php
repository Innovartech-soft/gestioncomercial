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
        Schema::create('recibos_metodos_pagos', function (Blueprint $table) {
            $table->id();
            $table->index(["id_recibo"], 'fk_metodo_pago_recibo1_idx');
            $table->index(["id_metodo_pago"], 'fk_recibo_metodo_pago1_idx');
            $table->double('valor');
            $table->timestamps();
            $table->softDeletes();

            $table->integer('id_recibo')->foreign('id_recibo', 'fk_metodo_pago_recibo1_idx')
                ->references('id')->on('recibos');

            $table->integer('id_metodo_pago')->foreign('id_metodo_pago', 'fk_recibo_metodo_pago1_idx')
                ->references('id')->on('metodos_pagos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos_metodos_pagos');
    }
};
