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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->date('payment_date');
            $table->enum('type', ['incoming', 'outgoing']);
            $table->enum('partner_type', ['customer', 'supplier']);
            $table->unsignedBigInteger('partner_id');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'other']);
            $table->string('bank_account')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('IDR');
            $table->decimal('exchange_rate', 15, 6)->default(1);
            $table->enum('status', ['draft', 'confirmed', 'reconciled', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['partner_type', 'partner_id'], 'payments_partner_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
