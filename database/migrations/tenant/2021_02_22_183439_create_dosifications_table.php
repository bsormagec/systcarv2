<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosifications', function (Blueprint $table) {
            $table->id();
            $table->string('nro_autorizacion', 50)->nullable();
			$table->string('llave_dosificacion')->nullable();
			$table->date('fecha_limite')->nullable();
			$table->integer('numero_inicial')->nullable();
			$table->integer('numero_actual')->nullable();
            $table->foreignId('sucursal_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dosifications');
    }
}
