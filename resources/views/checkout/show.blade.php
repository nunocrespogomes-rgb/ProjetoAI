<x-layouts::main-content
    title="Finalizar Encomenda"
    heading="Finalizar Encomenda"
    subheading="Valida os teus dados de envio e pagamento">

    <div class="p-6">
        <form action="{{ route('cart.confirm') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2 space-y-4 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-4">Informação de Envio & Faturação</h2>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">NIF</label>
                    <input type="text" name="nif" value="{{ old('nif', $customer?->nif) }}" 
                           class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('nif') border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 @enderror" required>
                    @error('nif')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Endereço de Entrega</label>
                    <textarea name="address" 
                              class="w-full h-24 resize-none rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('address') border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 @enderror" required>{{ old('address', $customer?->address) }}</textarea>
                    @error('address')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Método de Pagamento</label>
                    <select name="payment_type" class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-primary-500" required>
                        <option value="Visa" {{ old('payment_type', $customer?->default_payment_type) == 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="PayPal" {{ old('payment_type', $customer?->default_payment_type) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                        <option value="MB WAY" {{ old('payment_type', $customer?->default_payment_type) == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Referência / Dados de Pagamento</label>
                    <input type="text" name="payment_ref" value="{{ old('payment_ref', $customer?->default_payment_ref) }}" 
                           placeholder="Nº Cartão (Visa), E-mail (PayPal) ou Telemóvel (MB WAY)"
                           class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-primary-500 @error('payment_ref') border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 @enderror" required>
                    @error('payment_ref')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Notas Adicionais (Opcional)</label>
                    <textarea name="notes" 
                              class="w-full h-24 resize-none rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-4 py-2 focus:ring-2 focus:ring-primary-500" placeholder="Informações relevantes para o processamento da encomenda...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sticky top-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-black text-zinc-900 dark:text-white mb-4">Resumo do Pedido</h2>
                        <div class="divide-y divide-zinc-200 dark:divide-zinc-700 max-h-72 overflow-y-auto pr-1">
                            @foreach($cart as $item)
                                <div class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-200 text-sm">{{ $item['name'] ?? 'T-Shirt' }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Tamanho: {{ $item['size'] }} | Cor: {{ $item['color_name'] ?? $item['color_code'] }}</p>
                                        <p class="text-xs text-zinc-400">Qtd: {{ $item['qty'] }} x {{ number_format($item['unit_price'], 2) }}€</p>
                                    </div>
                                    <span class="font-semibold text-sm text-zinc-900 dark:text-white">{{ number_format(($item['qty'] * $item['unit_price']), 2) }}€</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="flex justify-between items-center mb-6">
                            <span class="font-bold text-zinc-900 dark:text-white">Total a Pagar</span>
                            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($totalPrice, 2) }} €</span>
                        </div>
                        
                        <flux:button type="submit" variant="primary" class="w-full h-10 justify-center">
                            Confirmar Pagamento e Encomenda
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts::main-content>