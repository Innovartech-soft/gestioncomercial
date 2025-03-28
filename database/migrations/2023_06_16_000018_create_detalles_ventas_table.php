<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'detalles_ventas';

    /**
     * Run the migrations.
     * @table detalles_ventas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->float('cantidad');
            //$table->float('descuento')->nullable();
            $table->float('descuento_porcentual')->nullable();
            $table->float('precio');

            $table->index(["id_venta"], 'fk_detalles_comprobantes_comprobantes1_idx');

            $table->index(["id_lista"], 'fk_detalles_comprobantes_listas1_idx');

            $table->index(["id_producto"], 'fk_detalles_ventas_productos1_idx');


            $table->integer('id_venta')->foreign('id_venta', 'fk_detalles_comprobantes_comprobantes1_idx')
                ->references('id')->on('ventas');

            $table->integer('id_lista')->foreign('id_lista', 'fk_detalles_comprobantes_listas1_idx')
                ->references('id')->on('listas_descuento');

            $table->integer('id_producto')->foreign('id_producto', 'fk_detalles_ventas_productos1_idx')
                ->references('id')->on('productos');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
