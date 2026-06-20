<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('month');
            $table->integer('year');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->default('cash');
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
