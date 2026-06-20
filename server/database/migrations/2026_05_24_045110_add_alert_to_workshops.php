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
            if (!Schema::hasColumn('workshops', 'alert_message')) {
                $table->text('alert_message')->nullable()->after('gateway_config');
            }
            if (!Schema::hasColumn('workshops', 'alert_expires_at')) {
                $table->timestamp('alert_expires_at')->nullable()->after('alert_message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['alert_message', 'alert_expires_at']);
        });
    }
};
