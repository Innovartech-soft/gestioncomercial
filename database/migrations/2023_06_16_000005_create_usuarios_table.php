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
    public $tableName = 'usuarios';

    /**
     * Run the migrations.
     * @table usuarios
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->string('password');
            $table->tinyInteger('estado')->default('1');
            //$table->tinyInteger('administrador')->default('0');
            $table->index(["id_compania"], 'fk_usuario_compania1_idx');
            $table->index(["id_rol"], 'fk_usuario_rol1_idx');
            
            $table->integer('id_compania')->foreign('id_compania', 'fk_usuario_compania1_idx')
                ->references('id')->on('companias')->nullable();

            $table->integer('id_rol')->foreign('id_rol', 'fk_usuario_rol1_idx')
                ->references('id')->on('roles')->default(1);

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
