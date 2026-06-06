<x-layouts::auth :title="__('Registo')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Criar uma conta')" :description="__('Introduza os seus dados abaixo para criar a sua conta')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <flux:input
                name="name"
                :label="__('Nome')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Nome completo')"
            />

            <flux:input
                name="email"
                :label="__('Endereço de e-mail')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@exemplo.com"
            />

            <flux:input
                name="password"
                :label="__('Palavra-passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Palavra-passe')"
                viewable
            />

            <flux:input
                name="password_confirmation"
                :label="__('Confirmar palavra-passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmar palavra-passe')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Criar conta') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Já tem uma conta?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sessão') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>