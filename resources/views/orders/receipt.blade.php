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
        .tshirt-preview-container { position: relative; width: 60px; height: 60px; margin: 0 auto; background-color: #fff; border: 1px solid #e4e4e7; border-radius: 4px; }
        .tshirt-base { position: absolute; top: 0; left: 0; width: 60px; height: 60px; z-index: 1; }
        .tshirt-estampa { position: absolute; top: 15px; left: 15px; width: 30px; height: 30px; z-index: 2; }
    </style>
</head>
<body>
    <div class="header">
        <span class="invoice-title">FunShirt</span><br>
        <strong>RECIBO DE ENCOMENDA #{{ $order->id }}</strong><br>
        <span>Data do Pedido: {{ date('d/m/Y', strtotime($order->date)) }}</span>
    </div>
    <table class="meta-table">
        <tr>
            <td style="width: 50%;">
                <strong>Dados de Faturação:</strong><br>
                Nome: {{ $order->customer->user->name ?? 'N/A' }}<br>
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
                <th style="width: 80px; text-align: center;">Produto</th>
                <th>Descrição</th>
                <th style="text-align: center; width: 60px;">Qtd</th>
                <th style="text-align: right; width: 80px;">Preço Un.</th>
                <th style="text-align: right; width: 90px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="text-align: center;">
                    <div class="tshirt-preview-container">
                        @php
                            $colorCode = strtolower($item->color_code ?? ($item->color->code ?? 'ffffff'));
                            $baseFile = public_path('storage/tshirt_base/' . $colorCode . '.jpg');
                            if (!file_exists($baseFile)) {
                                $baseFile = public_path('storage/tshirt_base/' . $colorCode . '.png');
                            }
                        @endphp
                        @if(file_exists($baseFile))
                            <img src="{{ $baseFile }}" class="tshirt-base">
                        @endif
                        @if($item->tshirtImage && $item->tshirtImage->image_url)
                            @php
                                $isCatalog = is_null($item->tshirtImage->customer_id);
                                $estampaFile = $isCatalog ? public_path('storage/tshirt_images/' . $item->tshirtImage->image_url) : storage_path('app/public/my_images/' . $item->tshirtImage->image_url);
                            @endphp
                            @if(file_exists($estampaFile))
                                <img src="{{ $estampaFile }}" class="tshirt-estampa">
                            @endif
                        @endif
                    </div>
                </td>
                <td>
                    <strong>{{ $item->tshirtImage->name ?? 'T-Shirt Customizada' }}</strong><br>
                    <span style="color: #666; font-size: 11px;">Tamanho: {{ $item->size }} | Cor: {{ $item->color->name ?? 'N/A' }}</span>
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