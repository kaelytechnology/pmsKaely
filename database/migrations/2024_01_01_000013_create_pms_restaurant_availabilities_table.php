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
        Schema::connection('tenant')->create('pms_restaurant_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('pms_restaurants')->onDelete('cascade');
            $table->date('date');
            $table->time('time_slot');
            $table->integer('available_capacity')->default(0);
            $table->boolean('is_available')->default(true);
            $table->foreignId('user_add')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_edit')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_deleted')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['restaurant_id', 'date', 'time_slot']);
            $table->index('date');
            $table->index('is_available');
            $table->unique(['restaurant_id', 'date', 'time_slot'], 'pms_rest_avail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_restaurant_availabilities');
    }
};