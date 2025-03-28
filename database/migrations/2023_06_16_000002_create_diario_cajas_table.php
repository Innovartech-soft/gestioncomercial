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
    public $tableName = 'diario_cajas';

    /**
     * Run the migrations.
     * @table diario_cajas
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->float('caja_apertura');
            $table->float('caja_actual')->nullable();
            $table->float('caja_cierre')->nullable();
            $table->timestamps();
            $table->unique(["fecha"], 'fecha_UNIQUE');
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
