<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create workshops table (idempotent)
        if (!Schema::hasTable('workshops')) {
            Schema::create('workshops', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('gstin')->nullable();
                $table->string('logo')->nullable();
                $table->timestamps();
            });
        }

        // 2. Insert the default workshop if none exists
        $defaultWorkshopId = DB::table('workshops')->value('id');
        if (!$defaultWorkshopId) {
            $defaultWorkshopId = DB::table('workshops')->insertGetId([
                'name'       => 'Suhaim Soft Work Shop',
                'phone'      => '+91 9876543210',
                'email'      => 'info@suhaimsoft.com',
                'address'    => '123 Workshop Avenue, City',
                'gstin'      => '29XXXXXXXXXX1Z5',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Add workshop_id and role to users (idempotent)
        if (!Schema::hasColumn('users', 'workshop_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('workshop_id')->nullable()->constrained('workshops')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin');
            });
        }

        // Update existing users with no workshop_id to belong to default workshop
        DB::table('users')->whereNull('workshop_id')->update(['workshop_id' => $defaultWorkshopId]);

        // 4. Add workshop_id to all tenant tables (idempotent)
        $tenantTables = [
            'customers',
            'vehicles',
            'products',
            'services',
            'employees',
            'bills',
            'expenses',
            'salaries',
            'warranties',
            'work_orders',
            'bill_templates',
        ];

        foreach ($tenantTables as $tableName) {
            if (!Schema::hasColumn($tableName, 'workshop_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('workshop_id')->nullable()->after('id');
                });

                // Update existing records to point to default workshop
                DB::table($tableName)->update(['workshop_id' => $defaultWorkshopId]);

                // Add foreign key constraint
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->foreign('workshop_id')->references('id')->on('workshops')->cascadeOnDelete();
                });
            } else {
                // Column exists, just ensure records without a workshop_id get assigned
                DB::table($tableName)->whereNull('workshop_id')->update(['workshop_id' => $defaultWorkshopId]);
            }
        }
    }

    public function down(): void
    {
        $tenantTables = [
            'customers',
            'vehicles',
            'products',
            'services',
            'employees',
            'bills',
            'expenses',
            'salaries',
            'warranties',
            'work_orders',
            'bill_templates',
        ];

        foreach ($tenantTables as $tableName) {
            if (Schema::hasColumn($tableName, 'workshop_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['workshop_id']);
                    $table->dropColumn('workshop_id');
                });
            }
        }

        if (Schema::hasColumn('users', 'workshop_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['workshop_id']);
                $table->dropColumn('workshop_id');
            });
        }
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        Schema::dropIfExists('workshops');
    }
};
