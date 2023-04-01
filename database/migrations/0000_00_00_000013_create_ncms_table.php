<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ncms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('ato_type');
            $table->string('ato_number');
            $table->string('ato_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ncms');
    }
};
