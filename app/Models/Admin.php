<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $google_id
 * @property string|null $firebase_uid
 * @property string|null $avatar
 * @property int|null $role_id
 * @property array<string>|null $permissions
 * @property string $locale
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Role|null $role
 */
#[Fillable(['name', 'email', 'password', 'google_id', 'firebase_uid', 'avatar', 'role_id', 'permissions', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'locale' => 'string',
            'role_id' => 'integer',
            'permissions' => 'array',
        ];
    }

    /**
     * The dynamic role assigned to this admin.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Human-friendly role name in Arabic.
     */
    public function getRoleNameAttribute(): string
    {
        return $this->role?->name ?? __('غير محدد');
    }

    /**
     * Machine-friendly role slug.
     */
    public function getRoleSlugAttribute(): ?string
    {
        return $this->role?->slug;
    }

    /**
     * Check if admin has super_admin / owner authority.
     */
    public function isSuperAdmin(): bool
    {
        $slug = $this->role?->slug;

        return $slug === 'super_admin'
            || $slug === 'owner'
            || in_array('*', $this->getAllPermissions(), true);
    }

    /**
     * Check if admin has a given role slug or slugs.
     *
     * @param  string|list<string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        $slug = $this->role?->slug;
        if ($slug === null) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($slug, $roles, true);
        }

        return $slug === $roles;
    }

    /**
     * Retrieve all active static permissions for this admin
     * (combines dynamic role permissions + admin-specific custom overrides).
     *
     * @return list<string>
     */
    public function getAllPermissions(): array
    {
        // Super admin has full wildcard authority
        if ($this->role?->slug === 'super_admin' || $this->role?->slug === 'owner') {
            return ['*'];
        }

        $rolePermissions = is_array($this->role?->permissions) ? $this->role->permissions : [];
        $customOverrides = is_array($this->permissions) ? $this->permissions : [];

        /** @var list<string> $all */
        $all = array_values(array_unique(array_merge($rolePermissions, $customOverrides)));

        return $all;
    }

    /**
     * Determine if admin has a specific static permission.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $all = $this->getAllPermissions();

        if (in_array('*', $all, true) || in_array($permission, $all, true)) {
            return true;
        }

        // Support wildcard matching e.g. 'courses.*'
        $parts = explode('.', $permission);
        if (count($parts) === 2 && in_array("{$parts[0]}.*", $all, true)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if admin has any of the given permissions.
     *
     * @param  list<string>  $permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get admin initials.
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
