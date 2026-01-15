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
    Schema::create('point_transactions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // optional: link to borrow request that caused the transaction
        $table->foreignId('borrow_request_id')
              ->nullable()
              ->constrained('borrow_requests')
              ->nullOnDelete();

        // earn, spend, refund, adjustment
        $table->string('type', 20);

        // can be + or - (e.g., spend = -50, earn = +50)
        $table->integer('amount');

        $table->string('description')->nullable();

        $table->timestamps();

        $table->index(['user_id', 'type']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
