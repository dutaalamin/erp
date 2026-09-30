<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['goods', 'service', 'raw_material', 'consumable']);
            $table->foreignId('unit_of_measure_id')->constrained('unit_of_measures')->cascadeOnDelete();
            $table->foreignId('purchase_unit_of_measure_id')->nullable()->constrained('unit_of_measures')->nullOnDelete();
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('minimum_stock', 15, 2)->default(0);
            $table->decimal('weight', 10, 3)->nullable();
            $table->string('barcode')->nullable();
            $table->string('internal_reference')->nullable();
            $table->boolean('is_purchasable')->default(true);
            $table->boolean('is_sellable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
