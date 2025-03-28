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
    public $tableName = 'parametros';

    /**
     * Run the migrations.
     * @table parametros
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_empresa', 100);
            $table->string('cuit', 45)->nullable();
            $table->string('dir_1', 45)->nullable();
            $table->string('dir_2', 45)->nullable();
            $table->string('tel_1', 45)->nullable();
            $table->string('tel_2', 45)->nullable();
            $table->string('aux_1', 100)->nullable();
            $table->string('aux_2', 100)->nullable();
            $table->tinyInteger('multimoneda')->nullable()->default('0');
            $table->float('dolar',8,2)->nullable()->default('0.00');
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
