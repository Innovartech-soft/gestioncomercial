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
    public $tableName = 'clientes';

    /**
     * Run the migrations.
     * @table clientes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('razon_social', 100);
            $table->string('dni_cuit', 45)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('tel_1', 45)->nullable();
            $table->string('tel_2', 45)->nullable();
            $table->string('email', 45)->nullable();
            $table->string('notas')->nullable();
            $table->integer('estado_cuenta')->default(1);
            $table->timestamps();

            $table->index(["id_lista"], 'fk_clientes_listas1_idx');


            $table->integer('id_lista')->foreign('id_lista', 'fk_clientes_listas1_idx')
                ->references('id')->on('listas_descuento');

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
