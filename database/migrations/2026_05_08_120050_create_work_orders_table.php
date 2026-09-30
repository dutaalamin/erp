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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('bill_of_material_id')->constrained('bill_of_materials')->cascadeOnDelete();
            $table->string('wo_number')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('planned_quantity', 15, 2);
            $table->decimal('actual_quantity', 15, 2)->default(0);
            $table->foreignId('unit_of_measure_id')->constrained('unit_of_measures')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
