<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Definições de Segurança')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $canManageTwoFactor;

    public bool $twoFactorEnabled;

    public bool $requiresConfirmation;

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (Fortify::confirmsTwoFactorAuthentication() && is_null($user->two_factor_confirmed_at)) {
                $disableTwoFactorAuthentication($user);
            }

            $this->twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
            $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Palavra-passe atualizada com sucesso.'));
    }

    /**
     * Handle the two-factor authentication enabled event.
     */
    #[On('two-factor-enabled')]
    public function onTwoFactorEnabled(): void
    {
        $this->twoFactorEnabled = true;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $disableTwoFactorAuthentication($user);

        $this->twoFactorEnabled = false;
    }
}; ?>

<div class="w-full">
    <section class="w-full">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('Definições de Segurança') }}</flux:heading>

        <x-pages::settings.layout :heading="__('Atualizar palavra-passe')" :subheading="__('Certifique-se de que a sua conta utiliza uma palavra-passe longa e aleatória para se manter segura')">
            <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
                <flux:input wire:model="current_password" :label="__('Palavra-passe atual')" type="password" required autocomplete="current-password" viewable />
                <flux:input wire:model="password" :label="__('Nova palavra-passe')" type="password" required autocomplete="new-password" viewable />
                <flux:input wire:model="password_confirmation" :label="__('Confirmar palavra-passe')" type="password" required autocomplete="new-password" viewable />

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit" data-test="update-password-button">
                        {{ __('Guardar') }}
                    </flux:button>
                </div>
            </form>

            @if ($canManageTwoFactor)
                <section class="mt-12">
                    <flux:heading>{{ __('Autenticação de dois fatores') }}</flux:heading>
                    <flux:subheading>{{ __('Gira as suas definições de autenticação de dois fatores') }}</flux:subheading>

                    <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
                        @if ($twoFactorEnabled)
                            <div class="space-y-4">
                                <flux:text>{{ __('Será solicitado um código PIN seguro e aleatório...') }}</flux:text>
                                <div class="flex justify-start">
                                    <flux:button variant="danger" wire:click="disable">{{ __('Desativar 2FA') }}</flux:button>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                <flux:text variant="subtle">{{ __('Quando activa a autenticação...') }}</flux:text>
                                
                                <flux:button variant="primary" wire:click="$dispatch('start-two-factor-setup')">
                                    {{ __('Ativar 2FA') }}
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        </x-pages::settings.layout>

        @if ($canManageTwoFactor)
            <div>
                @if ($twoFactorEnabled)
                    <div>
                        <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />
                    </div>
                @else
                    <div>
                        <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                    </div>
                @endif
            </div>
        @endif 
    </section>
</div>