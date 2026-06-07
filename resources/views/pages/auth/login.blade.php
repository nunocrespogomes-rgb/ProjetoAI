<x-layouts::auth :title="__('Iniciar sessão')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Inicie sessão na sua conta')" :description="__('Introduza o seu e-mail e palavra-passe abaixo para iniciar sessão')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="email"
                :label="__('Endereço de e-mail')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@exemplo.com"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Palavra-passe')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Palavra-passe')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Esqueceu-se da sua palavra-passe?') }}
                    </flux:link>
                @endif
            </div>

            <flux:checkbox name="remember" :label="__('Lembrar-me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Iniciar sessão') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Não tem uma conta?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Registe-se') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>