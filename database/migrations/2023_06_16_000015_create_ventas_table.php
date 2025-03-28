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
    public $tableName = 'ventas';

    /**
     * Run the migrations.
     * @table ventas
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->date('fecha_pago')->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('dni_cuit', 45)->nullable();
            $table->string('telefono', 45)->nullable();
            $table->string('email', 45)->nullable();
            $table->float('total')->nullable();
            $table->float('descuento')->nullable();
            $table->string('nombre_cliente', 100);
            $table->string('notas')->nullable();
            $table->tinyInteger('pagada')->default('0')->nullable();

            $table->index(["id_cliente"], 'fk_comprobantes_clientes1_idx');

            $table->index(["id_usuario"], 'fk_comprobantes_usuarios1_idx');

            $table->index(["id_vendedor"], 'fk_comprobantes_vendedores1_idx');

            $table->index(["id_tipo_venta"], 'fk_ventas_tipos_ventas1_idx');


            $table->integer('id_cliente')->foreign('id_cliente', 'fk_comprobantes_clientes1_idx')
                ->references('id')->on('clientes');

            $table->integer('id_usuario')->foreign('id_usuario', 'fk_comprobantes_usuarios1_idx')
                ->references('id')->on('usuarios');

            $table->integer('id_vendedor')->foreign('id_vendedor', 'fk_comprobantes_vendedores1_idx')
                ->references('id')->on('vendedores');

            $table->integer('id_tipo_venta')->foreign('id_tipo_venta', 'fk_ventas_tipos_ventas1_idx')
                ->references('id')->on('tipos_ventas');
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
