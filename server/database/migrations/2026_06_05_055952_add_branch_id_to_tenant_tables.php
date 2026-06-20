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
        $tables = ['users', 'bills', 'work_orders', 'expenses', 'employees'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('workshop_id')->constrained('branches')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'bills', 'work_orders', 'expenses', 'employees'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }
};
