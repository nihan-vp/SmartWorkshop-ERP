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
        Schema::table('workshops', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('logo');
            $table->string('subscription_status')->default('trial')->after('trial_ends_at'); // trial, active, suspended
        });

        // Seed existing workshops to active status
        Illuminate\Support\Facades\DB::table('workshops')->update([
            'subscription_status' => 'active',
            'trial_ends_at' => now()->addYears(10), // Extremely long trial just in case fallback is triggered
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'subscription_status']);
        });
    }
};
