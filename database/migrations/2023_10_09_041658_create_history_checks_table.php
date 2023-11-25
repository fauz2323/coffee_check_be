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
        Schema::create('history_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_apps');
            $table->string('uuid');
            $table->string('name');
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
        Schema::dropIfExists('history_checks');
    }
};
