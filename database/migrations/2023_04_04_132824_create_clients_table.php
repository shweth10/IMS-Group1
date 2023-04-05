<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('Insurer_id');
            $table->string('client_fname');
            $table->integer('Age');
            $table->string('driving_license_number');
            $table->string('client_email');
            $table->string('phone_number');
            $table->string('vehicle_model');
            $table->string('vehicle_registration');
            $table->unsignedBigInteger('policy_taken');
            $table->foreign('policy_taken')->references('id')->on('policies')->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
}
