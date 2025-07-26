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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('birth_date');
            $table->string('gender');
            $table->string('type');
            $table->string('description')->nullable();
            $table->integer('rate')->default(0);
            $table->timestamps();
            $table->index(['gender', 'type'], 'idx_trainer_gender_type');
            $table->index(['first_name', 'last_name'], 'idx_trainer_first_and_last_name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
