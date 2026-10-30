<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SecurityAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--user= : Audit a specific user by email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit authentication, two-factor, and passkeys security health across the application';

    /**
     * Execute the console command.
     */
    public function handle(AuthenticationService $authService): int
    {
        $specificEmail = $this->option('user');

        if ($specificEmail) {
            $user = User::where('email', $specificEmail)->first();

            if (! $user) {
                $this->error("User not found: {$specificEmail}");

                return self::FAILURE;
            }

            $profile = $authService->getSecurityProfile($user);

            $this->info("=== Security Audit for User: {$user->email} ===");
            $this->table(
                ['Attribute', 'Value'],
                [
                    ['ID', $user->id],
                    ['Name', $user->name],
                    ['Email', $user->email],
                    ['Email Verified', $profile['user']['email_verified'] ? 'YES' : 'NO'],
                    ['Email Verified At', $profile['user']['email_verified_at'] ?? 'N/A'],
                    ['Two-Factor Enabled', $profile['security']['two_factor_enabled'] ? 'YES' : 'NO'],
                    ['Two-Factor Confirmed At', $profile['security']['two_factor_confirmed_at'] ?? 'N/A'],
                    ['Registered Passkeys', $profile['security']['passkeys_count']],
                ]
            );

            return self::SUCCESS;
        }

        $this->info('=== System Security & Authentication Audit ===');

        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $twoFactorUsers = User::whereNotNull('two_factor_confirmed_at')->count();

        $passkeysCount = 0;
        try {
            $passkeysCount = DB::table('passkeys')->count();
        } catch (\Throwable) {
            // table might not exist
        }

        $usersWithPasskeys = User::whereHas('passkeys')->count();

        $this->table(
            ['Metric', 'Count', 'Percentage'],
            [
                ['Total Users', $totalUsers, '100%'],
                ['Email Verified Users', $verifiedUsers, $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1).'%' : '0%'],
                ['2FA Enabled & Confirmed', $twoFactorUsers, $totalUsers > 0 ? round(($twoFactorUsers / $totalUsers) * 100, 1).'%' : '0%'],
                ['Users with Passkeys', $usersWithPasskeys, $totalUsers > 0 ? round(($usersWithPasskeys / $totalUsers) * 100, 1).'%' : '0%'],
                ['Total Passkeys Registered', $passkeysCount, '-'],
            ]
        );

        return self::SUCCESS;
    }
}
