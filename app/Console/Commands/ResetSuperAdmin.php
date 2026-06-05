<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:reset
                            {--email=infosuhaimsoft@gmail.com : Super admin email}
                            {--password=12345678 : Super admin password}';

    /**
     * The console command description.
     */
    protected $description = 'Reset or recreate the super admin account (ID=1)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email    = $this->option('email');
        $password = $this->option('password');

        $this->info('=== Super Admin Reset ===');

        $existing = User::find(1) ?? User::where('email', $email)->first();

        if ($existing) {
            $existing->update([
                'id'          => 1,
                'name'        => 'Suhaim Soft Super Admin',
                'email'       => $email,
                'password'    => Hash::make($password),
                'role'        => 'super_admin',
                'workshop_id' => null,
            ]);
            $this->info("✓ Super admin '{$email}' updated successfully.");
        } else {
            User::create([
                'id'          => 1,
                'name'        => 'Suhaim Soft Super Admin',
                'email'       => $email,
                'password'    => Hash::make($password),
                'role'        => 'super_admin',
                'workshop_id' => null,
            ]);
            $this->info("✓ Super admin '{$email}' created successfully.");
        }

        // Remove any other users that are not this super admin
        $deleted = User::where('id', '!=', 1)->where('role', 'super_admin')->delete();
        if ($deleted > 0) {
            $this->warn("⚠ Removed {$deleted} duplicate super_admin user(s).");
        }

        $this->newLine();
        $this->table(['Field', 'Value'], [
            ['Email',    $email],
            ['Password', $password],
            ['Role',     'super_admin'],
        ]);

        $this->newLine();
        $this->info('Login at: ' . config('app.url') . '/login');

        return self::SUCCESS;
    }
}
