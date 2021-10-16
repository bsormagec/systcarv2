<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizations', function (Blueprint $table) {
            $table->id();
            $table->string('cufd');
            $table->boolean('status')->default(true);
            $table->boolean('transaccion')->nullable();
            $table->date('fecha_vigencia')->nullable();
            $table->json('codigos_respuesta')->nullable();
            $table->string('codigoControl')->nullable();
            $table->string('direccion')->nullable();
            $table->foreignId('sucursal_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autorizations');
    }
}
