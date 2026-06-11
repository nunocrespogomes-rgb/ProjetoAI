<x-layouts::main-content title="Preços"
heading="Configuração de preços"
subheading="Define os preços atuais das t-shirts. Esta tabela deve ter apenas uma configuração ativa.">
    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-4xl space-y-6">

            @if(session('alert-msg'))
                <flux:callout :variant="session('alert-type') === 'success' ? 'success' : 'warning'">
                    {{ session('alert-msg') }}
                </flux:callout>
            @endif

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('admin.prices.update') }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                            <flux:heading size="lg">Imagens de catálogo</flux:heading>
                            <div class="mt-5 space-y-5">
                                <div>
                                    <flux:input type="number" step="0.01" min="0.01" name="unit_price_catalog" label="Preço unitário" value="{{ old('unit_price_catalog', $price->unit_price_catalog ?? '') }}" required />
                                    @error('unit_price_catalog') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <flux:input type="number" step="0.01" min="0.01" name="unit_price_catalog_discount" label="Preço com desconto" value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount ?? '') }}" required />
                                    @error('unit_price_catalog_discount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                            <flux:heading size="lg">Imagens personalizadas</flux:heading>
                            <div class="mt-5 space-y-5">
                                <div>
                                    <flux:input type="number" step="0.01" min="0.01" name="unit_price_own" label="Preço unitário" value="{{ old('unit_price_own', $price->unit_price_own ?? '') }}" required />
                                    @error('unit_price_own') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <flux:input type="number" step="0.01" min="0.01" name="unit_price_own_discount" label="Preço com desconto" value="{{ old('unit_price_own_discount', $price->unit_price_own_discount ?? '') }}" required />
                                    @error('unit_price_own_discount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
                        <flux:input type="number" min="1" name="qty_discount" label="Quantidade mínima para desconto" value="{{ old('qty_discount', $price->qty_discount ?? '') }}" required />
                        @error('qty_discount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-2 text-sm text-zinc-500">
                            Exemplo: se for 10, o preço com desconto aplica-se quando o item tiver 10 ou mais unidades.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <flux:button type="submit" variant="primary" icon="check">Guardar preços</flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::main-content>
