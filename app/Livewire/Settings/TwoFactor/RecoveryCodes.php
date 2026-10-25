<?php

declare(strict_types=1);

namespace App\Livewire\Settings\TwoFactor;

use App\Services\Auth\TwoFactorService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RecoveryCodes extends Component
{
    /** @var list<string> */
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Mount the component.
     */
    public function mount(TwoFactorService $twoFactorService): void
    {
        $this->loadRecoveryCodes($twoFactorService);
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(TwoFactorService $twoFactorService): void
    {
        $this->recoveryCodes = $twoFactorService->regenerateRecoveryCodes(auth()->user());
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(TwoFactorService $twoFactorService): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            $this->recoveryCodes = $twoFactorService->getRecoveryCodes($user);
        }
    }

    public function render()
    {
        return view('web.settings.two-factor.recovery-codes');
    }
}
