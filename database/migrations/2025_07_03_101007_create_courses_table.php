<?php

declare(strict_types=1);

use App\Models\Court;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class)->nullable();
            $table->foreignIdFor(Court::class)->nullable();
            $table->string('name');
            $table->string('type'); // daily,monthly
            $table->integer('month_sessions')->nullable(); // monthly[10,15,30]
            $table->integer('cost');
            $table->integer('cancellation_cost');
            $table->boolean('is_multiPerson')->default(false);
            $table->integer('capacity')->nullable();
            $table->boolean('in_public')->default(false);
            $table->boolean('is_main')->default(false);
            $table->boolean('is_outdoor')->default(false);
            $table->boolean('is_active')->default(false);
            $table->integer('rate')->default(0);
            $table->text('description')->nullable();
            $table->string('trainers')->nullable();
            $table->timestamps();
            // last_visit_at
            // start_date
            // end_date
            // session_count
            // remaining_count
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
