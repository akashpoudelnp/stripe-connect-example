<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events', 'id');
            $table->integer('quantity');
            $table->integer('unit_cost');
            $table->string('email');
            $table->string('name');
            $table->string('status')->default('pending');
            $table->string('checkout_session_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('charge_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
