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
        Schema::connection('tenant')->create('pms_courtesy_dinners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('pms_reservations')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('pms_restaurants')->onDelete('cascade');
            $table->date('dinner_date');
            $table->time('dinner_time');
            $table->integer('people');
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('user_add')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_deleted')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['reservation_id', 'dinner_date']);
            $table->index(['restaurant_id', 'dinner_date']);
            $table->index('status');
            $table->index(['dinner_date', 'dinner_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_courtesy_dinners');
    }
};