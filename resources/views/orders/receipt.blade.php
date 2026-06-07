<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ $order->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #111;
        }

        .meta-table,
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .items-table th {
            background-color: #f4f4f5;
            text-align: left;
            font-weight: bold;
            padding: 8px;
            border-bottom: 1px solid #e4e4e7;
        }

        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e4e4e7;
            vertical-align: middle;
        }

        .total-box {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .tshirt-container {
            width: 80px;
            height: 80px;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
            background-color: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 6px;
        }

        .tshirt-base-img {
            transform: scale(var(--scale, 0.82));
            -webkit-transform: scale(var(--scale, 0.82));
            transform-origin: center center;
            -webkit-transform-origin: center center;
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 80px;
            z-index: 1;
        }

        .tshirt-design-img {
            transform: scale(var(--scale, 0.75));
            -webkit-transform: scale(var(--scale, 0.75));
            transform-origin: center center;
            -webkit-transform-origin: center center;
            position: absolute;
            top: 27%;
            left: 27%;
            width: 46%;
            height: 46%;
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
                <th style="width: 90px; text-align: center;">Produto</th>
                <th>Descrição</th>
                <th style="text-align: center; width: 60px;">Qtd</th>
                <th style="text-align: right; width: 80px;">Preço Un.</th>
                <th style="text-align: right; width: 90px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="width: 90px; text-align: center; vertical-align: middle;">
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

                        $imagePath = '';
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
                        }

                        $sizeUpper = strtoupper(trim($item->size ?? 'M'));
                        $scale = 0.82;
                        if ($sizeUpper === 'XS') $scale = 0.62;
                        if ($sizeUpper === 'S')  $scale = 0.72;
                        if ($sizeUpper === 'L')  $scale = 0.91;
                        if ($sizeUpper === 'XL') $scale = 1.00;
                    @endphp

                    <div class="tshirt-container" style="--scale: {{ $scale }};">
                        @if(!empty($base64Image))
                            <img src="{{ $base64Image }}" class="tshirt-base-img">
                        @endif

                        @if(!empty($imagePath))
                            <img src="{{ $imagePath }}" class="tshirt-design-img">
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