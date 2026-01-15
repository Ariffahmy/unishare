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
            Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lender_id')->constrained('users')->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->unsignedInteger('points_per_day');
            $table->unsignedInteger('total_points');

            $table->string('status')->default('pending');
            // pending, approved, rejected, borrowed, returned, cancelled

            $table->text('note')->nullable();

            $table->timestamps();
            $table->index(['item_id', 'start_date', 'end_date']);
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};
