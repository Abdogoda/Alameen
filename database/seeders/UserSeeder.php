<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for platform students/users.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Owner account also seeded as Student for dual-portal access
        User::query()->updateOrCreate(
            ['email' => 'abdogoda0a@gmail.com'],
            [
                'name' => 'Abdo Goda',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 2. Primary Student Account
        User::query()->updateOrCreate(
            ['email' => 'student@alameen.edu.sa'],
            [
                'name' => 'أحمد منصور (طالب متدرب)',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 3. Featured Student from Dashboard Prototype (Maryam)
        User::query()->updateOrCreate(
            ['email' => 'maryam@alameen.edu.sa'],
            [
                'name' => 'مريم إبراهيم',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 4. Clinical Speech Student
        User::query()->updateOrCreate(
            ['email' => 'omar@alameen.edu.sa'],
            [
                'name' => 'عمر النجار',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 5. Articulation Specialty Student
        User::query()->updateOrCreate(
            ['email' => 'fatima@alameen.edu.sa'],
            [
                'name' => 'فاطمة الزهراء الشناوي',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 6. Fluency & Stuttering Therapy Student
        User::query()->updateOrCreate(
            ['email' => 'youssef@alameen.edu.sa'],
            [
                'name' => 'يوسف الشريف',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        // 7. Test Student for Automated Pest/PHPUnit Tests
        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'طالب تجريبي',
                'password' => $password,
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );
    }
}
