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
        // Create penalties table
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();

            // Type: 'late_return', 'damaged', 'missing'
            $table->string('type');

            $table->integer('penalty_points')->default(0);
            $table->text('reason')->nullable();
            $table->string('status')->default('active'); // active, resolved, waived

            $table->timestamps();

            $table->index(['borrower_id', 'type']);
            $table->index(['borrow_request_id', 'type']);
        });

        // Add penalty tracking columns to borrow_requests
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->integer('penalty_points')->default(0)->after('total_points');
            $table->integer('overdue_days')->default(0)->after('penalty_points');
            $table->text('damage_description')->nullable()->after('overdue_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');

        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropColumn(['penalty_points', 'overdue_days', 'damage_description']);
        });
    }
};
