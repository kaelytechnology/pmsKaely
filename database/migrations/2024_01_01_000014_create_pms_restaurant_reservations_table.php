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
        Schema::connection('tenant')->create('pms_restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('pms_reservations')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('pms_restaurants')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('pms_events')->onDelete('set null');
            $table->foreignId('food_id')->nullable()->constrained('pms_foods')->onDelete('set null');
            $table->foreignId('dessert_id')->nullable()->constrained('pms_desserts')->onDelete('set null');
            $table->foreignId('beverage_id')->nullable()->constrained('pms_beverages')->onDelete('set null');
            $table->foreignId('decoration_id')->nullable()->constrained('pms_decorations')->onDelete('set null');
            $table->foreignId('special_requirement_id')->nullable()->constrained('pms_special_requirements')->onDelete('set null');
            $table->foreignId('availability_id')->nullable()->constrained('pms_restaurant_availabilities')->onDelete('set null');
            $table->integer('people');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('comment')->nullable();
            $table->string('other', 500)->nullable();
            $table->foreignId('user_add')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_deleted')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['reservation_date', 'reservation_time'], 'pms_rest_res_date_time_idx');
            $table->index('status');
            $table->index('people');
            $table->index(['restaurant_id', 'reservation_date'], 'pms_rest_res_restaurant_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_restaurant_reservations');
    }
};