<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('due_day_signature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signature_id')->constrained();
            $table->foreignId('due_day_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('due_day_signature');
    }
};
