<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create units table
        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('workshop_id')->nullable();
                $table->string('name');
                $table->string('symbol')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();

                $table->foreign('workshop_id')->references('id')->on('workshops')->cascadeOnDelete();
            });
        }

        // 2. Create result_templates table
        if (!Schema::hasTable('result_templates')) {
            Schema::create('result_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('workshop_id')->nullable();
                $table->string('name');
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->text('content')->nullable();
                $table->timestamps();

                $table->foreign('workshop_id')->references('id')->on('workshops')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('result_templates');
        Schema::dropIfExists('units');
    }
};
