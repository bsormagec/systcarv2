<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('discount',8,2)
                  ->nullable()
                  ->default(0);
            $table->decimal('sale_price',8,2);
            $table->text('observation', 65535)->nullable();
            $table->integer('almacen')
                  ->unsigned()
                  ->nullable();
            $table->integer('unit_id')
                  ->unsigned()
                  ->nullable()
                  ->comment('control de cant x unit en anulacion de factura');
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('product_id')->constrained();
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
        Schema::dropIfExists('detail_sales');
    }
}
