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
    public $tableName = 'tipos_ventas';

    /**
     * Run the migrations.
     * @table tipos_ventas
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
                "nombre"               => "Presupuesto"
                ],[
                "id"                   => 2,
                "nombre"               => "Proforma"
                ],[
                "id"                   => 3,
                "nombre"               => "Venta"
                ],[
                "id"                   => 4,
                "nombre"               => "Nota Credito"
                ],[
                "id"                   => 5,
                "nombre"               => "Nota Debito"
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
