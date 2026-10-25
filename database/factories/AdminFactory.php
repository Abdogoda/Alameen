<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = Role::query()->firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'المالك / مدير النظام العام',
                'description' => 'كامل صلاحيات التحكم في المنصة',
                'permissions' => ['*'],
                'is_system' => true,
            ]
        );

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => $role->id,
            'permissions' => null,
            'locale' => 'ar',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Set role to clinical supervisor.
     */
    public function supervisor(): static
    {
        return $this->state(function (array $attributes) {
            $role = Role::query()->firstOrCreate(
                ['slug' => 'clinical_supervisor'],
                [
                    'name' => 'المشرف السريري والأكاديمي',
                    'permissions' => AdminRole::CLINICAL_SUPERVISOR->defaultPermissions(),
                    'is_system' => false,
                ]
            );

            return [
                'role_id' => $role->id,
            ];
        });
    }

    /**
     * Set role to instructor.
     */
    public function instructor(): static
    {
        return $this->state(function (array $attributes) {
            $role = Role::query()->firstOrCreate(
                ['slug' => 'instructor'],
                [
                    'name' => 'محاضر ومدرب سريري',
                    'permissions' => AdminRole::INSTRUCTOR->defaultPermissions(),
                    'is_system' => false,
                ]
            );

            return [
                'role_id' => $role->id,
            ];
        });
    }

    /**
     * Set role to finance admin.
     */
    public function finance(): static
    {
        return $this->state(function (array $attributes) {
            $role = Role::query()->firstOrCreate(
                ['slug' => 'finance_admin'],
                [
                    'name' => 'المسؤول المالي ومراجعة الإيصالات',
                    'permissions' => AdminRole::FINANCE_ADMIN->defaultPermissions(),
                    'is_system' => false,
                ]
            );

            return [
                'role_id' => $role->id,
            ];
        });
    }

    /**
     * Set role to support admin.
     */
    public function support(): static
    {
        return $this->state(function (array $attributes) {
            $role = Role::query()->firstOrCreate(
                ['slug' => 'support_admin'],
                [
                    'name' => 'مسؤول الدعم الفني والإشعارات',
                    'permissions' => AdminRole::SUPPORT_ADMIN->defaultPermissions(),
                    'is_system' => false,
                ]
            );

            return [
                'role_id' => $role->id,
            ];
        });
    }
}
