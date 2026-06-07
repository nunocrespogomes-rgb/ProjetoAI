@props(['filterAction', 'resetUrl', 'name' => ''])

<div {{ $attributes->merge(['class' => 'p-4 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700']) }}>
    <form method="GET" action="{{ $filterAction }}" class="flex items-end gap-4">
        <div class="flex-1">
            <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                {{ __('Filtrar por Nome') }}
            </label>
            <input type="text" name="name" id="name" value="{{ $name }}" 
                   class="w-full bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-lg px-3 py-2 border border-zinc-300 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <flux:button type="submit" variant="filled" class="cursor-pointer">
            {{ __('Filtrar') }}
        </flux:button>

        @if(!empty($name))
            <flux:button href="{{ $resetUrl }}" variant="ghost">
                {{ __('Cancelar') }}
            </flux:button>
        @endif
    </form>
</div>