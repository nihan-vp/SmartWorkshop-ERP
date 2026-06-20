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
        Schema::create('product_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->integer('duration_days')->default(30);
            $table->string('status')->default('unused'); // unused, used
            $table->foreignId('used_by_workshop_id')->nullable()->constrained('workshops')->cascadeOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_keys');
    }
};
