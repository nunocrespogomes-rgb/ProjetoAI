<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Definições de Perfil')] class extends Component {
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (Auth::user()->user_type === 'F') {
            return;
        }

        // 1. Validar os dados incluindo a nova regra para a foto
        // 1. Validar os dados incluindo o caminho completo para a Rule e para o User
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique(\App\Models\User::class)->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:1024'], // Foto opcional, máximo 1MB (1024 KB)
        ]);

        // 2. Lógica de Upload da Fotografia do Cliente
        if ($this->photo) {
            // Se o cliente já tinha uma foto guardada, apaga o ficheiro antigo para não acumular lixo
            if ($user->photo_url) {
                Storage::disk('public')->delete('users_photos/' . $user->photo_url);
            }

            // Guarda a imagem nova na pasta storage/app/public/users_photos
            $path = $this->photo->store('users_photos', 'public');

            // Atualiza a coluna photo_url na base de dados com o nome único gerado
            $user->photo_url = basename($path);
        }

        // 3. Atualizar os restantes dados de texto
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Se o email mudou, remove a verificação antiga (padrão do Breeze)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('Um novo link de verificação foi enviado para o seu endereço de email.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return ! $user instanceof MustVerifyEmail || ($user instanceof MustVerifyEmail && $user->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Definições de Perfil') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Perfil')" :subheading="__('Atualize o seu nome e endereço de email')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="flex items-center gap-4 mb-6">
                @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover border border-zinc-200">
                @elseif (auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_full_url }}" class="w-16 h-16 rounded-full object-cover border border-zinc-200">
                @else
                <div class="w-16 h-16 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 font-bold text-xl">
                    {{ auth()->user()->initials() }}
                </div>
                @endif

                <flux:input type="file" wire:model="photo" label="Fotografia de Perfil" accept="image/*" />
            </div>
            <flux:input wire:model="name" :label="__('Nome')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                <div>
                    <flux:text class="mt-4">
                        {{ __('O seu endereço de email não está verificado.') }}

                        <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('Clique aqui para reenviar o email de verificação.') }}
                        </flux:link>
                    </flux:text>
                </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-profile-button">
                    {{ __('Guardar') }}
                </flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
        <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>