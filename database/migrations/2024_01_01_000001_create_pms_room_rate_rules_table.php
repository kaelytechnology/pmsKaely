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
        Schema::connection('tenant')->create('pms_room_rate_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->index();
            $table->string('class', 100);
            $table->string('room_type_name', 100);
            $table->integer('min_nights')->default(1);
            $table->integer('max_guests')->default(1);
            $table->integer('included_dinners')->nullable();
            $table->text('rule_text')->nullable();
            $table->boolean('is_active')->default(true);
            
            // User tracking fields
            $table->unsignedBigInteger('user_add')->nullable();
            $table->unsignedBigInteger('user_edit')->nullable();
            $table->unsignedBigInteger('user_deleted')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['code', 'class']);
            $table->index('is_active');
            $table->index('room_type_name');
            
            // Foreign keys
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
        Schema::connection('tenant')->dropIfExists('pms_room_rate_rules');
    }
};