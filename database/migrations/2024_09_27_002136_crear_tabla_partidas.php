<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('partidas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usuario_id');  // Relación con el usuario
        $table->integer('puntaje');  // Puntaje del jugador
        $table->timestamps();

        // Llave foránea
        $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
    });
}

    
    public function down()
    {
        Schema::dropIfExists('partidas');
    }
    
};
