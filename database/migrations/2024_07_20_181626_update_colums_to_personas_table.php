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
    public function up()
    {
        //Eliminar LLave Foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });

        //Creae LLave Foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('documento_id')->after('estado')->constrained('documentos')->onDelete('cascade');
        });

        //Creae Nuevo Campo
        Schema::table('personas', function (Blueprint $table) {
            $table->string('numero_documento', 20)->after('documento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Eliminar LLave Foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });

        //Creae LLave Foranea
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('documento_id')->after('estado')->constrained('documentos')->onDelete('cascade');
        });
        
        //Creae Nuevo Campo
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('numero_documento');
        });
    }
};
