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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('type');
            $table->text('fb_token');
            $table->Integer('status');
            $table->boolean('is_active');
            $table->boolean('is_visible');
            $table->string('is_admin')->nullable();
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
