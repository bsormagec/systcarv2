<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->text('code')->nullable();
            $table->string('descriptions');
            $table->text('observations')->nullable();
            $table->date('fecha');
            $table->enum('status', ['R', 'C', 'A', 'S'])->default('R'); // Reserved, Confirmed, Annulated, Served
            $table->timestamp('start_at')->nullable()->index();
            $table->timestamp('finish_at')->nullable()->index();
            $table->integer('duration')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('pet_id')->nullable()->constrained();
            $table->foreignId('medic_id')->nullable()->constrained('contacts');
            $table->foreignId('customer_id')->constrained('contacts');
            $table->foreignId('sucursal_id')->constrained();
            $table->foreignId('service_id')->nullable()->constrained('types');
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
        Schema::dropIfExists('appointments');
    }
}
