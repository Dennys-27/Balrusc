<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    // FUNCION UP ES PARA CREAR NUESTRA TABLA
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 30);
            $table->string('numero_documento', 20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */


    // FUNCION DOWN ES PARA REVERIR LA MIGRACION
    public function down()
    {
        Schema::dropIfExists('documentos');
    }
};
