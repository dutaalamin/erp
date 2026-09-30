<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_of_measures', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('symbol');
            $table->enum('type', ['unit', 'weight', 'length', 'volume', 'time', 'area']);
            $table->foreignId('base_unit_id')->nullable()->constrained('unit_of_measures')->nullOnDelete();
            $table->decimal('conversion_factor', 15, 6)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_of_measures');
    }
};
