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
        // Extend workshops table
        Schema::table('workshops', function (Blueprint $table) {
            if (!Schema::hasColumn('workshops', 'restrict_features_on_expiry')) {
                $table->boolean('restrict_features_on_expiry')->default(true);
            }
            if (!Schema::hasColumn('workshops', 'admin_extend_allowed')) {
                $table->boolean('admin_extend_allowed')->default(false);
            }
            if (!Schema::hasColumn('workshops', 'trial_extended_count')) {
                $table->integer('trial_extended_count')->default(0);
            }
        });

        // Create activity_logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('workshop_id')->nullable();
            $table->string('action');
            $table->text('description');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('workshop_id')->references('id')->on('workshops')->onDelete('cascade');
        });

        // Create system_settings table
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('activity_logs');
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['restrict_features_on_expiry', 'admin_extend_allowed', 'trial_extended_count']);
        });
    }
};
