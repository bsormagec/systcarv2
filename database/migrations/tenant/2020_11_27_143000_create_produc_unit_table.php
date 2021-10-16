<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained();
			$table->foreignId('product_id')->constrained();
			$table->decimal('precio', 8,2)->nullable();
			$table->decimal('precio_minimo', 8,2)->nullable();
			$table->decimal('cantidad_unidad',8,2)->nullable()->default(1);
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
        Schema::dropIfExists('product_unit');
    }
}
