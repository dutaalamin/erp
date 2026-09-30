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
        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->string('prefix');
            $table->string('suffix')->nullable();
            $table->unsignedInteger('next_number')->default(1);
            $table->unsignedTinyInteger('pad_length')->default(4);
            $table->enum('reset_period', ['never', 'yearly', 'monthly'])->default('yearly');
            $table->date('last_reset_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
    }
};
