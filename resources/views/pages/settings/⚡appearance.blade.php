<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Definições de Aspeto')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Definições de Aspeto') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Aspeto')" :subheading="__('Atualize as definições de aspeto da sua conta')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Escuro') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>