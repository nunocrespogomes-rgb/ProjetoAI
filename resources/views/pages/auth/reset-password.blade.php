<x-layouts::auth :title="__('Repor palavra-passe')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Repor palavra-passe')" :description="__('Por favor, introduza a sua nova palavra-passe abaixo')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('E-mail')"
                type="email"
                required
                autocomplete="email"
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
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('Repor palavra-passe') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>