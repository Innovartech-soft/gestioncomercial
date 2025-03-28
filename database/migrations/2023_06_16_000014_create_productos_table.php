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
    public $tableName = 'productos';

    /**
     * Run the migrations.
     * @table productos
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 250);
            $table->string('detalle',500)->nullable();
            $table->string('codigo', 100)->nullable();
            $table->string('codigo_interno', 50);
            $table->string('codigo_barras', 100)->nullable();
            $table->double('precio_costo');
            $table->tinyInteger('es_dolar')->nullable()->default('0');
            $table->double('precio_venta')->nullable();
            $table->string('url', 100)->nullable();
            $table->integer('tipo_iva')->default('1');
            $table->float('stock')->nullable()->default('0');
            $table->float('stock_minimo')->nullable();
            $table->string('unidad_medida', 20)->nullable();
            $table->float('bulto')->nullable();
            
            $table->index(["id_rubro"], 'fk_Productos_Rubros_idx');

            $table->index(["id_marca"], 'fk_Productos_marcas1_idx');

            $table->index(["id_proveedor"], 'fk_Productos_proveedores1_idx');

            $table->index(["id_lista_ganancia"], 'fk_Productos_listas_ganancia_idx');


            $table->integer('id_lista_ganancia')->foreign('id_lista_ganancia', 'fk_Productos_listas_ganancia_idx')
                ->references('id')->on('listas_ganancia');

            $table->integer('id_rubro')->foreign('id_rubro', 'fk_Productos_Rubros_idx')
                ->references('id')->on('rubros');

            $table->integer('id_marca')->foreign('id_marca', 'fk_Productos_marcas1_idx')
                ->references('id')->on('marcas');

            $table->integer('id_proveedor')->foreign('id_proveedor', 'fk_Productos_proveedores1_idx')
                ->references('id')->on('proveedores');
                
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
