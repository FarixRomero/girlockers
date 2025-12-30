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
        Schema::create('membership_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Detalles de la transacción
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PEN');
            $table->enum('membership_type', ['monthly', 'quarterly']);

            // Estado del pago
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])
                  ->default('pending');

            // Datos de Izipay
            $table->string('transaction_id')->nullable()->unique();
            $table->string('order_id')->unique();
            $table->json('izipay_response')->nullable();

            // Metadata
            $table->string('payment_method')->nullable(); // VISA, MASTERCARD
            $table->string('card_last_four', 4)->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
    }
};
