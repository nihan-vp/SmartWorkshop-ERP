<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active'); // active, expired, claimed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
