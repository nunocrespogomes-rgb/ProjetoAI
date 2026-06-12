<x-layouts::main-content
    title="Carrinho"
    heading="Carrinho"
    subheading="Revê os produtos antes de finalizar">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-start">
            <div class="my-4 p-6 w-full">

                @if(empty($cart))
                    <x-cart.empty />
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-2 space-y-4">
                            @foreach($cart as $cartKey => $item)
                                <x-cart.item
                                    :cartKey="$cartKey"
                                    :item="$item"
                                    :sizes="$sizes"
                                    :colors="$colors"
                                />
                            @endforeach
                        </div>

                        <div class="lg:col-span-1">
                            <x-cart.summary :cart="$cart" />
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layouts::main-content>
