<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('nif')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('name')->nullable();
            $table->string('state_registration')->nullable();
            $table->string('city_registration')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('city_id')->nullable()->constrained();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
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
        Schema::dropIfExists('people');
    }
};
