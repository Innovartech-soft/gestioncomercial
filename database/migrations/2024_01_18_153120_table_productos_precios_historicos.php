<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('productos_precios_historicos', function (Blueprint $table) {
            $table->increments('id');
            $table->index(["id_producto"], 'fk_productos_precios_hitoricos_productos1_idx');
            $table->float('precio');
            $table->index(["user_id"],'fk_productos_precios_hitoricos_usuarios1_idx');
            $table->integer('id_producto')->foreign('id_lista', 'fk_productos_precios_hitoricos_productos1_idx')
                ->references('id')->on('productos');
            $table->integer('user_id')->foreign('user_id', 'fk_productos_precios_hitoricos_usuarios1_idx')
                ->references('id')->on('usuarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_precios_historicos');
    }
};
