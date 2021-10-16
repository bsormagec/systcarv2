<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('run_boxes', function (Blueprint $table) {
            $table->id();
            $table->datetime('fecha_inicio');
            $table->decimal('import_inicio',8,2);
            $table->foreignId('user_on_id')
                  ->nullable()
                  ->constrained('users');
            $table->datetime('fecha_fin')->nullable();
            $table->decimal('import_fin',8,2)->nullable();
            $table->foreignId('user_of_id')
                  ->nullable()
                  ->constrained('users');
            $table->boolean('status')->default(true);
            $table->foreignId('account_id')->constrained();
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
        Schema::dropIfExists('run_boxes');
    }
}
