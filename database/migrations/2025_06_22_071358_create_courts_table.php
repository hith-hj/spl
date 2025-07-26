<?php

declare(strict_types=1);

use App\Models\Partner;
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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class);
            $table->boolean('is_main')->default(false);
            $table->boolean('is_outdoor')->default(false);
            $table->string('name');
            $table->string('type');
            $table->string('phone')->unique();
            $table->string('description')->nullable();
            $table->integer('rate')->default(0);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->index(['name', 'type'], 'idx_court_name_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
