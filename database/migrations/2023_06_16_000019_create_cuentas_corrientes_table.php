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
    public $tableName = 'cuentas_corrientes';

    /**
     * Run the migrations.
     * @table cuentas_corrientes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->float('monto');
            $table->float('saldo')->nullable();

            $table->index(["id_recibo"], 'fk_cuentas_corrientes_recibos1_idx');

            $table->index(["id_cliente"], 'fk_cuentas_corrientes_clientes1_idx');


            $table->integer('id_recibo')->foreign('id_recibo', 'fk_cuentas_corrientes_recibos1_idx')
                ->references('id')->on('recibos');

            $table->integer('id_cliente')->foreign('id_cliente', 'fk_cuentas_corrientes_clientes1_idx')
                ->references('id')->on('clientes');

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
