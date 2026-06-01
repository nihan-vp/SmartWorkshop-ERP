<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_template_id')->constrained('bill_templates')->cascadeOnDelete();
            $table->string('item_type'); // product or service
            $table->unsignedBigInteger('item_id');
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_template_items');
    }
};
