<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Illuminate\Support\Facades\Auth; 
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showVerificationStep = false;

    public bool $setupComplete = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(bool $requiresConfirmation): void
    {
        $this->requiresConfirmation = $requiresConfirmation;
    }

    #[On('start-two-factor-setup')]
    public function startTwoFactorSetup(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $enableTwoFactorAuthentication = app(EnableTwoFactorAuthentication::class);
        $enableTwoFactorAuthentication($user);

        $this->loadSetupData();
        
        // Esta é a instrução correta que força o Alpine/Flux a mostrar o modal na tela
        $this->js("\$flux.modal('two-factor-setup-modal').show()");
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = \App\Models\User::find(Auth::id());

        try {
            if (! $user || ! $user->two_factor_secret) {
                throw new Exception('O segredo de configuração de dois fatores não está disponível.');
            }

            $this->qrCodeSvg = $user->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Falha ao carregar os dados de configuração.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;
            $this->resetErrorBag();
            return;
        }

        $this->closeModal();
        $this->dispatch('two-factor-enabled');
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $confirmTwoFactorAuthentication($user, $this->code);

        $this->setupComplete = true;

        $this->closeModal();
        $this->dispatch('two-factor-enabled');
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');
        $this->resetErrorBag();
    }

   /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showVerificationStep',
            'setupComplete',
        );

        $this->resetErrorBag();
        
        // Força o fecho do modal via JS
        $this->js("\$flux.modal('two-factor-setup-modal').close()");
    }

    /**
     * Get the current modal configuration state.
     */
    public function getModalConfigProperty(): array
    {
        if ($this->setupComplete) {
            return [
                'title' => __('Autenticação de dois fatores ativada'),
                'description' => __('A autenticação de dois fatores está agora ativa. Leia o código QR ou introduza a chave de configuração na sua aplicação de autenticação.'),
                'buttonText' => __('Fechar'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verificar código de autenticação'),
                'description' => __('Introduza o código de 6 dígitos gerado pela sua aplicação de autenticação.'),
                'buttonText' => __('Continuar'),
            ];
        }

        return [
            'title' => __('Ativar autenticação de dois fatores'),
            'description' => __('Para concluir a ativação da autenticação de dois fatores, leia o código QR ou introduza a chave de configuração na sua aplicação de autenticação.'),
            'buttonText' => __('Continuar'),
        ];
    }
}; ?>

<div>
    <flux:modal name="two-factor-setup-modal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
            <flux:subheading>{{ $this->modalConfig['description'] }}</flux:subheading>
        </div>

        @if ($qrCodeSvg && ! $showVerificationStep)
            <div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl mx-auto w-fit">
                {!! $qrCodeSvg !!}
            </div>

            <div class="space-y-2">
                <flux:text variant="subtle" class="text-xs font-semibold uppercase tracking-wider block text-center">
                    {{ __('Chave de Configuração Manual') }}
                </flux:text>
                <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-lg text-center tracking-widest font-sans text-base font-bold select-all text-zinc-800 dark:text-zinc-200">
                    {{ $manualSetupKey }}
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button variant="ghost" wire:click="closeModal">{{ __('Cancelar') }}</flux:button>
                <flux:button variant="primary" wire:click="showVerificationIfNecessary">
                    {{ $this->modalConfig['buttonText'] }}
                </flux:button>
            </div>
        @elseif ($showVerificationStep)
            <form wire:submit.prevent="confirmTwoFactor" class="space-y-6">
                <flux:input wire:model="code" :label="__('Código de Verificação')" placeholder="000000" maxlength="6" required />
                <div class="flex justify-end gap-2">
                    <flux:button variant="ghost" wire:click="resetVerification">{{ __('Voltar') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ $this->modalConfig['buttonText'] }}</flux:button>
                </div>
            </form>
        @else
            <div class="flex items-center justify-center py-6">
                <flux:text>{{ __('A gerar configurações seguras...') }}</flux:text>
            </div>
        @endif
    </flux:modal>
</div>