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
        Schema::connection('tenant')->create('pms_room_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('pms_reservations')->onDelete('cascade');
            $table->string('old_room_number', 50);
            $table->string('new_room_number', 50);
            $table->datetime('change_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('user_add')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_deleted')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['reservation_id', 'change_date']);
            $table->index('status');
            $table->index('old_room_number');
            $table->index('new_room_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_room_changes');
    }
};