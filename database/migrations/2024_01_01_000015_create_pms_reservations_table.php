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
        Schema::connection('tenant')->create('pms_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('confirmation_number', 50)->unique();
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 20)->nullable();
            $table->string('room_number', 50);
            $table->string('room_type', 100);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('adults');
            $table->integer('children')->nullable()->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->foreignId('user_add')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_deleted')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('confirmation_number');
            $table->index('guest_email');
            $table->index(['check_in_date', 'check_out_date']);
            $table->index('status');
            $table->index('room_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_reservations');
    }
};