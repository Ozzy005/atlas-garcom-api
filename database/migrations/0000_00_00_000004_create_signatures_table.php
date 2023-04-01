<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('recurrence')->unsigned()->default(\App\Enums\Recurrence::MONTHLY->value);
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('has_discount')->default(false);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('discounted_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('color')->nullable();
            $table->tinyInteger('status')->unsigned()->default(\App\Enums\Status::ACTIVE->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
