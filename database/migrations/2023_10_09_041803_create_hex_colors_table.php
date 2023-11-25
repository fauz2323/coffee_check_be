<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hex_colors', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('red');
            $table->bigInteger('green');
            $table->bigInteger('blue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hex_colors');
    }
};
