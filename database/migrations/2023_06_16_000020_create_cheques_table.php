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
    public $tableName = 'cheques';

    /**
     * Run the migrations.
     * @table cheques
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('banco_emisor', 100)->nullable();
            $table->string('numero', 100);
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->string('serie', 45)->nullable();
            $table->float('importe',8,2);
            $table->string('cuenta', 45)->nullable();
            $table->string('titular_librador', 100)->nullable();
            $table->tinyInteger('endosado')->nullable()->default('0');
            $table->tinyInteger('cruzado')->nullable()->default('0');
            $table->string('nombre_beneficiario', 100)->nullable();
            $table->tinyInteger('estado')->default('1');

            $table->index(["id_cliente"], 'fk_cheques_clientes1_idx')->nullable();

            $table->index(["id_recibo"], 'fk_cheques_recibos1_idx')->nullable()->default(NULL);

            $table->integer('id_cliente')->foreign('id_cliente', 'fk_cheques_clientes1_idx')
                ->references('id')->on('clientes');

            $table->integer('id_recibo')->foreign('id_recibo', 'fk_cheques_recibos1_idx')
                ->references('id')->on('recibos');

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
