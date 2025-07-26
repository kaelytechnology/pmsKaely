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
        Schema::connection('tenant')->create('pms_restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('short_name', 50)->unique();
            $table->string('full_name')->unique();
            $table->unsignedBigInteger('food_type');
            $table->integer('min_capacity');
            $table->integer('max_capacity');
            $table->integer('total_capacity');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->text('description')->nullable();
            
            // User tracking fields
            $table->unsignedBigInteger('user_add')->nullable();
            $table->unsignedBigInteger('user_edit')->nullable();
            $table->unsignedBigInteger('user_deleted')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('short_name');
            $table->index('full_name');
            $table->index('food_type');
            $table->index(['min_capacity', 'max_capacity', 'total_capacity']);
            $table->index(['opening_time', 'closing_time']);
            $table->index(['created_at', 'updated_at']);
            $table->index('deleted_at');
            
            // Foreign key constraints
            $table->foreign('food_type')->references('id')->on('pms_food_types')->onDelete('restrict');
            $table->foreign('user_add')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_edit')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_deleted')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('pms_restaurants');
    }
};