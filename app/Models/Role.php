<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Dynamic Role Model
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $name_en
 * @property string $slug
 * @property string|null $description
 * @property string|null $description_ar
 * @property string|null $description_en
 * @property array<string> $permissions
 * @property bool $is_system
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'name_ar',
        'name_en',
        'slug',
        'description',
        'description_ar',
        'description_en',
        'permissions',
        'is_system',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_system' => 'boolean',
        ];
    }

    /**
     * Get role name based on active locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && ! empty($this->name_en)) {
            return $this->name_en;
        }

        if (! empty($this->name_ar)) {
            return $this->name_ar;
        }

        return $this->attributes['name'] ?? '';
    }

    /**
     * Get role description based on active locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && ! empty($this->description_en)) {
            return $this->description_en;
        }

        if (! empty($this->description_ar)) {
            return $this->description_ar;
        }

        return $this->attributes['description'] ?? null;
    }

    /**
     * Admins assigned to this role.
     */
    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'role_id');
    }

    /**
     * Check if role has a specific static permission.
     */
    public function hasPermission(string $permission): bool
    {
        $perms = is_array($this->permissions) ? $this->permissions : [];

        if (in_array('*', $perms, true)) {
            return true;
        }

        if (in_array($permission, $perms, true)) {
            return true;
        }

        $parts = explode('.', $permission);
        if (count($parts) === 2 && in_array("{$parts[0]}.*", $perms, true)) {
            return true;
        }

        return false;
    }
}
