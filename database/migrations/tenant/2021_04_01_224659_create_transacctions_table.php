<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacctions', function (Blueprint $table) {
            $table->id();
            $table->integer('sucursal_id')->nullable();
            $table->string('type');
            $table->dateTime('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code', 3);
            $table->double('currency_rate', 15, 8);
            $table->foreignId('account_id')
                  ->nullable()
                  ->constrained();
            $table->integer('contact_id')->nullable();
            $table->integer('category_id')->default(1);
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('reconciled')->default(0);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('sale_id')
                  ->nullable()
                  ->constrained();
            $table->timestamps();
            $table->softDeletes();      
            $table->index(['sucursal_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacctions');
    }
}
