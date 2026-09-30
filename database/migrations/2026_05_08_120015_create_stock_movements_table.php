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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment']);
            $table->decimal('quantity', 15, 2);
            $table->decimal('before_quantity', 15, 2);
            $table->decimal('after_quantity', 15, 2);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->foreignId('moved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('moved_at');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id'], 'stock_movements_reference_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
