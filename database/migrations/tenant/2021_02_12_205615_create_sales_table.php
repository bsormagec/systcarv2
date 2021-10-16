<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
			$table->date('date')->nullable();
            $table->string('document')
                   ->nullable()
                   ->comment('documento del cliente');
            $table->string('invoice_number')->nullable();
			$table->string('cuf')->nullable();
			$table->string('control_code')->nullable();
			$table->string('status')->nullable();
			$table->string('authorization_number')->nullable();
			$table->date('deadline')->nullable();
			$table->decimal('amount', 8,2)->nullable();
			$table->decimal('amount_ice', 8,2)->nullable();
			$table->decimal('amount_exento', 8,2)->nullable();
			$table->decimal('zero_rate', 8,2)->nullable()->comment('tasa sero');
			$table->decimal('subtotal', 8,2)->nullable();
			$table->decimal('discount', 8,2)->nullable();
			$table->decimal('amount_base', 8,2)
                  ->nullable()
                  ->comment('importe base credito fiscal');
			$table->decimal('debito_fiscal', 8,2)->nullable();
			$table->boolean('electronic_bill')->default(0);
			$table->integer('cash')->nullable()->default(1);
			$table->integer('autorizacion_id')->nullable();
			$table->decimal('received',8,2)->nullable();
            $table->decimal('turned',8,2)->nullable()->comment('cambio del cliente');;
			$table->text('observations', 65535)->nullable();
            $table->foreignId('typepayment_id')->constrained('types');
            $table->foreignId('typedocument_id')->constrained('types');
            $table->foreignId('contact_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('sales');
    }
}
