<?php

declare(strict_types=1);

use App\Models\Category;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_sub')->nullable();
            $table->string('parent_id')->nullable();
            $table->timestamps();
        });

        Schema::create('category_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partner::class);
            $table->foreignIdFor(Category::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
