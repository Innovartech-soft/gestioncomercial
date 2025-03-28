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
    public $tableName = 'recibos';

    /**
     * Run the migrations.
     * @table recibos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->float('monto');
            $table->tinyInteger('es_cobro');
            $table->string('detalle', 250)->nullable();

            $table->index(["id_cliente"], 'fk_recibos_clientes1_idx');

            $table->index(["id_venta"], 'fk_recibos_ventas1_idx');

            $table->index(["id_usuario"], 'fk_recibos_usuarios1_idx');

            $table->index(["id_proveedor"], 'fk_recibos_proveedores1_idx');

            $table->index(["id_tipo_recibo"], 'fk_recibos_tipos_recibos1_idx');


            $table->integer('id_cliente')->foreign('id_cliente', 'fk_recibos_clientes1_idx')
                ->references('id')->on('clientes')
                ->nullable();

            $table->integer('id_venta')->foreign('id_venta', 'fk_recibos_ventas1_idx')
                ->references('id')->on('ventas')
               
                ->nullable();

            $table->integer('id_usuario')->foreign('id_usuario', 'fk_recibos_usuarios1_idx')
                ->references('id')->on('usuarios')
                
                ->nullable();

            $table->integer('id_proveedor')->foreign('id_proveedor', 'fk_recibos_proveedores1_idx')
                ->references('id')->on('proveedores')
                ->nullable();

            $table->integer('id_tipo_recibo')->foreign('id_tipo_recibo', 'fk_recibos_tipos_recibos1_idx')
                ->references('id')->on('tipos_recibos')
                ->nullable();
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
