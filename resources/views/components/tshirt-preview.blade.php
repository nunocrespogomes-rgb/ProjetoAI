@props([
'backgroundColor' => 'ffffff',
'designUrl' => '',
'alt' => '',
'scaleUp' => false,
'isCart' => false,
'isDetail' => false,
'size' => 'M',
'cartKey' => null
])

<div {{ $attributes->merge(['class' => 'relative w-full h-full flex items-center justify-center overflow-hidden rounded-lg bg-white']) }}>

    <div x-data="{ 
            currentColor: '{{ strtolower($backgroundColor) }}', 
            currentSize: '{{ strtoupper($size ?? 'M') }}',
            get scaleClass() {
                {{-- REMOVIDO: O 'if(isDetail) return scale-100' saiu daqui para permitir que os tamanhos variem visualmente --}}
                if (this.currentSize === 'XS') return 'scale-[0.62]';
                if (this.currentSize === 'S')  return 'scale-[0.72]';
                if (this.currentSize === 'L')  return 'scale-[0.91]';
                if (this.currentSize === 'XL') return 'scale-100';
                return 'scale-[0.82]'; // Default M
            }
         }"
        @change-color.window="if (!'{{ $cartKey }}' || $event.detail.key === '{{ $cartKey }}') currentColor = $event.detail.color"
        @change-size.window="if (!'{{ $cartKey }}' || $event.detail.key === '{{ $cartKey }}') currentSize = $event.detail.size"
        class="relative w-full h-full flex items-center justify-center overflow-hidden bg-transparent">
        {{-- 1. Imagem do Molde Base --}}
        <img :src="'/storage/tshirt_base/' + currentColor + '.jpg'"
            :class="scaleClass"
            class="absolute inset-0 w-full h-full object-contain pointer-events-none z-10 transition-transform duration-300"
            alt="Modelo T-Shirt"
            onerror="this.onerror=null; this.src='/storage/tshirt_base/1e1e21.jpg';">
            
        @if($designUrl)
        <div :class="scaleClass"
            class="absolute inset-0 flex items-center justify-center z-20 transition-transform duration-300 {{ $isDetail ? 'p-1' : ($isCart ? 'p-1' : 'p-4') }}">

            <img src="{{ $designUrl }}"
                alt="{{ $alt }}"
                class="{{ $isDetail ? 'w-[55%] h-[55%] mt-1' : ($isCart ? 'w-[50%] h-[50%] mt-0.5' : ($scaleUp ? 'w-[55%] h-[55%] mt-1' : 'w-[40%] h-[40%] mt-2')) }} object-contain drop-shadow-xl" />
        </div>
        @endif

    </div>

</div>