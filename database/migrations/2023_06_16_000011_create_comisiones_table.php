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
    public $tableName = 'comisiones';

    /**
     * Run the migrations.
     * @table comisiones
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->float('monto');
            $table->tinyInteger('estado')->default('0');
            $table->string('periodo', 45)->nullable();
            $table->string('notas', 250)->nullable();


            $table->index(["id_vendedor"], 'fk_comisiones_vendedores1_idx');
            $table->index(["id_venta"], 'fk_comisiones_ventas1_idx');

            $table->integer('id_venta')->foreign('id_venta', 'fk_comisiones_ventas1_idx')
                ->references('id')->on('ventas');
            $table->integer('id_vendedor')->foreign('id_vendedor', 'fk_comisiones_vendedores1_idx')
                ->references('id')->on('vendedores');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
