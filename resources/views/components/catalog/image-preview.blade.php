<div class="flex flex-col items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-800 w-full aspect-square max-w-md mx-auto overflow-hidden"
     style="background-color: #ffffff;">
    
    @if($tshirtImage->image_url)
        {{-- Inicializamos o Alpine.js. Se o tamanho por defeito puder ser XS, podes alterar para currentSize: 'M' ou 'XS' --}}
        <div x-data="{ currentColor: '1e1e21', currentSize: 'M' }"
             @change-color.window="currentColor = $event.detail.color"
             @change-size.window="currentSize = $event.detail.size"
             class="relative w-full h-full flex items-center justify-center overflow-hidden bg-transparent">
            
            {{-- 1. Imagem do Molde Base da T-Shirt --}}
            <img :src="'/storage/tshirt_base/' + currentColor + '.jpg'" 
                 :class="{
                    'scale-[0.62]': currentSize === 'XS',
                    'scale-[0.72]': currentSize === 'S',
                    'scale-[0.82]': currentSize === 'M',
                    'scale-[0.91]': currentSize === 'L',
                    'scale-100': currentSize === 'XL'
                 }"
                 class="absolute inset-0 w-full h-full object-contain pointer-events-none z-10 transition-transform duration-300 ease-in-out bg-transparent" 
                 alt="Modelo T-Shirt Base"> 

            {{-- 2. Contentor e Imagem da Estampa (Desenho) --}}
            <div :class="{
                    'scale-[0.62]': currentSize === 'XS',
                    'scale-[0.72]': currentSize === 'S',
                    'scale-[0.82]': currentSize === 'M',
                    'scale-[0.91]': currentSize === 'L',
                    'scale-100': currentSize === 'XL'
                 }"
                 class="absolute inset-0 flex items-center justify-center z-20 p-4 transition-transform duration-300 ease-in-out bg-transparent">
                
                <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}"
                     class="w-[50%] h-[50%] object-contain mt-1 drop-shadow-xl bg-transparent"
                     alt="{{ $tshirtImage->name }}">
            </div>

        </div>
    @else
        <span class="text-zinc-400 dark:text-zinc-500">
            Sem imagem disponível
        </span>
    @endif

</div>