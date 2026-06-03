<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode Barang</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 10mm;
        }

        .barcode-table {
            width: 100%;
            border-collapse: collapse;
            page-break-after: always;
        }

        .barcode-table:last-child {
            page-break-after: avoid;
        }

        .barcode-cell {
            width: 25%;
            height: 20mm; 
            text-align: center;
            padding: 1mm;
            vertical-align: top;
            border: 0.5px solid #ccc;
            box-sizing: border-box;
            overflow: hidden;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .barcode-item img {
            width: 90%;
            height: 25px;
            margin-bottom: 1mm;
        }

        .barcode-item p {
            margin: 0;
            padding: 0;
            font-size: 6.5pt;
            line-height: 1.1;
        }

        .barcode-item .item-name {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .barcode-item .item-price {
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .barcode-item .item-barcode-value {
            font-size: 5.5pt;
            margin-top: 0.3mm;
        }
    </style>
</head>
<body>
    @php
        $sizeConfigMap = [
            'small' => [
                'cell_height' => '20mm',
                'img_height' => '25px',
                'name_font' => '6.5pt',
                'price_font' => '6.5pt',
                'barcode_value_font' => '5.5pt',
            ],
            'medium' => [
                'cell_height' => '26mm',
                'img_height' => '34px',
                'name_font' => '7.5pt',
                'price_font' => '7.5pt',
                'barcode_value_font' => '6.5pt',
            ],
            'large' => [
                'cell_height' => '32mm',
                'img_height' => '42px',
                'name_font' => '8.5pt',
                'price_font' => '8.5pt',
                'barcode_value_font' => '7.5pt',
            ],
        ];

        $chunkedBarcodes = array_chunk($barcodesData, 52);
    @endphp

    @foreach ($chunkedBarcodes as $pageBarcodes)
        <table class="barcode-table">
            @foreach (array_chunk($pageBarcodes, 4) as $row)
                <tr>
                    @foreach ($row as $data)
                        @php
                            $itemSize = $sizeConfigMap[$data['size'] ?? 'small'] ?? $sizeConfigMap['small'];
                        @endphp
                        <td class="barcode-cell" style="height: {{ $itemSize['cell_height'] }};">
                            <div class="barcode-item">
                                <p class="item-name" style="font-size: {{ $itemSize['name_font'] }};">{{ $data['nama'] }}</p>
                                <p class="item-price" style="font-size: {{ $itemSize['price_font'] }};">Rp {{ number_format($data['harga'], 0, ',', '.') }}</p>
                                <img src="data:image/svg+xml;base64,{{ base64_encode($data['barcode_svg']) }}" alt="Barcode" style="height: {{ $itemSize['img_height'] }};">
                                <p class="item-barcode-value" style="font-size: {{ $itemSize['barcode_value_font'] }};">{{ $data['barcode_value'] }}</p>
                            </div>
                        </td>
                    @endforeach
                    @if (count($row) < 4)
                        @for ($i = 0; $i < (4 - count($row)); $i++)
                            <td class="barcode-cell"></td>
                        @endfor
                    @endif
                </tr>
            @endforeach
        </table>
    @endforeach
</body>
</html>
