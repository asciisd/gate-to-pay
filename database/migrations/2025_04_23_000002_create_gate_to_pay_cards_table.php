<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGateToPayCardsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('gate_to_pay_cards')) {
            Schema::create('gate_to_pay_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('customer_id')->comment('Customer ID to use in Gate To Pay');
                $table->string('card_id')->nullable()->comment('Card ID from Gate To Pay');
                $table->string('card_number')->nullable()->comment('Card Number from Gate To Pay');
                $table->string('exp_date')->nullable()->comment('Card expiry date in MM/YY format');
                $table->string('status')->default('Pending');
                $table->string('name');
                $table->timestamps();

                // Add index for faster lookups
                $table->index('customer_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('gate_to_pay_cards')) {
            Schema::dropIfExists('gate_to_pay_cards');
        }
    }
}
