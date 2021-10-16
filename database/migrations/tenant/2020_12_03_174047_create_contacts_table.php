<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('sucursal_id');
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('lastName')->nullable();
            $table->string('fullName')->nullable();
            $table->string('business_name')
                  ->nullable()
                  ->comment('empresa para proveedores');
            $table->text('address')->nullable();
            $table->string('ci')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->boolean('enabled');
            $table->string('reference')->nullable();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained();
            $table->unsignedBigInteger('speciality_id')->nullable();
            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->foreignId('typedocument_id')
                  ->nullable()
                  ->constrained('types');
            $table->index(['sucursal_id', 'type']);
            $table->unique(['sucursal_id', 'type', 'email', 'deleted_at']);
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
        Schema::dropIfExists('contacts');
    }
}
