<x-layouts::auth :title="__('Recuperar palavra-passe')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Recuperar palavra-passe')" :description="__('Introduza o seu e-mail para receber uma ligação de reposição da palavra-passe')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="email"
                :label="__('Endereço de e-mail')"
                type="email"
                required
                autofocus
                placeholder="email@exemplo.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Enviar ligação de reposição por e-mail') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('Ou volte para') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('iniciar sessão') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>