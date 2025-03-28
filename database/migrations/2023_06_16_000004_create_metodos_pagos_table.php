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
    public $tableName = 'metodos_pagos';

    /**
     * Run the migrations.
     * @table metodos_pagos
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table($this->tableName)
            ->insert([[
                "id"                   => 1,
                "nombre"               => "Efectivo"
                ],[
                "id"                   => 2,
                "nombre"               => "Tarjeta"
                ],[
                "id"                   => 3,
                "nombre"               => "Cuenta Corriente"
                ],[
                "id"                   => 4,
                "nombre"               => "Cheque"
                ],[
                "id"                   => 5,
                "nombre"               => "Otro"
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
