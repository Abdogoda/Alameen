<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passkeys\Support\Aaguids;

/**
 * @property int $id
 * @property int $admin_id
 * @property string $name
 * @property string $credential_id
 * @property array<string, mixed> $credential
 * @property Carbon|null $last_used_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Admin $admin
 * @property-read string|null $authenticator
 */
class AdminPasskey extends Model
{
    use HasFactory;

    protected $table = 'admin_passkeys';

    protected $fillable = [
        'admin_id',
        'name',
        'credential_id',
        'credential',
        'last_used_at',
    ];

    protected $appends = [
        'authenticator',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credential' => 'json',
            'last_used_at' => 'datetime',
        ];
    }

    /**
     * The admin user who owns this passkey.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Alias for user relation to satisfy contracts if needed.
     */
    public function user(): BelongsTo
    {
        return $this->admin();
    }

    /**
     * Get the authenticator brand name based on AAGUID.
     */
    protected function authenticator(): Attribute
    {
        return Attribute::get(function (): ?string {
            $aaguid = $this->credential['aaguid'] ?? null;

            if (! is_string($aaguid) || $aaguid === Aaguids::unknown()) {
                return null;
            }

            return Aaguids::labelFor($aaguid);
        });
    }
}
