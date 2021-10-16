<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->nullable()->unique();
            $table->string('nombre');
            $table->text('descripcion_small', 65535)->nullable();
			$table->text('descripcion_long', 65535)->nullable();
            $table->string('lote')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio_compra',8,2)->nullable();
            $table->decimal('precio_venta',8,2)->nullable();
            $table->decimal('precio_minimo',8,2)->nullable();
            $table->integer('stock')
                  ->unsigned()
                  ->default(0);
            $table->date('fecha_expir')->nullable();
            $table->text('dosis')->nullable();
            $table->text('indicaciones')->nullable();
            $table->text('observacion')->nullable();
            $table->string('slug', 191)->nullable()->unique('slug');
            $table->boolean('se_almacena')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('subcategory_id')->nullable()->constrained('sub_categories');
            $table->json('prices')->nullable();
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
        Schema::dropIfExists('products');
    }
}
