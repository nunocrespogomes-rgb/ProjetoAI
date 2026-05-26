<?php

use Livewire\Component;

new class extends Component
{
    public int $qty = 1;
    public string $inputName = 'qty';

    public function mount(int $qty = 1, string $inputName = 'qty'): void
    {
        $this->qty = $qty;
        $this->inputName = $inputName;
    }

    public function increment()
    {
        if ($this->qty < 100) {
            $this->qty++;
        }
    }

    public function decrement()
    {
        if ($this->qty > 1) {
            $this->qty--;
        }
    }
};
?>

<div class="w-40">
    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
        Quantidade
    </label>

    <div class="flex items-center justify-between rounded-lg border border-zinc-300 dark:border-zinc-600 overflow-hidden bg-white dark:bg-zinc-800">

        <button
            type="button"
            wire:click="decrement"
            class="px-4 py-2 text-lg font-bold text-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
        >
            -
        </button>

        <span class="flex-1 text-center text-sm font-semibold text-zinc-900 dark:text-white">
            {{ $qty }}
        </span>

        <button
            type="button"
            wire:click="increment"
            class="px-4 py-2 text-lg font-bold text-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
        >
            +
        </button>
    </div>

    <input type="hidden" name="qty" value="{{ $qty }}">
</div>
