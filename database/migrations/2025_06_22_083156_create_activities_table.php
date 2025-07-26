<?php

declare(strict_types=1);

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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('belongTo_id');
            $table->string('belongTo_type');
            $table->string('name');
            $table->string('description');
            $table->string('type'); // VIP, normal
            $table->boolean('is_active');
            $table->string('cost');
            $table->integer('cancellation_cost');
            $table->boolean('is_trial');
            $table->enum('trial_option', ['free', 'discount'])->nullable();
            $table->integer('discount_amount')->nullable();
            $table->boolean('is_private');
            $table->integer('private_cost')->nullable();
            $table->timestamps();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
