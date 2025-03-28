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
    public $tableName = 'listas_descuento';

    /**
     * Run the migrations.
     * @table listas
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45);
            $table->float('valor');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table($this->tableName)
            ->insert([[
                "id"                   => 1,
                "nombre"               => "Lista 1",
                "valor"                => 1
                ],[
                "id"                   => 2,
                "nombre"               => "Lista 2",
                "valor"                => 1
                ],[
                "id"                   => 3,
                "nombre"               => "Lista 3",
                "valor"                => 1
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
