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
    public $tableName = 'tipos_recibos';

    /**
     * Run the migrations.
     * @table tipos_recibos
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45);
            $table->boolean('activo_pasivo');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table($this->tableName)
            ->insert([[
                "id"                   => 1,
                "nombre"               => "Pago",
                "activo_pasivo"        => false
                ],[
                "id"                   => 2,
                "nombre"               => "Cobro",
                "activo_pasivo"        => true
                ],[
                "id"                   => 3,
                "nombre"               => "Gasto",
                "activo_pasivo"        => false
                ],[
                "id"                   => 4,
                "nombre"               => "Ajuste",
                "activo_pasivo"        => false
                ],[
                "id"                   => 5,
                "nombre"               => "Movimiento Caja",
                "activo_pasivo"        => false
        ]]);
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
