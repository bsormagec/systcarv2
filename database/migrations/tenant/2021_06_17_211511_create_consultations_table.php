<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->decimal('peso',4,2);
            $table->decimal('temperatura',2,0);
            $table->string('frec_card',10)->comment('frecuencia cardiaca');
            $table->string('ritm_card',10)->comment('ritmo cardiaco');
            $table->string('mucosa',50)->nullable();
            $table->string('turgencia',50)->nullable();
            $table->string('pulso',10)->nullable();
            $table->string('varios')->nullable();
            $table->foreignId('appointment_id')->constrained();
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
        Schema::dropIfExists('consultations');
    }
}
