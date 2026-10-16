<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // 'learning', 'challenger', 'immortal'
            $table->string('name');
            $table->string('badge_title')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('billing_cycle')->default('per bulan');
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('membership_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained('membership_plans')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('qris'); // 'qris', 'bank_transfer', 'ewallet'
            $table->string('payment_status')->default('pending'); // 'pending', 'paid', 'cancelled', 'failed'
            $table->string('payment_reference')->nullable();
            $table->string('proof_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_orders');
        Schema::dropIfExists('membership_plans');
    }
};
