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
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('amount_paid', 12, 2)->default(0)->after('total');
        });

        // Set amount_paid = total for existing paid bills
        DB::table('bills')->where('payment_status', 'paid')->update([
            'amount_paid' => DB::raw('total')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('amount_paid');
        });
    }
};
