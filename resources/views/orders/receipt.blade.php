<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 13px; line-height: 1.5; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #111; }
        .meta-table, .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .meta-table td { padding: 4px 0; vertical-align: top; }
        .items-table th { background-color: #f4f4f5; text-align: left; font-weight: bold; padding: 8px; border-bottom: 1px solid #e4e4e7; }
        .items-table td { padding: 12px 8px; border-bottom: 1px solid #e4e4e7; vertical-align: middle; }
        .total-box { text-align: right; margin-top: 20px; font-size: 16px; font-weight: bold; }
        
        /* Estrutura de sobreposição estável para o Preview da T-Shirt (G7) */
        .tshirt-container {
            width: 60px;
            height: 60px;
            position: relative;
            border: 1px solid #e4e4e7;
            border-radius: 6px;
            display: block;
        }
        .tshirt-base {
            position: absolute;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            z-index: 1;
        }
        .tshirt-design {
            position: absolute;
            z-index: 2;
        }
    </style>
</head>
<body>

    <div class="header">
        <span class="invoice-title">FunShirt</span><br>
        <strong>RECIBO DE ENCOMENDA #{{ $order->id }}</strong><br>
        <span>Data do Pedido: {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i') }}</span>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%;">
                <strong>Dados de Faturação:</strong><br>
                NIF: {{ $order->nif }}<br>
                Método de Pagamento: {{ $order->payment_type }} ({{ $order->payment_ref }})
            </td>
            <td style="width: 50%;">
                <strong>Endereço de Entrega:</strong><br>
                {{ $order->address }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 70px;">Produto</th>
                <th>Descrição</th>
                <th style="text-align: center; width: 60px;">Qtd</th>
                <th style="text-align: right; width: 80px;">Preço Un.</th>
                <th style="text-align: right; width: 90px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    @php
                        $colorCode = strtolower($item->color_code ?? ($item->color->code ?? 'ffffff'));
                        
                        $basePath = public_path('storage/tshirt_base/' . $colorCode . '.jpg');
                        
                        if (!file_exists($basePath)) {
                            $basePath = public_path('storage/tshirt_base/' . $colorCode . '.png');
                        }
                        
                        $base64Image = '';
                        if (file_exists($basePath)) {
                            $imageData = base64_encode(file_get_contents($basePath));
                            $mimeType = pathinfo($basePath, PATHINFO_EXTENSION) === 'png' ? 'image/png' : 'image/jpeg';
                            $base64Image = 'data:' . $mimeType . ';base64,' . $imageData;
                        }

                        $htmlDesignStyle = "";
                        $imagePath = "";
                        
                        if ($item->tshirtImage && $item->tshirtImage->image_url) {
                            $isCatalog = is_null($item->tshirtImage->customer_id);
                            $imagePath = $isCatalog 
                                ? public_path('storage/tshirt_images/' . $item->tshirtImage->image_url)
                                : storage_path('app/public/my_images/' . $item->tshirtImage->image_url);
                            
                            if (file_exists($imagePath)) {
                                $designData = base64_encode(file_get_contents($imagePath));
                                $imagePath = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $designData;
                            } else {
                                $imagePath = '';
                            }

                            $size = strtoupper(trim($item->size ?? 'M'));
                            
                            $offset = 18; // Padrão para M
                            if ($size == 'XS') $offset = 22;
                            if ($size == 'S')  $offset = 20;
                            if ($size == 'M')  $offset = 18;
                            if ($size == 'L')  $offset = 16;
                            if ($size == 'XL') $offset = 14;
                            
                            $dim = 60 - ($offset * 2);
                            
                            $topOffset = $offset + 2; 

                            $htmlDesignStyle = 'style="top: ' . $topOffset . 'px; left: ' . $offset . 'px; width: ' . $dim . 'px; height: ' . $dim . 'px;"';
                        }
                    @endphp

                    <div class="tshirt-container">
                        {{-- 1. Base real da T-Shirt Colorida (.jpg convertida para Base64) --}}
                        @if(!empty($base64Image))
                            <img src="{{ $base64Image }}" class="tshirt-base">
                        @endif

                        {{-- 2. Desenho/Estampa sobreposta --}}
                        @if(!empty($imagePath))
                            <img src="{{ $imagePath }}" class="tshirt-design" {!! $htmlDesignStyle !!}>
                        @endif
                    </div>
                </td>
                
                <td>
                    <strong>{{ $item->tshirtImage->name ?? 'T-Shirt Customizada' }}</strong><br>
                    <span style="color: #666; font-size: 11px;">
                        Tamanho: {{ $item->size }} | Cor: {{ $item->color->name ?? 'N/A' }}
                    </span>
                </td>
                <td style="text-align: center;">{{ $item->qty }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}€</td>
                <td style="text-align: right;">{{ number_format($item->sub_total, 2) }}€</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Pago: {{ number_format($order->total_price, 2) }}€
    </div>

    @if($order->notes)
    <div style="margin-top: 30px; background: #f4f4f5; padding: 10px; border-radius: 4px;">
        <strong>Notas Adicionais:</strong><br>
        <em style="color: #555;">{{ $order->notes }}</em>
    </div>
    @endif

</body>
</html>