<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->integer('sucursal_id');
            $table->string('name');
            $table->string('code');
            $table->double('rate', 15, 8);
            $table->string('precision')->nullable();
            $table->string('symbol')->nullable();
            $table->integer('symbol_first')->unsigned()->default(1);
            $table->string('decimal_mark')->nullable();
            $table->string('thousands_separator')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sucursal_id');
            $table->unique(['sucursal_id', 'code', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
