<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <flux:input name="name" label="{{ __('Nome') }}" class="grow" value="{{ $name }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <flux:button variant="filled" type="submit" class="w-full">{{ __('Filtrar') }}</flux:button>
                </div>
                <div>
                    <flux:button variant="subtle" :href="$resetUrl" class="w-full">{{ __('Cancelar') }}</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>