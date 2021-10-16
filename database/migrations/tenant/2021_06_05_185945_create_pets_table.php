<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',30);
            $table->string('color',20);
            $table->string('sexo',20);
            $table->decimal('peso',8,2)->nullable();
            $table->string('chip',30)->nullable();
            $table->date('fecha_nacim')->nullable();
            $table->boolean('deceso')->default(false);
            $table->text('observations')->nullable();
            $table->foreignId('contact_id')->constrained();
            $table->foreignId('especie_id')->constrained();
            $table->foreignId('raza_id')->constrained();
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
        Schema::dropIfExists('pets');
    }
}
