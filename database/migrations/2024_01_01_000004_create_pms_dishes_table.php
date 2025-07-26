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
        Schema::connection('tenant')->create('pms_dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            
            // User tracking fields
            $table->unsignedBigInteger('user_add')->nullable();
            $table->unsignedBigInteger('user_edit')->nullable();
            $table->unsignedBigInteger('user_deleted')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('name');
            $table->index(['created_at', 'updated_at']);
            $table->index('deleted_at');
            
            // Foreign key constraints
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
        Schema::connection('tenant')->dropIfExists('pms_dishes');
    }
};