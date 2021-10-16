<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
			$table->string('nit')->nullable();
            $table->string('invoice_number',50)->nullable();
            $table->integer('dui_number')->nullable();
            $table->string('authorization_number')->nullable();
            $table->decimal('exempt_amount', 8,2)->nullable();
            $table->decimal('sub_total', 8,2)->nullable();
            $table->decimal('discount', 8,2)->nullable();
            $table->string('status',20);
            $table->decimal('amount_base_cf', 8,2)->nullable();
			$table->decimal('credito_fiscal', 8,2)->nullable();
            $table->integer('type_purchase')->nullable();
            $table->string('codigo_control', 20)->nullable();
            $table->text('observation');
            $table->boolean('expense_money_box')->default(0);
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
        Schema::dropIfExists('purchases');
    }
}
